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
    
    public function getPaginator($page=1, $query =[], $countPerPage=null)
    {
        if (count($query)) {
            $query = $this->UserManager->FormFilter->filter($query);
        }
        $paginator = $this->UserManager->MyTableGateway->paginator($page, $query);
        if(!empty($countPerPage)) $paginator->setItemCountPerPage($countPerPage);
        return $paginator;
    }
    
    public function getStatus()
    {
        return [
           UserManager::STATUS_ENABLED => '正常', 
        ];
    }
    
    /**
    * 获取用户
    * 
    * @param  int $id
    * @return array $user        
    */
    public function getUser($id)
    {
        return $this->UserManager->MyTableGateway->selectOne($id);
    }
}
