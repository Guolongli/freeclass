<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2017/2/3
 * Time: 10:00
 */

namespace Admin\Controller;


class RecordController extends BaseController
{
    public function index(){
        $record = M('record')->select();
        for($i=0;$i<count($record);$i++){
            $name[$i]=M('user')->where(array('id'=>$record[$i]['user']))->getField('name');
        }
        $this->assign('record',$record);
        $this->assign('name',$name);
        $this->display();
    }
}