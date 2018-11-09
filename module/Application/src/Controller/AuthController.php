<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AuthController extends AbstractActionController
{
    
    public function indexAction()
    {
        $View = new ViewModel();
        $View->setTerminal(true);
        return $View;
    }
    
    public function changePasswordAction()
    {
        $View = new ViewModel();
        $View->setTerminal(true);
        return $View;
    }
    
    //goto not permission page
    public function noPermissionAction()
    {
        $view = new ViewModel([
            'title'=> '无权访问！',
            'desc'=> '您无权访问该资源!'
        ]);
        $view ->setTerminal(true);
        $view ->setTemplate('error/admin/error');
        return $view;
    }
}
