<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/7/19
 * Time: 9:30
 */

namespace Home\Controller;
use Think\Controller;
vendor('PHPEmail.email');
class SentEmailController extends Controller
{
    private $smtpusermail;    //SMTP服务器的用户邮箱
    private $smtpemailto;      //邮件接收者
    private $smtpuser;          //SMTP服务器的用户帐号
    private $smtppass;          //SMTP服务器的第三方授权码
    private $mailtitle;         //邮件标题
    private $mailcontent;       //邮件内容

    /**
     * SentEmailController constructor.
     * @param $sum  //SMTP服务器的用户邮箱
     * @param $smt  /邮件接收者
     * @param $su   //SMTP服务器的用户帐号
     * @param $spwd //SMTP服务器的用户密码,不是邮箱密码，是smtp第三方授权码
     * @param $title    //邮件标题
     * @param $content  //邮件内容
     */
    public function __construct($smt,$title,$content)
    {
        $this->smtpusermail='freeclass@haizhilongli.com';
        $this->smtpuser='freeclass@haizhilongli.com';
        $this->smtppass='FreeClass101316';
        $this->smtpemailto=$smt;
        $this->mailtitle=$title;
        $this->mailcontent=$content;
    }

    public function send(){
       $smtpserver = "smtp.exmail.qq.com";//SMTP服务器
       $smtpserverport =25;//SMTP服务器端口
       $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
       $smtp = new \smtp($smtpserver,$smtpserverport,true,$this->smtpuser,$this->smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
       $smtp->debug = true;//是否显示发送的调试信息
        $state = $smtp->sendmail($this->smtpemailto, $this->smtpusermail, $this->mailtitle, $this->mailcontent, $mailtype);
        if($state==""){
            echo "对不起，邮件发送失败！请检查邮箱填写是否有误。";
            exit();
        }
   }
}