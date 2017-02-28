<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/11/22
 * Time: 9:04
 */

namespace Admin\Controller;


use Admin\Controller\BaseController;

class GetCourseMessageController extends BaseController
{
    private $courseMessage;//总课程信息
    private $semester;  //学期
    private $courseName;//课程名
    private $courseWeek;//上课周次（那几周要上课）
    private $courseTime;//每周上课时间（星期几，第几节）
    private $courseBianHao; //课程编号
    private $teacherName;//对应教学班老师的姓名
    private $classroom; //上课教室
    private $kai;  //存放访问http://jiaowu.sicau.edu.cn/xuesheng/gongxuan/gongxuan/kaike.asp?kai=68359529621051966552235614756094所需的 kai参数

    public function __construct($user,$pwd)
    {
        parent::__construct();
        $this->Load($user,$pwd);
        $this->semester = GetSemester();   //获取学期
        $this->setKai();        //获取kai参数
    }

    public function __destruct()
    {
        // TODO: Implement __destruct() method.
        unset($this->semester);
        unset($this->courseMessage);
        unset($this->teacherName);
        unset($this->courseName);
        unset($this->courseWeek);
        unset($this->courseTime);
        unset($this->classroom);
        unset($this->kai);
    }

    private function Load($user,$pwd){
        $load = new LoginController($user,$pwd);
        $load->CheckLoad();
    }

