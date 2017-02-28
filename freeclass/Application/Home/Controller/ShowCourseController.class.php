<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/11/26
 * Time: 12:04
 */

namespace Home\Controller;

use Think\Controller;
class ShowCourseController extends Controller
{
    private $user; //用户名
    private $course; //用户课程

    public function __construct()
    {
        parent::__construct();
        if($_SESSION['user']){
            $this->user = decrypt($_SESSION['user']);
        }else{
            $this->user = I('post.user');
        }
    }
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

    public function SelectCourse(){
        $course=D('user')->Relation('course')->find($this->user);
        $k =array();
        for($i=0;$i<count($course['course']);$i++){
            $time=preg_split('/,/',$course['course'][$i]['time']);
            $classroom = preg_split('/<br>/',$course['course'][$i]['classroom']);
            for($j=0;$j<count($time);$j++){
                if(!$this->course[$time[$j]][0]){
                    $k[$time[$j]]=0;
                    $this->course[$time[$j]][$k[$time[$j]]] = $course['course'][$i]['coursename'].'<br>'.$course['course'][$i]['teacher'].'<br>'.$classroom[$j].'<br>'.$course['course'][$i]['weekmessage'];
                }else{
                    $k[$time[$j]]++;
                    $this->course[$time[$j]][$k[$time[$j]]] =$course['course'][$i]['coursename'].'<br>'.$course['course'][$i]['teacher'].'<br>'.$classroom[$j].'<br>'.$course['course'][$i]['weekmessage'];
                }
            }
        }
        $this->assign('course',$this->course);
    }
}