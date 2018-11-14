<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
// use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Api\Service\UserManager;
use Zend\Session\SessionManager;

class TestController extends AbstractActionController
{
    private $UserManager;
    private $SessionManager;
    public function __construct(
        UserManager $UserManager,
        SessionManager $SessionManager
        )
    {
        $this->UserManager      = $UserManager;
        $this->SessionManager   = $SessionManager;
    }
    
    //index
    public function indexAction()
    {
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
        var_dump('ok');
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
        
        
        
        
        
        
        
        
        
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
        var_dump();
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
        
        $viewModel = new ViewModel();
        return $viewModel;
    }
}
