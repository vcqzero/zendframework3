<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Api\Service\UserManager;

class UserController extends AbstractActionController
{
    private $UserManager;
    
    public function __construct(
        UserManager $UserManager
        )
    {
        $this->UserManager = $UserManager;
    }
    
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
