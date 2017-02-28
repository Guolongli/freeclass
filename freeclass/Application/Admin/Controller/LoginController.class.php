<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {

    private $user;
    private $pwd;

    public function __construct($user,$pwd)
    {
        parent::__construct();
        $this->user = $user;
        $this->pwd = $pwd;
    }

    //获取教务处登录界面的cookie
    private function get_cookie($url,$cookie_path){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_path);
        $ret=curl_exec($ch);
        curl_close($ch);
        return $ret;
    }

    //得到decode2用于模拟加密
    private function get_dcode2($ret){
        $start=strpos($ret,'dcode2=');
        $dcode2=substr($ret,$start+7,10);
        return $dcode2;
    }

    //模拟js的fromCharCode函数用于加密
    private function fromCharCode($codes) {
        if (is_scalar($codes)) $codes= func_get_args();
        $str= '';
        foreach ($codes as $code) $str.= chr($code);
        return $str;
    }

    //模拟js的charCodeAt函数进行编码转换
    private function charCodeAt($str, $index){
        $char = mb_substr($str, $index, 1, 'UTF-8');
        if (mb_check_encoding($char, 'UTF-8'))
        {
            $ret = mb_convert_encoding($char, 'UTF-32BE', 'UTF-8');
            return hexdec(bin2hex($ret));
        }
        else
        {
            return null;
        }
    }

    //对密码进行模拟加密
    private function password($pass,$dcode2){
        $dcode=$pass;
        $dcode1="";
        $dcode2="".$dcode2*137;
        $dcodelen=strlen($dcode);
        for($i=1;$i<=$dcodelen;$i++){
            $tmpstr=substr($dcode,$i-1,1);
            $dcode1=$dcode1.$this->fromCharCode($this->charCodeAt($tmpstr,0)-$i-substr($dcode2,$i-1,1));
        }
        return $dcode1;
    }

    //进行模拟登录
    private function Load(){
        $url="http://jiaowu.sicau.edu.cn/web/web/web/index.asp";
        $cookie_path=dirname(__RUNTIME__)."/login.cookie";
        $d=$this->get_dcode2($this->get_cookie($url,$cookie_path));
        $p=$this->password($this->pwd,$d);
        $p=urlencode($p);
        $data="user=".$this->user."&pwd=".$p."&lb=S&submit=";
        $url="http://jiaowu.sicau.edu.cn/jiaoshi/bangong/check.asp";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_REFERER, $url);
        curl_setopt($ch,CURLOPT_HEADER,1);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_COOKIEFILE, $cookie_path);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION, 1);
        $ret=curl_exec($ch);
        curl_close($ch);
        $ret=iconv('gbk', 'utf-8', $ret);
        return $ret;
    }

    //用于登录验证
    public function CheckLoad(){
        $result=$this->Load();
        if(strpos($result,"说明：此评教为无记名评教,若评价为\"不满意\"，建议写明原因")){
            $this->error("教务处需要评教，请前往教务处评教后再登录");
        }
        $ret=strpos($result, "Location: ../../xuesheng/bangong/main/index1.asp");
        if(!$ret){
            echo $result;
        }
    }
}
