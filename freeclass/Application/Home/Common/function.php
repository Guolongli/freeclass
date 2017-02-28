<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/11/24
 * Time: 11:39
 */
/**
 * @return string
 * 通过用户字符串长度来判断是老师（长度小于8）登陆还是学生登录（长度大于等于8）
 */
function GetSOrT(){
    if($_SESSION['user']){
        $user = decrypt($_SESSION['user']);
    }else{
        $user = I('post.user');
    }
    if(strlen($user)<8){
        return 'T';
    }else{
        return 'S';
    }
}

