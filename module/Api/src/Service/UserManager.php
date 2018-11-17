<?php
/**
* 关于用户表的增删改查
* 
*/
namespace Api\Service;

use Zend\Db\Sql\Where;
use Api\Filter\FormFilter;
use Api\Repository\PdoMysql;
use Api\Repository\Table\User;
use Api\Repository\MyTableGateway;

class UserManager
{
    public  $FormFilter;
    public  $MyTableGateway;
    
    private $super_admin_config;
    
    const STATUS_ENABLED    = 'ENABLED';
    
    /**
    * @var 超级管理员 
    */
    const ROLE_SUPER_USER = 'SUPER_USER';
    
    /**
    * @var 工地管理员
    */
    const ROLE_WORKYARD_ADMIN = 'WORKYARD_ADMIN';
    
    /**
    * @var 巡检员
    */
    const ROLE_WORKYARD_GUARD = 'WORKYARD_GUARD';
    
    /**
    * @var 未登录 游客身份
    */
    const ROLE_GUEST = 'GUSET';

    public function __construct(
        FormFilter $FormFilter,
        $super_admin_config,
        MyTableGateway $MyTableGateway
        )
    {
        $this->FormFilter   = $FormFilter;
        $this->super_admin_config = $super_admin_config;
        $this->MyTableGateway = $MyTableGateway;
    }
    
    /**
    * 
    * 
    * @param  string $identity
    * @return UserEntity       
    */
    public function findUserByIdentity($identity)
    {
        $filed_username = User::FILED_USERNAME;
        $where  = [
            $filed_username => $identity
        ];
        $user = $this->PdoMysql->findOne($where);
        return $user;
    }
    
    /**
    * 随机创建密码明文
    * 
    * @param  int $length 密码长度
    * @return string       
    */
    public function buildNewPassword() 
    {
        return self::DEFUALT_PASSWORD;
    }
    
    private function getPasswordRand($length = 6)
    {
        // 密码字符集，可任意添加你需要的字符
        $chars = [
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
            'i', 'j', 'k', 'l','m', 'n', 'o', 'p', 'q', 'r', 's',
            't', 'u', 'v', 'w', 'x', 'y','z', 'A', 'B', 'C', 'D',
            'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L','M', 'N', 'O',
            'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y','Z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
        ];
        shuffle($chars);
        $offset = rand(0, 10);
        // 在 $chars 中随机取 $length 个数组元素键名
        $password = array_slice($chars, $offset, $length);
        $password = implode('', $password);
        return $password; 
    }
    
    public function password_hash($password)
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
    * 验证用户名密码是否正确
    * 
    * @param  string $password
    * @param  string $username
    * @return bool        
    */
    public function validPassword($password, $username)
    {
        $user = $this->findUserByIdentity($username);
        $hash = $user->getPassword();
        return password_verify($password, $hash);
    }
    
    public function createSuperAdmin()
    {
        //如果用户不存在则创建
        $name = $this->getSuperAdminName();
        
        if (empty($name))
        {
            return ;
        }
        
        $where = [
            User::FILED_USERNAME => $name
        ];
        
        if (!empty($this->MyTableGateway->count($where))) 
        {
            return;    
        }
        
        //进行新增
        $password = $this->getSuperAdminPassword();
        
        if (empty($password))
        {
            return ;
        }
        
        $password = $this->password_hash($password);
        $values= [
            User::FILED_USERNAME => $name,
            User::FILED_PASSWORD => $password,
            User::FILED_STATUS   => self::STATUS_ENABLED,
            User::FILED_ROLE    => self::ROLE_SUPER_USER,
            User::FILED_WORKYARD_ID => 0,
        ];
        $this->MyTableGateway->insert($values);
    }
    
    private function getSuperAdminName()
    {
        $config = $this->super_admin_config;
        
        if(isset($config['username']))
        {
            return $config['username'];
        }
    }
    
    private function getSuperAdminPassword()
    {
        $config = $this->super_admin_config;
        if(isset($config['password']))
        {
            return $config['password'];
        }
    }
}

