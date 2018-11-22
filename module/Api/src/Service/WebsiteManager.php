<?php
namespace Api\Service;

use Zend\Config\Config;
use Api\Tool\MyAjax;

/**
* 所有的配置信息，都从此读取
*/
class WebsiteManager
{
    const PATH_BASIC_WEBSITE_CONFIG = 'module/Api/config/website.config/basic.website.config.php';
    const PATH_EMAIL_WEBSITE_CONFIG = 'module/Api/config/website.config/email.website.config.php';
    
    private $basicConfig;
    private $emailConfig;
    
    /**
     * 获取站点基本信息的参数
     * 
     * @param string $key
     * @return the $basicConfig
     */
    public function getBasicParam($key)
    {
        if (empty($this->basicConfig))
        {
            $this->basicConfig = new \Zend\Config\Config(include self::PATH_BASIC_WEBSITE_CONFIG);
        }
        $config = $this->basicConfig;
        return $config[$key];
    }
    
    public function getEmailParam($key)
    {
        if (empty($this->emailConfig))
        {
            $this->emailConfig= new \Zend\Config\Config(include self::PATH_EMAIL_WEBSITE_CONFIG);
        }
        $config = $this->emailConfig;
        
        return $config[$key];
    }
    

    public function __construct()
    {
    }
    
    /**
    * 编辑
    * 
    * @param  string $name
    * @param  string $value
    * @return array $ajaxRes       
    */
    public function edit($name, $value)
    {
        //website config
        try{
            $config_path = WebsiteManager::PATH_BASIC_WEBSITE_CONFIG;
            $config = include $config_path;
            $config[$name] = $value;
            $writer = new \Zend\Config\Writer\PhpArray();
            $writer->toFile(WebsiteManager::PATH_BASIC_WEBSITE_CONFIG, $config);
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
}

