<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/12/9
 * Time: 10:14
 */

namespace Home\Controller;


use Think\Controller;

class FreeClassController extends BaseController
{
    private $WeekFlag;
    private $WeekStuNo;
    private $WeekStuName;

    /**
     * 显示空课统计的输入页面
     */
    public function InputOption(){
        if(I('get.action') == 'teacher'){
            $courseMessage = urldecode(I('get.course'));
            $courseName = preg_split('/<br>/',$courseMessage);
            $CourseId = D('Course')->where(array('coursename'=>$courseName[0]))->getField('id');
            $MyGroup['groups'][0]= array(
                'name' => $courseName[0],
                'id' => 'C'.$CourseId
            );
        }else{
            $UserId = decrypt($_SESSION['user']);
            $MyGroup = D('User')->Relation('groups')->find($UserId);
        }
        $this->assign('mygroups',$MyGroup['groups']);
        $this->display();
    }

    /**
     * 统计空课
     */
    public  function GetFreeClass(){
        if($PostWeek = $this->CheckFreeClass()){

        }else{
            $this -> error('周次输入有误，请重新输入！');
        }
        $GroupName = I('post.groupname');
        if(!$GroupName){
            $this->error('群组名不能为空，请重新选择！');
        }else{
            if(strstr($GroupName,'C')){
                $CourseId = str_replace('C','',$GroupName);
                $CheckStatus = M('user_course')->where(array('userid'=>decrypt($_SESSION['user']),'courseid'=>$CourseId))->getField('courseid');
                if(empty($CheckStatus)){
                    $this->error('对不起，你没有权限');
                }
                $GroupMessage = D('Course')->Relation('user')->find($CourseId);
                S('groupname','C'.$GroupName);
            }else{
                $CheckStatus = M('user_group')->where(array('userid'=>decrypt($_SESSION['user']),'groupid'=>$GroupName))->getField('status');
                if(empty($CheckStatus)){
                    $this->error('对不起，你没有统计该组空课的权限');
                }
                $GroupMessage = D('Groups')->Relation('user')->find($GroupName);
                S('groupname',$GroupName);
            }
            if(!$GroupMessage){
                $this->error('群组名不存在，请重新选择！');
            }else{
                $Members = $GroupMessage['user'];
            }
        }
        //初始化一个35位的数组，对应35节课
        for($i=1;$i<36;$i++){
            $this->WeekFlag[$i] = 0;
            $this->WeekStuNo[$i]='';
            $this->WeekStuName[$i] = '';
        }

        //循环成员
        for($i = 0;$i<count($Members);$i++){
            $this->StatisticalFreeClass($Members[$i]['id'],$PostWeek);
        }
        $this->assign('WeekFlag',$this->WeekFlag);
        $this->assign('WeekStuNo',$this->WeekStuNo);
        $this->assign('WeekName',$this->WeekStuName);
        S('WeekName',$this->WeekStuName);
        $recordData = array(
            'user' => decrypt($_SESSION['user']),
            'operation' => "统计'".$GroupMessage['name']."'群组空课",
            'time' => GetTime()
        );
        M('record')->add($recordData);
        $this->display();
    }

    /**
     * 检查提交数据（周次）是否符合规范
     * 符合规范，通过二进制转换成十进制数
     * 不符合规范，提示错误信息
     */
    private function CheckFreeClass(){
        $Week = I('post.week');
        $WeekArray = preg_split('/周/',$Week);
        $Sum = 0;
        for($i=0;$i<count($WeekArray)-1;$i+=1){
            if($WeekArray[$i]>0&&$WeekArray[$i]<21){
                $Sum += pow(2,$WeekArray[$i]-1);
            }else{
                return false;
            }
        }
        return $Sum;
    }

    /**
     * @param $MemberId 每个群组成员的学号
     * 统计空课核心算法
     */
    private function StatisticalFreeClass($MemberId,$PostWeek){

        //根据每位成员的学号取出对应的课表
        $UserMessage = D('user')->Relation('course')->find($MemberId);
        $Courses = $UserMessage['course'];

        //取出课表中的time,并根据time，将字段week值赋给$TimeFlag[$Time[$j]]，用于或许统计时进行与运算
        for($i=1;$i<36;$i++){
            $TimeFlag[$i] = 0;
            $SFlag[$i] =0;
        }
        for($i=0;$i<count($Courses);$i++){
            $Time = preg_split('/,/',$Courses[$i]['time']);
            for($j=0;$j<count($Time);$j++){
                //如果在某个时间段有两节课，那么先将这两节课的week进行或运算，再重新赋给$TimeFlag[$Time[$j]]
                if($TimeFlag[$Time[$j]]){
                    $TimeFlag[$Time[$j]] = ((int)$Courses[$i]['week']|$TimeFlag[$Time[$j]]);
                }else{
                    $TimeFlag[$Time[$j]] = $Courses[$i]['week'];
                }
            }
        }
        //进行统计空课
        for($i=1;$i<36;$i++){
            if(($TimeFlag[$i]&$PostWeek) == 0){
                $this->WeekFlag[$i]++;
                $this->WeekStuNo[$i] .= '<li>'.$UserMessage['id'].'</li>';
                $this->WeekStuName[$i] .= '<li>'.$UserMessage['name'].'</li>';
                $SFlag[$i] = 1;
            }else{
                $SFlag[$i] = 0;
            }
        }
        $this->Excel($SFlag,$UserMessage['name'],$UserMessage['id']);
    }

    /**
     * @param $FreeClass
     * @param $StuName
     * @param $StuNo
     * 将每个人的空课情况写入缓存，准备导出
     */
    private function Excel($FreeClass,$StuName,$StuNo){
        $FreeClassFlag = array(0);
        for($i=1,$j=0;$i<=count($FreeClass);$i++){
            $temp = $i*$FreeClass[$i]%5;
            if($temp == 0){
                $temp = 5;
            }
            $FreeClassFlag[$j] = ceil($i*$FreeClass[$i]/5).'-'.($temp);
            $j+=$FreeClass[$i];
        }
        $FreeClassImplod = implode(',',$FreeClassFlag);
        $UserFreeClass = array(
            'id' => $StuNo,
            'name' => $StuName,
            'freeclass' =>  $FreeClassImplod
        );
        S($StuNo,$UserFreeClass);
    }

    /**
     * 导出excel文件
     */
    public function DownLoadExcel(){
        if(strstr(S('groupname'),'C')){
            $CourseId = str_replace('C','',S('groupname'));
            $GroupMessage = D('Course')->Relation('user')->find($CourseId);
        }else{
            $GroupMessage = D('Groups')->Relation('user')->find(S('groupname'));
        }
        for($i = 0;$i<count($GroupMessage['user']);$i++){
            $XlsData[$i] = S($GroupMessage['user'][$i]['id']);
        }
        $DownLoadExcel = new DownLoadFileController();
        $DownLoadExcel->expUser($GroupMessage['name'],$XlsData);
    }

    /**
     * 导出word文件
     */
    public function DownLoadWord(){
        $WeekName = S('WeekName');
        $WeekName = str_replace('<li>','',$WeekName);
        $WeekName = str_replace('</li>',"\r\n\r\n\r\n ",$WeekName);
        $DownLoadWord = new DownLoadFileController();
        $DownLoadWord->exportWord($WeekName);
    }
}