<?php
namespace Api\Service;

use Zend\Cache\Storage\Adapter\Filesystem;

class Weixiner
{
    const CACHE_KEY_WEIXIN_TOKEN = 'weixin-token';
    const CACHE_KEY_WEIXIN_JSAPI_TICKET = 'weixin-jsapi-ticket';
    
    const EXPIRES_IN = 7200;
    
    private $Cache;
    private $appid;
    private $secret;
    private $accessTokenError;//获取access token error
    
    /**
     * @return the $accessTokenError
     */
    public function getAccessTokenError()
    {
        return $this->accessTokenError;
    }

    public function __construct(
        Filesystem $Cache,
        $weixinConfig
        )
    {
        $this->Cache = $Cache;
        $this->appid = $weixinConfig['appid'];
        $this->secret= $weixinConfig['secret'];
    }
    
    /**
     * fetch the access_token array 
     *
     * @param  bool $force           
     * @return string $access_token
     */
    public function getAccessToken($force=false)
    {
        $Cache = $this->Cache;
        $Cache->getOptions()->setTtl(self::EXPIRES_IN - 20);
        
        $access_token = $Cache->getItem(self::CACHE_KEY_WEIXIN_TOKEN);
        if ($force || empty($access_token))
        {
            //需要重新获取token
            $url = 'https://api.weixin.qq.com/cgi-bin/token';
            
            $data = [
                'appid'     => $this->appid,
                'secret'    => $this->secret,
                'grant_type'=> 'client_credential',
            ];
            
            $res = \Zend\Http\ClientStatic::get($url, $data);
            $res = $res->getContent();
            $res = json_decode($res, true);
            
            if (!empty($res['errcode']))
            {
                $errcode = $res['errcode'];
                $errmsg  = $res['errmsg'];
                $this->accessTokenError = "获取微信access_token 发生错误，错误代码为:$errcode, 错误信息：$errmsg";
            }else {
                $access_token = $res['access_token'];
            }
            
            $Cache->setItem(self::CACHE_KEY_WEIXIN_TOKEN, $access_token);
        }
        return $access_token;
    }
    
    /**
    * 获取微信签名，用于调用sdk
    * 
    * @param  string $url 
    * @return array $config        
    */
    public function getWxConfig($url)
    {
        $str="abcdrrlljokoptldiektldlyuiopasdfghjklzxcvbnm";
        str_shuffle($str);
        $noncestr       =substr(str_shuffle($str),5,10);
        $jsapi_ticket   =$this->getJsapiTicket();
        $timestamp      =time();
        
        $data=[
            'jsapi_ticket'  =>$jsapi_ticket,
            'noncestr'      =>$noncestr,
            'timestamp'     =>$timestamp,
            'url'           =>$url,
        ];
        $data_string=[];
        foreach ($data as $key=>$val)
        {
            $data_string[]=$key . '=' . $val;
        }
        
        $signature          =sha1(implode('&', $data_string));
        $config = [
            'appId' => $this->appid,
            'timestamp' => $timestamp,
            'nonceStr' => $noncestr,
            'signature' => $signature,
        ];
        return $config;
    }
    
    /**
    * jsapi_ticket是公众号用于调用微信JS接口的临时票据。
    * 正常情况下，jsapi_ticket的有效期为7200秒，
    * 通过access_token来获取。
    * 
    * @param  
    * @return        
    */
    public function getJsapiTicket()
    {
        $Cache = $this->Cache;
        $Cache->getOptions()->setTtl(self::EXPIRES_IN - 20);
        $jsapi_ticket = $Cache->getItem(self::CACHE_KEY_WEIXIN_JSAPI_TICKET);
        //先确保access_token存在
        //防止access_token更新之后jsapi使用时出错
        $has_access_token_cached = $Cache->hasItem(self::CACHE_KEY_WEIXIN_TOKEN);
        if (empty($has_access_token_cached) || empty($jsapi_ticket))
        {
            $access_token = $this->getAccessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=$access_token&type=jsapi";
            $res = \Zend\Http\ClientStatic::get($url);
            $contet = $res->getContent();
            $contet = json_decode($contet, true);
            
            if (!empty($contet['errcode']))
            {
                $errcode = $contet['errcode'];
                $errmsg  = $contet['errmsg'];
                throw new \Exception("获取微信jspai_ticket 发生错误，错误代码为:$errcode, 错误信息：$errmsg");
            }
            
            $jsapi_ticket= $contet['ticket'];
            $Cache->setItem(self::CACHE_KEY_WEIXIN_JSAPI_TICKET, $jsapi_ticket);
        }
        return $jsapi_ticket;
    }
}

