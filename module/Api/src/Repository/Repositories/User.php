<?php
namespace Api\Repository\Repositories;

class User
{
    //定义表名
    const TABLE_NAME            = 'users';
    //定义表字段名称
    //表示字段的常量名称不要更改
    const FILED_ID              = 'id';
    const FILED_USERNAME        = 'username';
    const FILED_PASSWORD        = 'password';
    const FILED_REALNAME        = 'realname';
    const FILED_TEL             = 'tel';
    const FILED_STATUS          = 'status';
    const FILED_WORKYARD_ID     = 'workyard_id';
    const FILED_ROLE            = 'role';
}
