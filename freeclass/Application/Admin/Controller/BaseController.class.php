<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/11/22
 * Time: 8:57
 */

namespace Admin\Controller;


use Think\Controller;

class BaseController extends Controller
{
    protected $cookie;    //用来存放cookie进行登录验证

    public function __construct()
    {
        parent::__construct();
        $status = M('admin')->where(array('name'=>decrypt($_SESSION['user'])))->getField('status');
        if($status==0||empty($status)){
            $this->error('对不起，你没有操作权限',U('Index/index'));
        }
        //检测学期更换
        $checkSemeseter = new SemesterController();
        $checkSemeseter->change();
    }

    public function setCookie($cookie){
        $this->cookie=$cookie;
    }

}