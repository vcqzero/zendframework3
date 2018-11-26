<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Service\WebsiteManager;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class WebsiteHelper extends AbstractHelper 
{
    private $WebsiteManager;
    private $record;//版权信息
    public function __construct(
        WebsiteManager $WebsiteManager
        )
    {
        $this->WebsiteManager = $WebsiteManager;
    }
    
    /**
    * 获取站点配置信息
    * 
    * @param  string $website the website need
    * @param  string $param the param
    * @return string || '' return '' if not find    
    */
    public function getWebsiteConfig($website, $param)
    {
        $config = $this->WebsiteManager->getWebsiteConfig($website);
        if(!empty($config[$param]))
        {
            return $config[$param];
        }else {
            return '';
        }
    }
    
    public function getEmailConfig($param)
    {
        $config = $this->WebsiteManager->getWebsiteConfig(WebsiteManager::WEBSITE_EMAIL);
        if(!empty($config[$param]))
        {
            return $config[$param];
        }else {
            return '';
        }
    }
}
