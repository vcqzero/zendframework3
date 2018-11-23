<?php
/**
* 关于用户表的增删改查
* 
*/
namespace Api\Service;

class RoleManager
{
    const ROLE_GUEST = 'GUEST';
    const ROLE_SUPER_USER = 'SUPER_USER';
    
    /**
    * 获取所有角色，不区分module
    * 如果想获取某module中的角色，请另写方法
    * 
    * @return array       
    */
    public function getRoles()
    {
        return [
            self::ROLE_GUEST,
            self::ROLE_SUPER_USER
        ];
    }
}


