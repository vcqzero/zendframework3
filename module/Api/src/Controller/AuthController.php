<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Api\Tool\MyAjax;
use Api\Controller\Plugin\TokenPlugin;

class AuthController extends AbstractActionController
{
    public function __construct(){}
    
    //login
    public function loginAction()
    {
        $view   = new JsonModel();
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $view->setVariables([
                MyAjax::KEY_SUCCESS => false,
                MyAjax::KEY_MSG     => TokenPlugin::TOKEN_ERROR_MSG
            ]);
            return $view;
        }
        //进行登录验证
        $username = $this->params()->fromPost('username');
        $password = $this->params()->fromPost('password');
        
        $isLogin  = $this->Auth()->login($username, $password);
        $error    = $this->Auth()->getError();
        
        $view->setVariables([
            MyAjax::KEY_SUCCESS => $isLogin,
            MyAjax::KEY_MSG     => $error
        ]);
        return $view;
    }
    
    //logout
    public function logoutAction()
    {
        $this->Auth()->logout();
        echo "<script>location='/'</script>";
        exit();
    }
    
    public function loginPageAction()
    {
        //dispath by pc or mobile
        
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
        var_dump();
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
        
    }
    
    public function noPermissionPageAction()
    {
        //dispath by pc or mobile
        
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
        var_dump();
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
    }
}
