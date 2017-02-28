<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/11/24
 * Time: 11:19
 */
/**
 * @return string
 * 获取当前学期
 */
function GetSemester(){
    $year=date('Y');
    $month=date('m');
    if($month<8&&$month>1){
        $year2=$year-1;
        return $year2.'-'.$year.'-2';
    }else{
        $year2=$year+1;
        return $year.'-'.$year2.'-1';
    }
}

/**
 * 获取当前时间
 */
function GetTime(){
    date_default_timezone_set('prc');
    return date('Y-m-d h:i:s',time());
}

/**
 * @param $data
 * @param $key
 * @return string
 * 加密
 */
function encrypt($data)
{
    $key    =   md5('freeclass');
    $x      =   0;
    $len    =   strlen($data);
    $l      =   strlen($key);
    for ($i = 0; $i < $len; $i++)
    {
        if ($x == $l)
        {
            $x = 0;
        }
        $char .=$key{$x};
        $x++;
    }
    for ($i = 0; $i < $len; $i++)
    {
        $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
    }
    return base64_encode($str);
}

/**
 * @param $data
 * @param $key
 * @return string
 * 解密
 */
function decrypt($data)
{
    $key = md5('freeclass');
    $x = 0;
    $data = base64_decode($data);
    $len = strlen($data);
    $l = strlen($key);
    for ($i = 0; $i < $len; $i++)
    {
        if ($x == $l)
        {
            $x = 0;
        }
        $char .= substr($key, $x, 1);
        $x++;
    }
    for ($i = 0; $i < $len; $i++)
    {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1)))
        {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        }
        else
        {
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return $str;
}