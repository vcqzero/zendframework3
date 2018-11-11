<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
// use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Api\Service\UserManager;

class TestController extends AbstractActionController
{
    private $UserManager;
    public function __construct(UserManager $UserManager)
    {
        $this->UserManager = $UserManager;
    }
    
    //index
    public function indexAction()
    {
        $TableGateway = $this->UserManager->MyTableGateway;
        $users = $TableGateway->paginator();
        
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        foreach ($users as $user)
        {
            var_dump($user);
        }
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
        
        $viewModel = new ViewModel();
        return $viewModel;
    }
}
