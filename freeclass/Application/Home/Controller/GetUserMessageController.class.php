<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/11/24
 * Time: 15:13
 */

namespace Home\Controller;

use Think\Controller;

class GetUserMessageController extends Controller
{
    private $name;  //姓名
    private $campus;//校区


    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

    public function GetUser(){
        $load = new LoginController();
        preg_match_all("/<td [\w\W]*?>([\w\W]*?)<\/td>/",$load->Load(),$message);
        $this->name = $message[1][5];
        $this->campus = $message[1][9];
    }

    //登录到课表界面并获取课表
    public function GetCourse(){
        if(GetSOrT()=='S'){
            $temp_url="http://jiaowu.sicau.edu.cn/xuesheng/gongxuan/gongxuan/kbbanji.asp";
            $result = $this->visit($temp_url);
            preg_match_all("/<a  class=menu href=\"([\w\W]*?)\">/",$result,$link);
            $url="http://jiaowu.sicau.edu.cn/xuesheng/gongxuan/gongxuan/".$link[1][1];
            $coursePage = $this->visit($url);
            preg_match_all("/<td [\w\W]*?>([\w\W]*?)<\/td>/",$coursePage,$courseMessage);
            $j=0;
            for($i=22;$i<count($courseMessage[1]);$i=$i+17){
                $course[$j] =str_replace('&nbsp;','',strip_tags($courseMessage[1][$i]));
                $j++;
            }
            $this->GetUser();

        }elseif (GetSOrT()=='T'){
            $url="http://jiaowu.sicau.edu.cn/jiaoshi/paike/kebiao/kbjiaoshi.asp?xueqi=".GetSemester();
            $coursePage = $this->visit($url);
            preg_match_all("/<td [\w\W]*?>([\w\W]*?)<\/td>/",$coursePage,$courseMessage);
            $j=0;
            for($i=68;$i<count($courseMessage[1]);$i=$i+12){
                $course[$j] =str_replace(' &nbsp;','',$courseMessage[1][$i]);
                $j++;
            }
            $this->GetUser();

            //获取老师上课学生的名单
            $teacher = new TeacherController();
            $teacher->getStudentName();
        }
        if(empty(D('user')->where(array('id'=>I('post.user')))->getField('name'))){
            $data=array(
                'id'=>I('post.user'),
                'name'=>$this->name,
                'userstatus'=>GetSOrT(),
                'campus'=>$this->campus
            );
            D('User')->add($data);
        }
        for($i=0;$i<count($course);$i++){
            $id = D('course')->where("coursename = '$course[$i]'")->getField('id');
            $data2=array('userid'=>I('post.user'),'courseid'=>$id);
            M('user_course')->add($data2);
        }
    }

    /**
     * @param $url
     * @return mixed|string
     * 访问对应的界面
     */
    public function visit($url){
        $cookie_path=dirname(__RUNTIME__)."/cookie/".$_POST['user']."login.cookie";
        $cookie=dirname(__RUNTIME__)."/cookie/".$_POST['user']."getCourse.cookie";
        $data="user=".$this->user."&pwd=".$_POST['pwd']."&lb=".GetSOrT()."&submit=";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
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