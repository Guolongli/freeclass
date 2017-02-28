<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/12/8
 * Time: 14:45
 */

namespace Home\Controller;

use Think\Controller;
class TeacherController extends Controller
{

    /**
     * 老师查看本教学班学生信息
     */
    public function Look(){
        $Course = urldecode(I('get.course'));
        $CourseMessage = preg_split('/<br>/',$Course);
        $CourseId = D('Course')->where(array('coursename'=>$CourseMessage[0]))->getField('id');
        $UserMessage = D('Course')->Relation('user')->find($CourseId);
        $student = M('teacher_student')->where(array('coursename'=>$CourseMessage[0],'teacherid'=>decrypt($_SESSION['user'])))->select();
        for($i=0;$i<count($UserMessage['user']);$i++){
            $isStudetId[$i] = $UserMessage['user'][$i]['id'];
            $isStudentName[$i] = $UserMessage['user'][$i]['name'];
        }
        for($i=0;$i<count($student);$i++){
            $allStudentId[$i] = $student[$i]['studentid'];
        }
        $noStudetId = array_diff($allStudentId,$isStudetId);
        for($i=0;$i<count($noStudetId);$i++){
            $noStudetName[$i] = M('teacher_student')->where(array('coursename'=>$CourseMessage[0],'teacherid'=>decrypt($_SESSION['user']),'studentid'=>$noStudetId[$i]))->getField('studentname');
        }
        $allStudentName = array($isStudentName,$noStudetName);
        file_put_contents('./temp.txt',$allStudentName);
        $this->ajaxReturn($allStudentName);
    }

    /**
     * 获取对应教学班的学生名单
     */
    public function getStudentName(){
        $courseMessageHtml = $this->visit("http://jiaowu.sicau.edu.cn//jiaoshi/cj/kcxl_js/xuanke.asp","http://jiaowu.sicau.edu.cn//jiaoshi/cj/kcxl_js/jxrw.asp?xueqi=".GetSemester());
        preg_match_all("/<td [\w\W]*?>([\w\W]*?)<\/td>/",$courseMessageHtml,$HtmlMessage);
        for($i=0;$i<(count($HtmlMessage[1])-37)/28-1;$i++){
            $courseBianHao = $i*28+39;
            $urlBianHao = $i*28+64;
            $course[$i]['bianhao'] = $HtmlMessage[1][$courseBianHao];
            preg_match_all("/bianhao=([\w\W]*?)\"/",$HtmlMessage[1][$urlBianHao],$tempBianHao);
            $course[$i]['url'] = $tempBianHao[1][0];
        }
        for($i=0;$i<count($course);$i++){
            $studentHtml = $this->visit("http://jiaowu.sicau.edu.cn//jiaoshi/cj/kcxl_js/showxs.asp?bianhao=".$course[$i]['url'],'http://jiaowu.sicau.edu.cn//jiaoshi/cj/kcxl_js/xuanke.asp');
            preg_match_all("/<p align=\"center\">([\w\W]*?)<\/p>/",$studentHtml,$pHtmlMessage);
            for($j=1;$j<count($pHtmlMessage[1]);$j+=2){
                $studentMessage = preg_split('/<br>/',$pHtmlMessage[1][$j]);
                $data = array(
                    'teacherid' => I('post.user'),
                    'studentid' => trim($studentMessage[0]),
                    'studentname' => trim($studentMessage[1]),
                    'coursename' =>D('course')->where(array('bianhao'=>$course[$i]['bianhao']))->getField('coursename')
                );
                M('teacher_student')->add($data);
            }
        }
    }

    public function visit($url,$referurl){
        $cookie_path=dirname(__RUNTIME__)."/login.cookie";
        $cookie=dirname(__RUNTIME__)."/getCourse.cookie";
        $data="user=".$this->user."&pwd=".$_POST['pwd']."&lb=".GetSOrT()."&submit=";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_REFERER, $referurl);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_COOKIEFILE, $cookie_path);
        curl_setopt($ch,CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $ret=curl_exec($ch);
        curl_close($ch);
        $ret=iconv('gbk', 'utf-8', $ret);
        return $ret;
    }
}