<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
// use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Api\Service\UserManager;
use Zend\Session\SessionManager;
use Zend\Session\Container;

class TestController extends AbstractActionController
{
    private $UserManager;
    private $SessionManager;
    private $mySession;
    public function __construct(
        UserManager $UserManager,
        SessionManager $SessionManager,
        $mySession
        )
    {
        $this->UserManager      = $UserManager;
        $this->SessionManager   = $SessionManager;
        $this->mySession= $mySession;
        $this->mySession->poo = 'qinchong';
    }
    
    //index
    public function indexAction()
    {
<<<<<<< HEAD
        $manager = $this->SessionManager;
//         $manager->forgetMe();
//         $id = $manager->getId();
//         $manager->destroy();
        $manager->regenerateId();
        $manager->start();
//         $manager->setId('ppkpkpkk');
        
        
//         $manager->setName('pp');
=======
        $Manager = $this->SessionManager;
        $Manager->start();
        $contianer = new Container('Zend_Auth', $Manager);
        $contianer->storage = 'admin';
>>>>>>> origin/master
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
<<<<<<< HEAD
//         var_dump($id);
        var_dump($this->mySession->poo);
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
        
        
        
        
        
        
        
        
        
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
        var_dump();
=======
        var_dump($contianer->storage);
>>>>>>> origin/master
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
        
        $viewModel = new ViewModel();
        return $viewModel;
    }
}
