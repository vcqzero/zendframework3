<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\UserManager;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class UserHelper extends AbstractHelper
{
    private $UserManager;
    
    public function __construct(
        UserManager $UserManager
        ) 
    {
        $this->UserManager   = $UserManager;
    }
    
    /**
    * 
    * 
    * @param  
    * @return array       
    */
    public function findAll()
    {
        $Users = $this->UserManager->MyTableGateway->select();
        return $Users;
    }
}
