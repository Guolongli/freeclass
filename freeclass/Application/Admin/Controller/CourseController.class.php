<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2017/2/10
 * Time: 9:03
 */

namespace Admin\Controller;


use Think\Controller;

class CourseController extends Controller
{
    public function index(){
        $allCourse = M('course')->select();
        $yaanCourse = M('course')->where(array('campus'=>'雅安'))->select();
        $chengduCourse = M('course')->where(array('campus'=>'成都'))->select();
        $dujiangyanCourse = M('course')->where(array('campus'=>'都江堰'))->select();
        $this->assign('allCourse',$allCourse);
        $this->assign('yaanCourse',$yaanCourse);
        $this->assign('chengduCourse',$chengduCourse);
        $this->assign('dujiangyanCourse',$dujiangyanCourse);
        $this->display();
    }
}