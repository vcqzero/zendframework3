<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
// use Zend\View\Model\JsonModel;
use Zend\View\Model\JsonModel;
use Zend\Log\Logger;

class TestController extends AbstractActionController
{
    private $Logger;
    public function __construct(Logger $Logger)
    {
        
        $this->Logger= $Logger;
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
        
        $view = new JsonModel();
        return $view;
    }
}
