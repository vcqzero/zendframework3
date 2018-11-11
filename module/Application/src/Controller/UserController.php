<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    //go to index page
    public function indexAction()
    {
        return new ViewModel([
            'where' => $this->params()->fromQuery(),
            'page'  => $this->params()->fromQuery('page', 1),
        ]);
    }
    
    //goto add page
    public function addModalAction()
    {
        $this->layout()->setTemplate('layout/blank.phtml');
        return new ViewModel([
        ]);
    }
    
    //goto edit page
    public function editModalAction()
    {
        $this->layout()->setTemplate('layout/blank.phtml');
        return new ViewModel($this->params()->fromRoute());
    }
    
    //goto change password page
    public function resetPasswordModalAction()
    {
        $this->layout()->setTemplate('layout/blank.phtml');
        $userID    = $this->params()->fromRoute('userID', 0);
        return new ViewModel([
            'userID' => $userID,
        ]);
    }
}
