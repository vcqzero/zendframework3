<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    //go to index page
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function tableAction()
    {
        $post  = $this->params()->fromPost();
        $query = [
            'query'=>$this->params()->fromQuery()
        ];
        $view = new ViewModel(array_merge($post, $query));
        $view ->setTerminal(true);
        return $view;
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
