<?php
namespace Api\Service;

use Zend\Config\Config;
use Api\Tool\MyAjax;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;

/**
* 所有的配置信息，都从此读取
*/
class WebsiteManager
{
    //config path 
    const PATH_ADMIN_WEBSITE_CONFIG = 'config/custom/website/admin.website.config.php';
    const PATH_EMAIL_CONFIG = 'config/custom/email.config.php';
    
    //img path in public
    const PATH_IMG_BASIC = 'public/img/website';
    //website
    const WEBSITE_ADMIN = 'admin';//admin
    const WEBSITE_EMAIL = 'email';//email 为全站公用和站点性质不同
    
    private $smtp;
    public function __construct(Smtp $smtp)
    {
        $this->smtp= $smtp;
    }
    /**
    * get the website config
    * @param  string $website
    * @return array       
    */
    public function getWebsiteConfig($website)
    {
        $path = $this->getPathConfig($website);
        if(empty($path)) return [];
        return include $path;
    }
    
    public function getPathConfig($website)
    {
        switch ($website)
        {
            case self::WEBSITE_ADMIN:
                $path = self::PATH_ADMIN_WEBSITE_CONFIG;
                break;
            case self::WEBSITE_EMAIL:
                $path= self::PATH_EMAIL_CONFIG;
                break;
            default:
                $path = false;
        }
        return $path;
    }
    
    /**
    * get path to save imgs
    * 
    * @param  
    * @return string $path       
    */
    public function getPathSaveImg($website)
    {
        $basic_path = self::PATH_IMG_BASIC;
        $path       = $basic_path . '/' . $website;
        if(!file_exists($path)) mkdir($path);
        return $path;
    }
    
    /**
    * 编辑
    * 
    * @param  string $name
    * @param  string $value
    * @return array $ajaxRes       
    */
    public function edit($name, $value, $website)
    {
        //website config
        try{
            $config = $this->getWebsiteConfig($website);
            $config_path = $this->getPathConfig($website);
            $config[$name] = $value;
            $writer = new \Zend\Config\Writer\PhpArray();
            $writer->toFile($config_path, $config);
            $res = [
                MyAjax::SUBMIT_SUCCESS=> true,
            ];
        }catch (\Exception $e ){
            $res = [
                MyAjax::SUBMIT_SUCCESS=> true,
                MyAjax::SUBMIT_MSG => '保存失败',
            ];
        }
        
        return $res;
    }
    
    public function testMail($email)
    {
        try{
            $smtp = $this->smtp;
            $message = new Message();
            //set subject
            $subject = '设置成果';
            $message->setSubject($subject);
            //set sender
            $sender = $smtp->getOptions()->getConnectionConfig()['username'];
            $message->setSender($sender);
            $message->setFrom($sender);
            //set reciver
            $message->setTo($email);
            //utf-8
            $message->setEncoding('UTF-8');
            //set body
            $body = '邮件测试成果，请不要回复。';
            $message->setBody($body);
            $smtp->send($message);
            $res = true;
        }catch (\Exception $e ){
            $res = $e->getMessage();        
        }
        
        return $res;
    }
}

