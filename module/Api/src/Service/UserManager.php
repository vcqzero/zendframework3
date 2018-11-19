<?php
/**
* 关于用户表的增删改查
* 
*/
namespace Api\Service;

use Zend\Db\Sql\Where;
use Api\Filter\FormFilter;
use Api\Repository\Table\User;
use Api\Repository\MyTableGateway;

class UserManager
{
    public  $FormFilter;
    public  $MyTableGateway;
    private $super_user;
    
    const STATUS_ENABLED    = 'ENABLED';
    
    const ROLE_SUPER_USER = 'SUPER_USER';
    const ROLE_GUEST      = 'GUEST';

    public function __construct(
        FormFilter $FormFilter,
        MyTableGateway $MyTableGateway,
        $super_user =[]
        )
    {
        $this->FormFilter       = $FormFilter;
        $this->MyTableGateway   = $MyTableGateway;
        $this->super_user       = $super_user;
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
    
    public function createSuperUser()
    {
        //get the info of super user
        $superUser = $this->super_user;
        $username  = $superUser['username'] ?? '';
        $password  = $superUser['password'] ?? '';
        
        if (empty($username) || empty($password)) return ;
        //check whether exist
        $where = [
            User::FILED_USERNAME => $username
        ];
        //if exist
        if (!empty($this->MyTableGateway->count($where))) return ;
        
        //if not exist
        $password = $this->password_hash($password);
        $values= [
            User::FILED_USERNAME => $username,
            User::FILED_PASSWORD => $password,
            User::FILED_STATUS   => self::STATUS_ENABLED,
            User::FILED_ROLE     => self::ROLE_SUPER_USER,
        ];
        $this->MyTableGateway->insert($values);
    }
}

