<?php
namespace Home\Controller;
use Admin\Controller\GetCourseMessageController;
use Think\Controller;
class IndexController extends Controller {

    public $load;//用来存储登录信息
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }


    /**
     * 登录验证
     */
    public function index(){
        if($_SESSION['user']){
            $showCourse = new ShowCourseController();
            $showCourse->SelectCourse();
            $this->assign('UserStatus',GetSOrT());
            $this->display('Index/index');
        }else{
            if(IS_POST){
                $load = new LoginController();
                $this->load = $load->Load();
                if($load->CheckLoad($this->load)){
                    if(!$this->CheckUserIs()){
                        $getCourse = new GetUserMessageController();
                        $getCourse->GetCourse();
						if(file_exists('./cookie/'.$_POST['user'].'getCourse.cookie')){
							unlink('./cookie/'.$_POST['user'].'getCourse.cookie');
						}
                    }
                    $showCourse = new ShowCourseController();
                    $showCourse->SelectCourse();
                    $_SESSION['user'] =encrypt(I('post.user'));
                    $this->assign('UserStatus',GetSOrT());
                    $recordData = array(
                        'user' => I('post.user'),
                        'operation' => '登录',
                        'time' => GetTime()
                    );
                    M('record')->add($recordData);
                    $this->success('恭喜你，登录成功','Index/index');
                }else{
                    echo "<script>alert('账号或密码错误，请查证后重新登录');history.back();</script>";
                }
				if(file_exists('./cookie/'.$_POST['user'].'login.cookie')){
							unlink('./cookie/'.$_POST['user'].'login.cookie');
						}
            }else{
                $this->display('Login/index');
            }
        }
    }

    /**
     * @return bool
     * 判断用户是否已经插入课表
     */
    public function CheckUserIs(){
        $user = $_POST['user'];
        if(M('user_course')->where("userid='$user'")->getField('courseid')){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 显示登录界面
     */
    public function Login(){
        $this->display('Login/login');
    }

    /**
     * 检测用户是否已经激活邮箱
     */
    public function email(){
        $user = decrypt($_SESSION['user']);
        $email = D('user')->where(array('id'=>$user))->getField('email');
        if(empty($email)){
            return false;
        }else{
            return true;
        }
    }

    /**
     * 显示首页
     */
    public function Home(){
        $this->display('Login/index');
    }

    public function Temp(){
        $_SESSION['user'] = encrypt(I('post.user'));
        $showCourse = new ShowCourseController();
        $showCourse->SelectCourse();
        $this->assign('UserStatus',GetSOrT());
        $this->display('Index/index');
    }

    /**
     * 注销登录
     */
    public function Logout(){
        $recordData = array(
            'user' => decrypt($_SESSION['user']),
            'operation' => '注销登录',
            'time' => GetTime()
        );
        M('record')->add($recordData);
        unset($_SESSION['user']);
        $this->success('注销成功',U('Index/Home'));
    }
}