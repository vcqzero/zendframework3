<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Api\Service\UserManager;
/**
 * @name 控制台管理
 * @desc 管理控制台页面
 */
class IndexController extends AbstractActionController
{
    private $UserManager;
    
    /**
    * 主页
    * 
    * @desc 显示主页
    * @param  
    * @return        
    */
    public function indexAction()
    {
        return new ViewModel();
    }
    
    /**
    * 主页
    * 
    * @desc 显示主页2
    * @param  
    * @return        
    */
    public function index2Action()
    {
        return new ViewModel();
    }
}
