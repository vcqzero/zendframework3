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
    
    public function getRecord()
    {
        return $this->WebsiteManager->getBasicParam('website_record');
    }
    
    public function getIco()
    {
         return $this->WebsiteManager->getBasicParam('ico');
    }
    
    public function getTitle()
    {
        return $this->WebsiteManager->getBasicParam('website_title');
    }
    
    public function getEmaileName()
    {
        return $this->WebsiteManager->getEmailParam('name');
    }
    
    public function getEmaileHost()
    {
        return $this->WebsiteManager->getEmailParam('host');
    }
    
    public function getEmailePort()
    {
        return $this->WebsiteManager->getEmailParam('port');
    }
    
    public function getEmaileUsername()
    {
        return $this->WebsiteManager->getEmailParam('username');
    }
    
    public function getEmailePassword()
    {
        return $this->WebsiteManager->getEmailParam('password');
    }
    
    public function getTestEmaile()
    {
        return $this->WebsiteManager->getEmailParam('test_email');
    }
}
