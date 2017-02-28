<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2017/2/25
 * Time: 9:49
 */

namespace Admin\Controller;


class GroupController extends BaseController
{
    public function index(){
        $groups = M('groups')->select();
        $this->assign('groups',$groups);
        $this->display();
    }

    public function Mygroup(){
        $groupName = I('get.id');
        if(I('get.action') == 'DeleteGroup'){
            M('groups')->where(array('id'=>$groupName))->delete();
            M('user_group')->where(array('groupid'=>$groupName))->delete();
            $this->success('解散成功');
        }
        if(I('get.action') == 'Look'){
            $groupMember = D('Groups')->Relation('user')->find($groupName);
            $this->ajaxReturn($groupMember['user']);
        }
    }
}