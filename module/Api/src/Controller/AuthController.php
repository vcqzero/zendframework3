<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Api\Controller\Plugin\AuthPlugin;
use Api\Controller\Plugin\AjaxPlugin;

class AuthController extends AbstractActionController
{
    private $AuthPlugin;
    public function __construct(
        AuthPlugin $AuthPlugin
        )
    {
        $this->AuthPlugin = $AuthPlugin;
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
    
    //login
    public function loginAction()
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        //进行登录验证
        $username = $this->params()->fromPost('username');
        $password = $this->params()->fromPost('password');
        
        $isLogin  = $this->AuthPlugin->login($username, $password);
        $error    = $this->AuthPlugin->getError();
        $this->AjaxPlugin->success($isLogin, ['error'=>$error]);
    }
    
    //logout
    public function logoutAction()
    {
        $this->AuthPlugin->logout();
        echo "<script>location='/'</script>";
        exit();
    }
    
    public function loginPageAction()
    {
        //dispath by pc or mobile
    }
    
    public function noPermissionPageAction()
    {
        //dispath by pc or mobile
    }
}
