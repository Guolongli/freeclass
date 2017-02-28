<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/12/5
 * Time: 8:58
 */

namespace Home\Controller;


class GroupController extends BaseController
{
    private $groups;//存储group的model
    private $user;

    public function __construct()
    {
        parent::__construct();
        $this->groups = D('Groups');
        $this->user = decrypt($_SESSION['user']);

    }
    public function __destruct()
    {
        // TODO: Implement __destruct() method.
    }

    /**
     * 显示用户的个人群组
     */
    public function MyGroup(){
        $Groups = D('user')->Relation('groups')->find(decrypt($_SESSION['user']));
        $Status = M('user_group')->where(array('userid'=>decrypt($_SESSION['user'])))->field('status')->select();
        $this->assign('groups',$Groups['groups']);
        $this->assign('status',$Status);
        $action = I('get.action');
        $CheckStatus = M('user_group')->where(array('userid'=>$this->user,'groupid'=>I('get.id')))->getField('status');
        if($action == 'Look'||$action == 'Quit'||$action == 'DeleteMember'||$action == 'AddAdmin'||$action == 'DeleteAdmin'||$action == 'DeleteGroup'){
            if(!empty($CheckStatus)){
                if($action == 'Look'){
                    $members = $this->Look();
                    $this->ajaxReturn($members['user']);
                }
                if($action == 'Quit'){
                    $this->Quit();
                    $this->success('退出成功',U('Group/MyGroup'));
                    exit();
                }
                if($CheckStatus>0){
                    if($action == 'DeleteMember'){
                        S('groupid',I('get.id'));
                        $members = $this->DeleteMember();
                        $this->ajaxReturn($members);
                    }
                }else{
                    $this->error('你等级不够！');
                }
                if($CheckStatus== 2){
                    if($action == 'AddAdmin'){
                        S('groupid',I('get.id'));
                        $members = $this->AddAdmin();
                        $this->ajaxReturn($members);
                    }
                    if($action == 'DeleteAdmin'){
                        S('groupid',I('get.id'));
                        $members = $this->DeleteAdmin();
                        $this->ajaxReturn($members);
                    }
                    if($action == 'DeleteGroup'){
                        $this->DeleteGroup();
                        $this->success('解散成功',U('Group/MyGroup'));
                        exit();
                    }
                }else{
                    $this->error('你没有该操作权限！');
                }
            }else{
                $this->error('你不能操作该群！');
            }
        }



        $this->display();
    }

    /**
     * 新建群组
     */
    public function AddGroup(){
        $groupname = I('post.groupname');
        /**
         * 以下用于ajax验证群组名是否已经存在
         */
        if(I('get.action')=='check') {
            if ($this->groups->where("name='$groupname'")->getField('id')) {
                $this->ajaxReturn(0);
            } else {
                $this->ajaxReturn(1);
            }
        }

        /**
         * 以下用于表单提交，将数据写入数据库
         */
        if(I('get.action')=='submit'){
            $data = array(
                'name'=>$groupname,
                'word'=>I('post.word')
            );
            if($this->groups->create($data)){
                $this->groups->add($data);
                $GroupId = $this->groups->where("name='$groupname'")->getField('id');
                $UGData = array(
                    'userid'=>decrypt($_SESSION['user']),
                    'groupid'=>$GroupId,
                    'status'=>'2'
                );
                M('user_group')->add($UGData);
                $recordData = array(
                    'user' => decrypt($_SESSION['user']),
                    'operation' => "新建'$groupname'群组",
                    'time' => GetTime()
                );
                M('record')->add($recordData);
                $this->success('恭喜您，新建成功');
                exit;
            }else{
                $this->error($this->groups->getError());
            }
        }
        $this->display();
    }

    /**
     * 加入群组
     */
    public function JoinGroup(){
        $groupname = I('post.groupname');
        /**
         * 以下用于ajax进行模糊查询
         */
        if(I('get.action')=='search') {
            $where['name'] = array('like',"%$groupname%");
            $data = D('Groups')->where($where)->field('name')->select();
            $this->ajaxReturn($data);
        }

        /**
         * 以下用于查询用户之前是否加入过该群组，群组口令是否正确，验证成功后，让用户加入群组
         */
        if(I('get.action')=='submit'){
            $GroupId = $this->groups->where("name='$groupname'")->getField('id');
            if(!$GroupId){
                $this->error('该群组不存在，请重新输入！');
            }
            $where=array(
                'userid'=>decrypt($_SESSION['user']),
                'groupid'=>$GroupId
            );
            if(M('user_group')->where($where)->select()){
                $this->error('你已经加入过该群组，不可重复加入！');
            }else{
                $PostWord = I('post.word');
                $DbWord = D('Groups')->where("name='$groupname'")->getField('word');
                if($PostWord == $DbWord){
                    $UGData = array(
                        'userid'=>decrypt($_SESSION['user']),
                        'groupid'=>$GroupId
                    );
                    M('user_group')->add($UGData);
                    $recordData = array(
                        'user' => decrypt($_SESSION['user']),
                        'operation' => "加入'$groupname'群组",
                        'time' => GetTime()
                    );
                    M('record')->add($recordData);
                    $this->success('恭喜您，加群成功');
                    exit;
                }else{
                    $this->error('口令错误，请重新输入');
                }
            }
        }
        $this->display();
    }

