<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2017/2/2
 * Time: 11:09
 */

namespace Admin\Controller;


use Think\Controller;

class UserController extends BaseController
{
    private $course;

    public function index(){
        $user=M('user')->select();
        $this->assign('user',$user);
        $this->display();
    }

    /**
     * 启用/禁用 用户
     */
    public function operation(){
        $id = I('get.id');
        $operation = I('get.operation')==1?0:1;
        M('user')->where(array('id'=>$id))->save(array('isornot'=>$operation));
        $this->success('更改成功',U('index'));
    }

    /**
     * 查看某人课表
     */
    public function course(){
        $id = I('get.id');
        $course=D('User')->Relation('course')->find($id);
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
        $this->display();
    }

    /**
     * 查看某人的操作日志
     */
    public function record(){
        $id = I('get.id');
        $record = M('record')->where(array('user'=>$id))->select();
        for($i=0;$i<count($record);$i++){
            $name[$i] = D('user')->where(array('id'=>$id))->getField('name');
        }
        $this->assign('record',$record);
        $this->assign('name',$name);
        $this->display('Record/index');
    }
}