    /**
     * @return mixed|string
     * 获取开课界面
     */
    private function visitKaiPage(){
        $url="http://jiaowu.sicau.edu.cn/xuesheng/gongxuan/gongxuan/kai.asp";
        $url2="http://jiaowu.sicau.edu.cn/xuesheng/gongxuan/gongxuan/xszhinan.asp?xueqi=".$this->semester;
        $cookie_path=dirname(__RUNTIME__)."/login.cookie";
        $cookie=dirname(__RUNTIME__)."/getCourse.cookie";
        $data="user=".$this->user."&pwd=".$_POST['pwd']."&lb=S&submit=";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_REFERER, $url2);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_COOKIEFILE, $cookie_path);
        curl_setopt($ch,CURLOPT_COOKIEJAR, $cookie);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $ret=curl_exec($ch);
        curl_close($ch);
        $ret=iconv('gbk', 'utf-8', $ret);
        return $ret;
    }

    /**
     * 获取kai参数
     */
    private function setKai()
    {
        $KaiPage = $this->visitKaiPage();
        preg_match_all("/<a href=\"([\w\W]*?)\">[\w\W]*?<\/a>/", $KaiPage, $kaiArray);
        $this->kai = $kaiArray[1][7];
    }

    /**
     * @return mixed|string
     * 访问课表页面
     */
    private function visitGetCourse(){
        $url="http://jiaowu.sicau.edu.cn//xuesheng/gongxuan/gongxuan/".$this->kai;
        $url2="http://jiaowu.sicau.edu.cn/xuesheng/gongxuan/gongxuan/kai.asp";
        $cookie_path=dirname(__RUNTIME__)."/getCourse.cookie";
        $data="user=".$this->user."&pwd=".$_POST['pwd']."&lb=S&submit=";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_REFERER, $url2);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_COOKIEFILE, $cookie_path);
        curl_setopt($ch,CURLOPT_COOKIEJAR, $cookie_path);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $ret=curl_exec($ch);
        curl_close($ch);
        $ret=iconv('gbk', 'utf-8', $ret);
        return $ret;
    }

    private function visitAllCoursePage($pageCount){
        $url="http://jiaowu.sicau.edu.cn//xuesheng/gongxuan/gongxuan/".$this->kai;
        $url2="http://jiaowu.sicau.edu.cn/xuesheng/gongxuan/gongxuan/kai.asp";
        $cookie_path=dirname(__RUNTIME__)."/getCourse.cookie";
        $data="selw=%C8%AB%B2%BF&ww_zd=%BF%CE%B3%CC&ww=&picha=yes&kl=&zh=&jsj=&ku=&o=%D3%E0%C8%DD+desc&id=0&y=".$pageCount."&xuangai=&act=&dizhi=%2Fxuesheng%2Fgongxuan%2Fgongxuan%2Fkaike.asp%3Fkai%3D66886436671852746359835215267376&w1=%D1%A7%C6%DA%3D%272016-2017-1%27++and+%C5%C5%BF%CE%C0%E0%B1%F0%3C%3E%27%BB%EC%B0%E0%27+and+%CA%C7%B7%F1%B3%B7%B0%E0%3C%3E%27%CA%C7%27+and+%CA%C7%B7%F1%B7%A2%B2%BC%3D%27%CA%C7%27+and+%D0%A3%C7%F8%3D%27%D1%C5%B0%B2%27&w2=&sw1=&p=20&twid=1000&wid=100%2C100%2C150%2C80%2C80%2C80%2C120%2C80%2C50%2C50%2C50%2C50%2C50%2C100%2C50%2C50%2C30%2C100%2C100%2C50%2C50%2C80%2C70&vrul=y%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy%2Cy&m=&rul=n%2Cn%2Cn%2Cn%2Cn%2Cn%2Cn%2Cn%2Cn%2Cn%2Cn%2Cn%2Cn%2Cn%2C%CA%FD%2C%CA%FD%2C%CE%C4%2Cn%2Cn%2Cn%2C%CE%C4%2C%CE%C4%2C%CE%C4&h=%BF%CE%B3%CC%BB%E3%D7%DC%BF%AA%BF%CE%C4%BF%C2%BC&rig=%CE%DE&bh=81345043972820799721089989951801";
        $ch=curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_REFERER, $url2);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_COOKIEFILE, $cookie_path);
        curl_setopt($ch,CURLOPT_COOKIEJAR, $cookie_path);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
        $ret=curl_exec($ch);
        curl_close($ch);
        $ret=iconv('gbk', 'utf-8', $ret);
        return $ret;

    }

    /**
     * 获取课表，并化简存入数据库
     */
    public function GetCourse(){
        preg_match_all('/<td [\w\W]*?>([\w\W]*?)<\/td>/',$this->visitGetCourse(),$this->courseMessage);
        preg_match("/\D*(\d+\.?\d?)\D*/",$this->courseMessage[1][4],$countPage);
        if($countPage[1]%20==0){
            $count = $countPage[1]/20+1;
        }else{
            $count = $countPage[1]/20+2;
        }
        $coursecount=0;
        for($j=1;$j<$count;$j++) {
            preg_match_all('/<td [\w\W]*?>([\w\W]*?)<\/td>/', $this->visitAllCoursePage($j), $this->courseMessage);
            for ($i = 0; $i < 20; $i++) {
                $coursecount++;
                $name = $i * 27 + 36;
                if (empty($this->courseMessage[1][$name])) {
                    return true;
                }
                $bianHao = $i*27 + 34;
                $week = $i * 27 + 41;
                $room = $i * 27 + 39;
                $time = $i * 27 + 40;
                $teacher = $i * 27 + 47;
                $campus = $i*27+54;
                $this->courseName = $this->courseMessage[1][$name];
                $this->courseBianHao = $this->courseMessage[1][$bianHao];
                $this->courseWeek = $this->simWeek($this->courseMessage[1][$week], $this->courseMessage[1][$time]);
                $this->classroom = $this->courseMessage[1][$room];
                $this->courseTime = $this->simCourse($this->courseMessage[1][$time]);
                $this->teacherName = $this->courseMessage[1][$teacher];
                $campus = $this->courseMessage[1][$campus];
                $CourseData = array(
                    'coursename' => $this->courseName,
                    'bianhao' => $this->courseBianHao,
                    'teacher'=>$this->teacherName,
                    'weekmessage'=>$this->courseMessage[1][$week].'周',
                    'week' => $this->courseWeek,
                    'time' => $this->courseTime,
                    'classroom' => $this->classroom,
                    'campus'=>$campus
                );
                D('course')->add($CourseData);
            }
        }

    }

    /**
     * @param $time
     * @return string
     * 化简时间，星期X第Y结课转化为(X-1)*7
     */
    private function simCourse($time){
        preg_match_all("/\D*(\d+\.?\d?)\D*/",$time,$result);
        for($i=0;$i<count($result[1]);$i+=2){
            if($result[1][$i+1]%2==0){
                $week[$i]=($result[1][$i]-1)*5+$result[1][$i+1]/2;
            }
        }
        return implode(',',$week);
    }

    /**
     * @param $_temp
     * @return int
     * 化简week
     */
    private function simWeek($_temp,$time){
        if(strpos($_temp,'-')&&!strpos($time,'(')&&!strpos($_temp,',')){
            $aa=(preg_split("/-/", $_temp));
            for ($i=0;$i<20;$i++){
                if($aa[0]<$i+2&&$aa[1]>$i){
                    $course_check1[$i]=1;
                }else {
                    $course_check1[$i]=0;
                }
            }
        }elseif (strpos($time,'(双)')){
            $aa=(preg_split("/-/", $_temp));
            for ($i=0;$i<20;$i++){
                if($aa[0]<$i+2&&$aa[1]>$i&&$i%2==1){
                    $course_check1[$i]=1;
                }else {
                    $course_check1[$i]=0;
                }
            }
        }elseif (strpos($time,'(单)')){
            $aa=(preg_split("/-/", $_temp));
            for ($i=0;$i<20;$i++){
                if($aa[0]<$i+2&&$aa[1]>$i&&$i%2==0){
                    $course_check1[$i]=1;
                }else {
                    $course_check1[$i]=0;
                }
            }
        }elseif (strpos($_temp,',')){
            $aa=(preg_split("/,/", $_temp));
            for($i=0;$i<20;$i++)
                $course_check1[$i]=0;
            foreach ($aa as $bb){
                if(strpos($bb,'-')){
                    $cc=preg_split("/-/", $bb);
                    for ($i=0;$i<20;$i++){
                        if($cc[0]<$i+2&&$cc[1]>$i){
                            $course_check1[$i]=1;
                        }else {
                            $course_check1[$i]=0;
                        }
                    }
                }else {
                    for ($i=0;$i<20;$i++){
                        if($course_check1[$i]==0){
                            if($bb==$i+1){
                                $course_check1[$i]=1;
                                break;
                            }else {
                                $course_check1[$i]=0;
                            }
                        }
                    }
                }
            }
        }
        $week = 0;
        for($i=0;$i<20;$i++){
            $week = $week+pow(2,$i)*$course_check1[$i];
        }
        return $week;
    }
}