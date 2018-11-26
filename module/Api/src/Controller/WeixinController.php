<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class WeixinController extends AbstractActionController
{
    private $Weixiner;
    public function __construct(
        \Api\Service\Weixiner $Weixiner
        )
    {
        $this->Weixiner = $Weixiner;
    }
    
    public function getWxConfigAction()
    {
        $url = $this->params()->fromPost('url');
        $config = $this->Weixiner->getWxConfig($url);
        return new JsonModel($config);
    }
}
