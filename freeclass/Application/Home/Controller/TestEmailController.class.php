<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/12/28
 * Time: 13:19
 */

namespace Home\Controller;

use Think\Controller;
class TestEmailController extends Controller
{
    public function sent(){
        $user = D('user')->select();
        for($i=0,$j=0;$i<count($user);$i++){
            if(isset($user[$i]['email'])){
                $receiver[$j] = $user[$i]['email'];
                $j++;
            }
        }
        $numEmail = M('setting') -> where(array('key'=>'numemail'))->getField('value');
        $emailList = emailListController::singleton();
        $emailList->setMessage(I('post.content'),$receiver);
        $emailList->send($numEmail);
        M('setting') -> where(array('key'=>'numemail'))->save(array('value'=>$numEmail+count($receiver)));
    }
}