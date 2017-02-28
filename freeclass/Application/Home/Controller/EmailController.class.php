<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/12/26
 * Time: 16:10
 */

namespace Home\Controller;


use Home\Controller\SentEmailController;
use Think\Controller;

class EmailController extends Controller
{
    private $sentEmail;  //发送邮件对象

    /**
     * 发送激活邮件
     */
    public function jiHuo(){
        $email = I('post.email');
        $content ="单击以下链接激活邮箱<br>"."http://".$_SERVER['SERVER_NAME'].__ROOT__."/index.php/Home/Email/yanZheng/email/".encrypt($email)."/user/".$_SESSION['user'].".html";
        $this->sentEmail = new SentEmailController($email,'邮箱激活',$content);
        $this->sentEmail->send();
        echo "<script>alert('激活邮件已发送，请注意查收!');history.back(-1);</script>";
    }

    /**
     * 验证并激活邮箱
     */
    public function yanZheng(){
        $user = decrypt(I('get.user'));
        $email = decrypt(I('get.email'));
        $checkEmail = D('user')->where(array('id'=>$user))->getField('email');
        if(empty($checkEmail)){
            $data = array('email'=>$email);
            D('user')->where(array('id'=>$user))->save($data);
            $recordData = array(
                'user' => decrypt(I('get.user')),
                'operation' => '激活邮箱',
                'time' => GetTime()
            );
            M('record')->add($recordData);
            echo"<script>alert('激活成功');location.href = 'http://".$_SERVER['SERVER_NAME'].__ROOT__."';</script>";
        }else{
            $this->error('您邮箱已经激活，请勿重复点击！');
        }
    }

    /**
     * 群发邮件
     */
    function sentMessage(){
//        $groupid = $_POST['groupid'];
        $title = $_POST['title'];
        $content = $_POST['content'];
//        $groupMessage = D('Groups')->Relation('user')->find($groupid);
//        for($i=0;$i<count($groupMessage['user']);$i++){
//            $receiver[$i] = $groupMessage['user'][$i]['email'];
//        }
        $user = D('user')->select();
        for($i=0,$j=0;$i<count($user);$i++){
            if(isset($user[$i]['email'])){
                $receiver[$j] = $user[$i]['email'];
                $j++;
            }
        }
        $this->success('发送成功');
        for($i=0;$i<count($receiver);$i++){
            echo 'freeclass'.($i%5+1)."<br>";
            $this->sentEmail = new SentEmailController($receiver[$i],$title,$content."<p>系统消息，请勿回复</p>");
            $this->sentEmail->send();
            sleep(10);
        }
    }
}