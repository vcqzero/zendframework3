<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
* @desc 账户管理
*/
class WebsiteController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
    
//     public function emailAction()
//     {
//         return new ViewModel();
//     }
}