    /**
     * 查看群成员
     */
    private function Look(){
        $id=I('get.id');
        return $this->groups->Relation('user')->find($id);
    }

    /**
     * 退出群组
     */
    private function Quit(){
        $where = array(
            'groupid'=>I('get.id'),
            'userid'=>decrypt($_SESSION['user'])
        );
        M('user_group')->where($where)->delete();
        $groupname = D('Groups')->where(array('id'=>I('get.id')))->getField('name');
        $recordData = array(
            'user' => decrypt($_SESSION['user']),
            'operation' => "退出'$groupname'群组",
            'time' => GetTime()
        );
        M('record')->add($recordData);
    }

    /**
     * 管理员删除成员
     */
    public function DeleteMember(){
        $groupid = S('groupid');
        if(I('get.delete')=='member'){
            $userid = I('post.user');
            $where = array(
                'userid'=>array('in',$userid),
                'groupid'=>$groupid
            );
            M('user_group')->where($where)->delete();
            $groupname = D('Groups')->where(array('id'=>$groupid))->getField('name');
            $recordData = array(
                'user' => decrypt($_SESSION['user']),
                'operation' => "删除'$groupname'群组的成员:".implode(',',$userid),
                'time' => GetTime()
            );
            M('record')->add($recordData);
            $this->success('删除成功！');
            die;
        }
        $where=array(
            'groupid'=>$groupid,
            'userid' =>decrypt($_SESSION['user'])
        );
        $User_Status = M('user_group')->where($where)->getField('status');
        $GroupMembers = M('user_group')->table('user_group a,user b')->where("a.groupid ='$groupid' and a.status<'$User_Status' and a.userid = b.id")->select();
        return $GroupMembers;
    }

    /**
     * 群主添加管理员
     */
    public function AddAdmin(){
        $groupid = S('groupid');
        if(I('get.add')=='admin'){
            $userid = I('post.user');
            $where = array(
                'userid'=>array('in',$userid),
                'groupid'=>$groupid
            );
            M('user_group')->where($where)->save(array('status'=>1));
            $groupname = D('Groups')->where(array('id'=>$groupid))->getField('name');
            $recordData = array(
                'user' => decrypt($_SESSION['user']),
                'operation' => "任命'$groupname'群组的管理员:".implode(',',$userid),
                'time' => GetTime()
            );
            M('record')->add($recordData);
            $this->success('添加成功！');
            die;
        }
        $GroupMembers = M('user_group')->table('user_group a,user b')->where("a.groupid ='$groupid' and a.status=0 and a.userid = b.id")->select();
        return $GroupMembers;
    }
    /**
     * 群主删除管理员
     */
    public function DeleteAdmin(){
        $groupid = S('groupid');
        if(I('get.delete')=='admin'){
            $userid = I('post.user');
            $where = array(
                'userid'=>array('in',$userid),
                'groupid'=>$groupid
            );
            M('user_group')->where($where)->save(array('status'=>0));
            $groupname = D('Groups')->where(array('id'=>$groupid))->getField('name');
            $recordData = array(
                'user' => decrypt($_SESSION['user']),
                'operation' => "删除'$groupname'群组的管理员:".implode(',',$userid),
                'time' => GetTime()
            );
            M('record')->add($recordData);
            $this->success('删除成功！');
            die;
        }
        $GroupMembers = M('user_group')->table('user_group a,user b')->where("a.groupid ='$groupid' and a.status=1 and a.userid = b.id")->select();
        return $GroupMembers;
    }
    /**
     * 群主解散群组
     */
    private function DeleteGroup(){
        $id=I('get.id');
        $groupname = D('Groups')->where(array('id'=>$id))->getField('name');
        $recordData = array(
            'user' => decrypt($_SESSION['user']),
            'operation' => "解散'$groupname'群组",
            'time' => GetTime()
        );
        M('record')->add($recordData);
        D('Groups')->where("id='$id'")->delete();
        M('user_group')->where("groupid='$id'")->delete();
    }

}