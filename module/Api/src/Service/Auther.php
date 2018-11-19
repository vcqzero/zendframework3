<?php
namespace Api\Service;

use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;
use Api\Repository\Table\User;
use Zend\Session\SessionManager;

class Auther
{
    public $AuthAdapter;
    public $AuthService;
    private $SessionManager;
    private $error;
    
    /**
     * @param \Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter $AuthAdapter
     */
    private function setAuthAdapter($AuthAdapter)
    {
        //设置要验证的数据表和username和password字段名称
        $tableName      = User::TABLE_NAME;
        $filed_username = User::FILED_USERNAME;
        $filed_password = User::FILED_PASSWORD;
        $AuthAdapter->setTableName($tableName);
        $AuthAdapter->setIdentityColumn($filed_username);
        $AuthAdapter->setCredentialColumn($filed_password);
        
        //设置密码验证的回调函数
        $validationCallback=function($hash, $password){
            return password_verify($password, $hash);
        };
        $AuthAdapter->setCredentialValidationCallback($validationCallback);
        $this->AuthAdapter = $AuthAdapter;
    }

    /**
     * 返回登录验证的错误信息，默认为null
     *
     * @return the $error
     */
    public function getError()
    {
        return $this->error;
    }
    
    public function __construct(
        AuthAdapter    $AuthAdapter,
        SessionManager $SessionManager
        )
    {
        $this->setAuthAdapter($AuthAdapter);
        $this->SessionManager= $SessionManager;
        $this->AuthService   = new AuthenticationService();
    }
    
    
    /**
     * 验证用户名和口令密码，登录成功进入...登录失败返回失败信息
     *
     * @param string $username
     * @param string $password
     * @param bool   $remember
     * @return bool true if success or false
     */
    public function login($username, $password)
    {
        if (empty($username))
        {
            return false;
        }
        $AuthAdapter    = $this->AuthAdapter;
        $AuthService    = $this->AuthService;
        
        $AuthAdapter->setIdentity($username)->setCredential($password);
        
        $AuthService->setAdapter($AuthAdapter);
        $result = $AuthService->authenticate();//会进行数据库验证，验证成功，保持到数据库中
        //获取验证结果
        //不同的code对应不同的错误信息
        $code  = $result->getCode();
        $this  ->setError($code);
        $valid = $result->isValid();
        
        //如果登录成功重新生成sessionid
        if ($valid) {
            $user = $AuthAdapter->getResultRowObject(['username', 'status', 'role']);
            //默认情况下，identity为username，、
            //这里增加以上信息到identity中，方便以后获取
            $AuthService->getStorage()->write($user);
            $this->SessionManager->regenerateId();
        }
        return $valid;
        
    }
    
    public function remember($ttl)
    {
        $this->SessionManager->rememberMe($ttl);
    }
    
    public function forget()
    {
        $this->SessionManager->forgetMe();
    }
    
    /**
     * @param int  $code
     */
    private function setError($code)
    {
        switch ($code)
        {
            case Result::SUCCESS:
                $error= '登录成功';
                break;
            case Result::FAILURE_IDENTITY_NOT_FOUND:
            case Result::FAILURE_IDENTITY_AMBIGUOUS:
                $error = '用户名错误或用户不存在';
                break;
            case Result::FAILURE_CREDENTIAL_INVALID:
                $error = '密码错误';
                break;
            default:
                $error = '用户名或密码错误';
        }
        
        $this->error = $error;
    }
    
    /**
     * 全局判断用户是否登录的唯一入口
     * 
     * @return boolean
     */
    public function isLogin()
    {
        return $this->AuthService->hasIdentity();
    }
    
    /**
    * get current user info
    * 
    * @param  void
    * @return \stdClass 
    */
    public function getUser()
    {
        return $this->AuthService->getIdentity();
    }
    
    public function getRole()
    {
        $user = $this->AuthService->getIdentity();
        return $user->role;
    }
    
    /**
     * 用户注销登录
     * @param  null
     * @return boolean 成功返回true
     */
    public function logout()
    {
        $this->AuthService->clearIdentity();
        $Manager = $this->SessionManager;
        $Manager->forgetMe();
        $Manager->destroy();
        $Manager->regenerateId();
        return ;
    }
}
