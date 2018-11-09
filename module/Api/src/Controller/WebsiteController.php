<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Api\Service\WebsiteManager;

class WebsiteController extends AbstractActionController
{
    private $WebsiteManager;
    public function __construct(
        WebsiteManager $WebsiteManager
        )
    {
        $this->WebsiteManager = $WebsiteManager;
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
    
    //edit the website infomation
    public function editBasicAction()
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        //获取用户提交表单
        $values = $this->params()->fromPost();
        $writer = new \Zend\Config\Writer\PhpArray();
        $writer->toFile(WebsiteManager::PATH_BASIC_WEBSITE_CONFIG, $values);
        $this->ajax()->success(true);
    }
    
//     public function editEmailAction()
//     {
//         $values = $this->params()->fromPost();
//         $writer = new \Zend\Config\Writer\PhpArray();
//         $writer->toFile(WebsiteManager::PATH_EMAIL_WEBSITE_CONFIG, $values);
//         $this->ajax()->success(true);
//     }
    
//     public function testEmailAction()
//     {
//         $this->MyMailer->sendEmailOnTest();
//         echo true;
//         exit();
//     }
}
