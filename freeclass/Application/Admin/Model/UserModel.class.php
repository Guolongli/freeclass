<?php
namespace Admin\Model;
use Think\Model\RelationModel;

/**
 * Created by PhpStorm.
 * User: 龙鲤
 * Date: 2016/11/25
 * Time: 12:40
 */
class UserModel extends RelationModel
{
    protected $_link=array(
        'course'=>array(
            'mapping_type'=>self::MANY_TO_MANY,

            'relation_table'=>'user_course', //relation_table是中间约束和关联的表名

            'foreign_key'=>'userid', //此表类的对应中间表的外键

            'relation_foreign_key'=>'courseid' //关联表的对应中间表的外键
        ),
        'groups'=>array(
            'mapping_type'=>self::MANY_TO_MANY,

            'relation_table'=>'user_group', //relation_table是中间约束和关联的表名

            'foreign_key'=>'userid', //此表类的对应中间表的外键

            'relation_foreign_key'=>'groupid' //关联表的对应中间表的外键
        )
    );
}