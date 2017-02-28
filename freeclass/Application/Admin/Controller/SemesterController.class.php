<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2017/2/23
 * Time: 8:55
 */

namespace Admin\Controller;

use Think\Controller;

class SemesterController extends Controller
{

    private $nowSemester; //根据当前时间推算出的学期
    private $checkSemester; //数据库中存放的学期

    public function __construct()
    {
        parent::__construct();
        $this->nowSemester = GetSemester();
        $this->checkSemester = M('setting')->where(array('key'=>'semester'))->getField('value');
    }

    /**
     * 检测学期是否需要更换
     */
    public function change(){
        if($this->nowSemester!=$this->checkSemester){
            $this->operation();
            $this->importCourse();
        }
    }

    /**
     * 检测到需要更换学期后的操作
     */
    private function operation(){
        //清空课程数据表
        $sql = "TRUNCATE `course`";
        M()->execute($sql);
        //清空课程用户关联表
        $sql = "TRUNCATE `user_course`";
        M()->execute($sql);
        //清空操作日志表
        $sql = "TRUNCATE `record`";
        M()->execute($sql);
        //清空教师学生关联表
        $sql = "TRUNCATE `teacher_student`";
        M()->execute($sql);
        //更新系统设置表里的学期
        M('setting')->where(array('key'=>'semester'))->save(array('value'=>$this->nowSemester));
    }

    /**
     * 更新课表
     */
    private function importCourse(){
        //
        $yaanUser = M('setting')->where(array('key'=>'yaan_user'))->getField('value');
        $yaanPwd = M('setting')->where(array('key'=>'yaan_pwd'))->getField('value');
        $wenjiangUser = M('setting')->where(array('key'=>'wenjiang_user'))->getField('value');
        $wenjiangPwd = M('setting')->where(array('key'=>'wenjiang_pwd'))->getField('value');
        $dujiangyanUser = M('setting')->where(array('key'=>'dujiangyan_user'))->getField('value');
        $dujiangyanPwd = M('setting')->where(array('key'=>'dujiangyan_pwd'))->getField('value');
        $getYaanCourse = new GetCourseMessageController($yaanUser,$yaanPwd);
        $getYaanCourse ->GetCourse();
        $getWenjiangCourse = new GetCourseMessageController($wenjiangUser,$wenjiangPwd);
        $getWenjiangCourse ->GetCourse();
        $getDujiangyanCourse = new GetCourseMessageController($dujiangyanUser,$dujiangyanPwd);
        $getDujiangyanCourse ->GetCourse();
    }
}