<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/12/28
 * Time: 12:38
 */

namespace Home\Controller;

use Think\Controller;

vendor('PHPEmail.email');

class emailListController
{
    private static $_instance;           //保存实例在此属性中
    private $receiver;       //Array，用来保存接收者的数组
    private $mailcontent;       //邮件内容

    //构造、克隆函数声明为private,防止直接创建或者复制对象
    private function __construct(){
    }
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * @return mixed 返回类本身
     * 单例方法
     */
    public static function singleton()
    {
        if(!isset(self::$_instance))
        {
            $c=__CLASS__;
            self::$_instance=new $c;
        }
        return self::$_instance;
    }

    /**
     * @param $content 邮件内容
     * @param $receiverArray 邮件接收者
     */
    public function setMessage($content,$receiverArray){
        $this->mailcontent = $content;
        $this->receiver = $receiverArray;
    }

    /**
     * @param $numEmail 第几次发送
     * 发送邮件
     */
    public function send($numEmail){
        $smtpserver = "smtp.exmail.qq.com";//SMTP服务器
        $smtpserverport =25;//SMTP服务器端口
        $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
        for($i=0;$i<count($this->receiver);$i++){
            $No = $numEmail%50;
            $num = $No<10?'0'.$No:$No;
            $smtpuser = "freeclass".$No."@haizhilongli.com";
            $smtppass = "FcNo00".$num;
            $smtp = new \smtp($smtpserver,$smtpserverport,true,$smtpuser,$smtppass);//这里面的一个true是表示使用身份验证,否则不使用身份验证.
            $smtp->debug = true;//是否显示发送的调试信息
            $state = $smtp->sendmail($this->receiver[$i], $smtpuser, '空课统计通知', $this->mailcontent, $mailtype);
            if($state==""){
                echo "对不起，邮件发送失败！请检查邮箱填写是否有误。";
                echo "<a href='index.html'>点此返回</a>";
                exit();
            }
            $numEmail++;
        }
    }
}