<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class WeixinController extends AbstractActionController
{
    private $Weixiner;
    public function __construct(
        \Api\Service\Weixiner $Weixiner
        )
    {
        $this->Weixiner = $Weixiner;
    }
    /**
     * We override the parent class' onDispatch() method to
     * set an alternative layout for all actions in this controller.
     */
    public function onDispatch(MvcEvent $e)
    {
        // Call the base class' onDispatch() first and grab the response
        $response = parent::onDispatch($e);
        
        // Set alternative layout
        $this->layout()->setTemplate('layout/blank.phtml');
        
        // Return the response
        return $response;
    }
    
    public function getWxConfigAction()
    {
        $url = $this->params()->fromPost('url');
        $config = $this->Weixiner->getWxConfig($url);
        echo json_encode($config);
        exit();
    }
}
