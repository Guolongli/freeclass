<?php
/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/12/5
 * Time: 10:07
 */

namespace Admin\Model;


use Think\Model\RelationModel;

class GroupsModel extends RelationModel
{
    protected $_link=array(
        'user'=>array(
            'mapping_type'=>self::MANY_TO_MANY,

            'relation_table'=>'user_group', //relation_table是中间约束和关联的表名

            'foreign_key'=>'groupid', //此表类的对应中间表的外键

            'relation_foreign_key'=>'userid' //关联表的对应中间表的外键
        )
    );

    protected $_validate=array(
        array('name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
        array('word','require','口令不能为空！')
    );

}