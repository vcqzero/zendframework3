<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Api\Tool\MyAjax;
use Api\Controller\Plugin\TokenPlugin;
use Api\Service\Auther;

class AuthController extends AbstractActionController
{
    const TTL_REMEMBER = 60 * 60 * 24 * 7;//默认可记住状态7天
    private $Auther;
    public function __construct(Auther $Auther)
    {
        $this->Auther = $Auther;
    }
    
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
        
        $login  = $this->Auther->login($username, $password);
        $error    = $this->Auther->getError();
        
        $view->setVariables([
            MyAjax::SUBMIT_SUCCESS => $login,
            MyAjax::SUBMIT_MSG     => $error
        ]);
        //如果登录成功，则验证是否记住我
        $remember = $this->params()->fromPost('remember');
        if ($login && $remember == 'true') {
            $this->Auther->remember(self::TTL_REMEMBER);
        }else {
            $this->Auther->forget();
        }
        return $view;
    }
    
    //logout
    public function logoutAction()
    {
        $this->Auther->logout();
        $this->redirect()->toRoute('auth');
        return $this->getResponse();//disable return view 
    }
    
    public function loginPageAction()
    {
        $module = $this->params()->fromRoute('module');
        if ($module == 'Application') {
            return $this->redirect()->toRoute('auth');
        }
        
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
        var_dump($module);
        var_dump('未指定该module下登录页面');
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
    
    public function testAction()
    {
//         $this->Auther->AuthService->clearIdentity();
//         // DEBUG INFORMATION START
//         echo '------debug start------<br/>';
//         echo "<pre>";
//         var_dump(__METHOD__ . ' on line: ' . __LINE__);
//         var_dump($this->identity()->username);
//         echo "</pre>";
//         exit('------debug end------');
        // DEBUG INFORMATION END
        
        $auth = $this->Auther->AuthService;
        $username = 'admin';
        $password = 'admin888';
        $this->Auther->login($username, $password);
        $result = $this->Auther->AuthAdapter->getResultRowObject(['username', 'status', 'role']);
        $auth->getStorage()->write($result);
        $result = $auth->getStorage()->read();
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
//         var_dump($result);
        var_dump($this->Auther->AuthService->getStorage()->read());
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
        
    }
}
