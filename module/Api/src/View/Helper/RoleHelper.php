<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\RoleManager;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class RoleHelper extends AbstractHelper
{
    private $RoleManager;
    
    public function __construct(
        RoleManager $RoleManager
        ) 
    {
        $this->RoleManager   = $RoleManager;
    }
    
    /**
    * 根据角色英文名称输出角色中文名称
    * 
    * @param string $role 
    * @return string $role       
    */
    public function renderRole($role)
    {
        switch ($role) {
            case RoleManager::ROLE_GUEST :
                $desc = '游客';
                break;
            case RoleManager::ROLE_SUPER_USER :
                $desc = '超级管理员';
                break;
            default:
                $desc = '-';
        }
        
        return $desc;
    }
    
}
