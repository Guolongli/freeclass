<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/11/24
 * Time: 9:54
 */

namespace Home\Controller;


use Think\Controller;

class LoginController extends Controller
{
    private $lb;	//类型
    public $load;   //登录页面

    function __construct()
    {
        parent::__construct();
        $this->lb = GetSOrT();
    }
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
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
    public function Load(){
        $url="http://jiaowu.sicau.edu.cn/web/web/web/index.asp";
        $cookie_path=dirname(__RUNTIME__)."/cookie/".$_POST['user']."login.cookie";
        $d=$this->get_dcode2($this->get_cookie($url,$cookie_path));
        $p=$this->password(I('post.pwd'),$d);
        $p=urlencode($p);
        $data="user=".I('post.user')."&pwd=".$p."&lb=".$this->lb."&submit=";
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
    public function CheckLoad($result){
        if(strpos($result,"说明：此评教为无记名评教,若评价为\"不满意\"，建议写明原因")){
            $this->error("教务处需要评教，请前往教务处评教后再登录");
        }
        if ($this->lb=="S"){
            $ret=strpos($result, "Location: ../../xuesheng/bangong/main/index1.asp");
        }elseif ($this->lb=="T"){
            $ret=strpos($result, "Location: main/note.asp");
        }
        return $ret;
    }

    //获取学号对应的姓名
    public function GetName($temp){
        $data=array();
        preg_match_all('/<td [\w\W]*?>([\w\W]*?)<\/td>/',$temp,$data);
        if($this->lb == 'S'){
            $name=substr($data[1][4], 0,strpos($data[1][4], "("));
        }else{
            $name=$data[1][1];
        }
        return $name;
    }
}