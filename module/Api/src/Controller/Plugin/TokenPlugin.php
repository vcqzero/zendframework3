<?php
namespace Api\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class TokenPlugin extends AbstractPlugin
{
    const TOKEN_ERROR_MSG   = '非法访问';
    
    private $TokenSession;
    private $token_seperate = '_this_is_my_token_';
    
    public function __construct(
        $TokenSession
        )
    {
        $this->TokenSession   = $TokenSession;
    }
    
    /**
     * 生成一个token并存入session中，
     * 返回生成的token
     * 此token中分隔符|前面的是sessionKey
     *
     * @param  string $tokenName token名称
     * @return string json 字符串
     */
    public function token()
    {
        //获取session key 和 token
        $tokenName  = $this->getTokenName();
        $tokenString= $this->getTokenString();
        
        $token      = $tokenName . $this->token_seperate . $tokenString;
        //将tokenName拼接到token中
        //保存session
        $this->TokenSession->$tokenName = $tokenString;
        
        return  $token;
    }
    
    /**
     * 获取最终存入session时使用的session key name
     *
     * @param  string $tokenName
     * @return string key
     */
    private function getTokenName()
    {
        $unid = uniqid('FOR_TOKEN_SESSION');
        $unid = md5($unid);
        $name = \Zend\Math\Rand::getString(16, $unid);
        return trim($name);
    }
    /**
     * create token
     *
     * @param  void
     * @return string (Md5)
     */
    private function getTokenString():string
    {
        $numbers    = range(10, 90); // 将10~50的数字排成数组
        shuffle($numbers); // shuffle 将数组顺序随即打乱
        $result     = array_slice($numbers, 0, 3); // 从数组中下标为3的开始取值，步长为3 也就是获取了6位随机数字
        $random     = implode('', $result);
        return md5($random);
    }
    
    /**
     * 验证token值是否合法
     *
     * @param string $token
     * @return boolean true or false
     */
    public function isValid($token)
    {
        $res = false;
        if (empty($token)) {
            return false;
        }
        try{
            $tokenArray  = explode($this->token_seperate, $token);
            $tokenName   = $tokenArray[0];
            $tokenString = $tokenArray[1];
            $tokenInSession = $this->TokenSession->$tokenName;
            $res = $tokenString == $tokenInSession;
        }catch (\Exception $e ){
            $res = false;
        }
        return $res;
    }
}
