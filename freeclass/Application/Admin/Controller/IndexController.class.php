<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {

    /**
     * 跳转到登录界面
     */
    public function index(){
        $this->display('Login/index');
    }

    /**
     * 验证账号密码
     */
    public function Load()
    {
        $user = I('post.user');
        $pwd = md5(I('post.pwd'));
        $checkPwd = M('admin')->where(array('name'=>$user))->getField('password');
        if($pwd == $checkPwd){
            $_SESSION['user'] = encrypt($user);
            $this->success('恭喜你，登陆成功',U('Home'));
        }else{
            $this->error('账号或密码错误，请核对后重新输入');
        }
    }

    /**
     * 显示主页
     */
    public function Home(){
        new BaseController();
        $userCount = M('user')->count();
        $yaanCount = M('user')->where(array('campus'=>'雅安'))->count();
        $chengduCount = M('user')->where(array('campus'=>'成都'))->count();
        $dujiangyanCount = M('user')->where(array('campus'=>'都江堰'))->count();
        $groupCount = M('groups')->count();
        $courseCount = M('course')->count();
        $recordCount = M('record')->count();
        $this->assign('userCount',$userCount);
        $this->assign('groupCount',$groupCount);
        $this->assign('courseCount',$courseCount);
        $this->assign('recordCount',$recordCount);
        $this->assign('yaanCount',$yaanCount);
        $this->assign('chengduCount',$chengduCount);
        $this->assign('dujiangyanCount',$dujiangyanCount);
        $this->display();
    }

    /**
     * 注销登录
     */
    public function logout(){
        unset($_SESSION['user']);
        $this->success('注销成功',U('index'));
    }
}