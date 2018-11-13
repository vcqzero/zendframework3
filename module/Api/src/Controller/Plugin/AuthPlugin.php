<?php
namespace Api\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter as AuthAdapter;
use Api\Service\UserManager;
use Api\Repository\Table\User;

class AuthPlugin extends AbstractPlugin
{
    private $authAdapter;
    private $UserManager;
    private $AuthServer;
    
    private $error;//登录验证时的错误信息
    //登录验证信息
    const LOGIN_SUCCESS       = "登录成功";
    const USER_NOT_FIND       = "用户不存在";
    const PASSWORD_IS_WRONG   = "密码错误";
    const LOGIN_FAIL          = "登录失败";
    
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
        AuthAdapter $authAdapter,
        UserManager $UserManager
        )
    {
        $this->authAdapter = $authAdapter;
        $this->UserManager = $UserManager;
        $this->AuthServer= new AuthenticationService();
    }
    
    /**
     * 验证用户名和口令密码，登录成功进入...登录失败返回失败信息
     *
     * @param string $username
     * @param string $password
     * @return bool true if success or false
     */
    public function login($username, $password)
    {
        if (empty($username) || empty($password))
        {
            return false;
        }
        $authAdapter    = $this->authAdapter;
        $tableName      = User::TABLE_NAME;
        $filed_username = User::FILED_USERNAME;
        $filed_password = User::FILED_PASSWORD;
        
        //设置要验证的数据表和username和password字段名称
        $authAdapter->setTableName($tableName);
        $authAdapter->setIdentityColumn($filed_username);
        $authAdapter->setCredentialColumn($filed_password);
        //设置密码验证的回调函数
        $validationCallback=function($hash, $password){
            return password_verify($password, $hash);
        };
        $authAdapter->setCredentialValidationCallback($validationCallback);
        
        //传入验证的用户名和密码
        $authAdapter->setIdentity($username)->setCredential($password);
        
        //实例化auth对象 并进行验证
        $auth   = $this->AuthServer->setAdapter($authAdapter);
//         $auth   ->clearIdentity();//清除原来的验证
        $result =$auth->authenticate();//会进行数据库验证，验证成功，保持到数据库中
        //获取验证结果
        //不同的code对应不同的错误信息
        $code  = $result->getCode();
        $this  ->mapErrorWithCode($code);
        $valid = $result->isValid();
        return $valid;
    }
    
    /**
     * 根据用户登录code生成登录结果
     *
     * @param  int $code
     * @return string $message
     */
    private function mapErrorWithCode($code)
    {
        switch ($code)
        {
            case Result::SUCCESS:
                $mess = '';
                break;
            case Result::FAILURE_IDENTITY_NOT_FOUND:
            case Result::FAILURE_IDENTITY_AMBIGUOUS:
                $mess=self::USER_NOT_FIND;
                break;
            case Result::FAILURE_CREDENTIAL_INVALID:
                $mess=self::PASSWORD_IS_WRONG;
                break;
            default:
                $mess=self::LOGIN_FAIL;
        }
        $this->error = $mess;
    }
    
    /**
     * 用户注销登录
     * @param  null
     * @return boolean 成功返回true
     */
    public function logout()
    {
        $this->AuthServer->clearIdentity();
    }
}
