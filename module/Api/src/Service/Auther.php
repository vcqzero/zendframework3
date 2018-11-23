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
    private $UserManager;
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
        SessionManager $SessionManager,
        UserManager $UserManager
        )
    {
        $this->setAuthAdapter($AuthAdapter);
        $this->SessionManager= $SessionManager;
        $this->AuthService   = new AuthenticationService();
        $this->UserManager   = $UserManager;
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
            $identity = $AuthAdapter->getResultRowObject(null, ['password']);
            //这里增加以上信息到identity中，方便以后获取
            //仅保存id 和 username 其他项是变量，不可保存
            $this->setIdentity($identity);
            $this->SessionManager->regenerateId();
        }
        return $valid;
    }
    
    /**
    * 非username和password方式验证
    * 
    * @param mixed $identity 可以将任何信息存入，只有保证有内容即可表示登录了 
    */
    public function setIdentity($identity)
    {
        if (is_array($identity)) $identity = (object) $identity;
        $this->AuthService->clearIdentity();
        $this->AuthService->getStorage()->write($identity);
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
     * 该方法直接访问session中的数据
     * 非常可靠
     * 
     * @return boolean
     */
    public function isLogin()
    {
        if (!$this->AuthService->hasIdentity()) return false;
        $isLogin =  !empty($this->AuthService->getIdentity());
        //每次验证成功之后刷新identity
        if ($isLogin) $this->refreshIdentity();
        return $isLogin;
    }
    
    public function getRole()
    {
        $identity = $this->AuthService->getIdentity();
        return $identity->role;
    }
    
    /**
    * 重新刷新缓存中的identity
    * 
    * @return array $identity       
    */
    public function refreshIdentity()
    {
        $identity = $this->AuthService->getIdentity();
        $id = $identity->id;
        $identity = $this->UserManager->MyTableGateway->selectOne($id);
        $this->setIdentity($identity);
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
