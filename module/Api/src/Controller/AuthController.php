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
                MyAjax::SUBMIT_SUCCESS => false,
                MyAjax::SUBMIT_MSG     => TokenPlugin::TOKEN_ERROR_MSG
            ]);
            return $view;
        }
        //进行登录验证
        $username = $this->params()->fromPost('username');
        $password = $this->params()->fromPost('password');
        
        $isLogin  = $this->Auth()->login($username, $password);
        $error    = $this->Auth()->getError();
        
        $view->setVariables([
            MyAjax::SUBMIT_SUCCESS => $isLogin,
            MyAjax::SUBMIT_MSG     => $error
        ]);
        //如果登录成功，则验证是否记住我
        $remember = $this->params()->fromPost('remember');
        if ($isLogin && $remember == 'true') {
            $this->Auth()->remember($username);
        }
        return $view;
    }
    
    //loginOnRemember
    public function loginOnRememberAction()
    {
        $view   = new JsonModel();
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $view->setVariables([
                MyAjax::SUBMIT_SUCCESS => false,
                MyAjax::SUBMIT_MSG     => TokenPlugin::TOKEN_ERROR_MSG
            ]);
            return $view;
        }
        
        //验证是否正处于记住状态 
        $username = $this->params()->fromPost('username');
        //如果不是记住状态，需要通过用户输入密码验证
        if (empty($this->Auth()->isRemember($username)))
        {
            $password = $this->params()->fromPost('password');
            $isLogin  = $this->Auth()->login($username, $password);
            $error    = $this->Auth()->getError();
            $view->setVariables([
                MyAjax::SUBMIT_SUCCESS => false,
                MyAjax::SUBMIT_MSG     => TokenPlugin::TOKEN_ERROR_MSG
            ]);
            //强制设置不记录
            $this->Auth()->forget($username);
            return $view;
        }
        
        //如果是记住状态，需要通过其他方式验证
        $isLogin  = $this->Auth()->login($username, '545', true);
        $error    = $this->Auth()->getError();
        $view->setVariables([
            MyAjax::SUBMIT_SUCCESS => false,
            MyAjax::SUBMIT_MSG     => TokenPlugin::TOKEN_ERROR_MSG
        ]);
        
        //如果登录成功，则验证是否不用记住我
        $remember = $this->params()->fromPost('remember');
        if ($isLogin && $remember != 'true') {
            $this->Auth()->forget($username);
        }
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
