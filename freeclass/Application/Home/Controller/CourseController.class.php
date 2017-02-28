<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/12/20
 * Time: 9:58
 */

namespace Home\Controller;


class CourseController extends BaseController
{
    private $action;        //提交到本页面的动作

    public function __construct()
    {
        parent::__construct();
        $this->action = empty(I('get.action'))?'':I('get.action');
    }

    /**
     * 添加课程
     */
    public function AddCourse(){
        if($this->action == 'addcourse'){
            $weekMessage = I('post.week');
            $week = 0;
            $weekArray = preg_split('/周/',$weekMessage);
            for($i=0;$i<count($weekArray)-1;$i++){
                $week += pow(2,$weekArray[$i]-1);
            }
            $data = array(
                'coursename'=>'*'.I('post.coursename'),
                'time'=>I('post.time'),
                'weekmessage'=>$weekMessage,
                'week' =>$week,
                'isauto' => '1'
            );
            if(D('course')->create($data)){
                D('course')->add($data);
                $courseid = D('Course')->where(array('coursename'=>'*'.I('post.coursename')))->getField('id');
                M('user_course')->add(array('userid'=>decrypt($_SESSION['user']),'courseid'=>$courseid));
                $recordData = array(
                    'user' => decrypt($_SESSION['user']),
                    'operation' => "添加'".I('post.coursename')."'课程",
                    'time' => GetTime()
                );
                M('record')->add($recordData);
                $this->success('添加成功','Addcourse');
                exit;
            }else{
                $this->error(D('course')->getError());
            }
          }
        $this->display();
    }

    /**
     * 删除课程
     */
    public function DeleteCourse(){
        if($this->action == 'deleteCourse'){
            $course = I('post.course');
            $status = M('user_course')->where(array('userid'=>decrypt($_SESSION['user']),'courseid'=>$course))->select();
            $isAuto = D('course')->where(array('id'=>$course))->getField('isauto');
            if($status&&$isAuto=='1'){
                $courseName = M('course') -> where(array('id'=>$course))->getField('coursename');
                $recordData = array(
                    'user' => decrypt($_SESSION['user']),
                    'operation' => "删除'$courseName'课程",
                    'time' => GetTime()
                );
                M('record')->add($recordData);
                D('course')->where(array('id'=>$course))->delete();
                M('user_course')->where(array('courseid'=>$course,'userid'=>decrypt($_SESSION['user'])))->delete();
                $this->ajaxReturn(1);    //ajax返回1代表删除成功
            }else{
                $this->ajaxReturn(-1);   //ajax返回-1代表用户无权操作次课程
            }
        }
        $userMessage = D('user')->Relation('course')->find(decrypt($_SESSION['user']));
        $course = array();
        $k = array();
        for($i=0;$i<count($userMessage['course']);$i++){
            if(substr($userMessage['course'][$i]['coursename'],0,1) == '*'){
                if(empty($course[$userMessage['course'][$i]['time']][0])){
                    $k[$userMessage['course'][$i]['time']] = 0;
                    $course[$userMessage['course'][$i]['time']][0]['coursename'] = $userMessage['course'][$i]['coursename'].'<br>'.$userMessage['course'][$i]['weekmessage'];
                    $course[$userMessage['course'][$i]['time']][0]['id'] = $userMessage['course'][$i]['id'];
                }else{
                    $k[$userMessage['course'][$i]['time']]++;
                    $course[$userMessage['course'][$i]['time']][$k[$userMessage['course'][$i]['time']]]['coursename'] = $userMessage['course'][$i]['coursename'].'<br>'.$userMessage['course'][$i]['weekmessage'];
                    $course[$userMessage['course'][$i]['time']][$k[$userMessage['course'][$i]['time']]]['id'] = $userMessage['course'][$i]['id'];
                }
            }
        }
        $this->assign('course',$course);
        $this->display();
    }

    /**
     * 更新课表
     */
    public function ResetCourse(){
        M('user_course')->where(array('userid'=>decrypt($_SESSION['user'])))->delete();
        unset($_SESSION['user']);
        $this->display('Login/login');
    }
}