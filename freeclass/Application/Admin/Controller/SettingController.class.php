<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2017/2/23
 * Time: 12:41
 */

namespace Admin\Controller;


class SettingController extends BaseController
{
    public function index(){
        $settingArray = M('setting')->select();
        $this->assign('setting',$settingArray);
        $this->display();
    }

    public function change(){
        if(IS_POST){
            $key = array_keys($_POST);
            $value = $_POST[$key[0]];
            if(!$value){
                $this->error('未修改或者value不允许为空');
            }
            M('setting')->where(array('key'=>$key[0]))->save(array('value'=>$value));
            $this->success('修改成功');
        }
    }
}