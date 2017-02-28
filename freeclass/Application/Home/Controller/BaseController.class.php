<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/11/7
 * Time: 18:55
 */

namespace Home\Controller;


use Think\Controller;

class BaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if(!$_SESSION['user']){
            $this->error('对不起，您没有操作权限，请登录后再操作',U('Index/index'));
        }
        $isornot = M('user')->where(array('id'=>decrypt($_SESSION['user'])))->getField('isornot');
        if($isornot == 0){
            $this->error('对不起，您已经被禁用，请联系管理员');
        }

    }
}