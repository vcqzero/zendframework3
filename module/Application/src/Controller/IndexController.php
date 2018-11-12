<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
class IndexController extends AbstractActionController
{
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
}
