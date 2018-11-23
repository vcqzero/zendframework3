<?php
namespace Api\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Api\Controller\Plugin\TokenPlugin;

/**
 * 用于分页的管理
 * 注意不要和zend本身的paginator混淆了
 */
class TokenHelper extends AbstractHelper 
{
    private $TokenPlugin;
    
    public function __construct(
        TokenPlugin $TokenPlugin
        )
    {
        $this->TokenPlugin = $TokenPlugin;
    }
    
    public function getToken()
    {
        return $this->TokenPlugin->token();
    }
    
}
