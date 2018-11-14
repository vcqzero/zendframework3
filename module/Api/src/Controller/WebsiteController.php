<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Api\Service\WebsiteManager;
use Zend\View\Model\JsonModel;
use Zend\Config\Config;
use Api\Tool\MyAjax;

class WebsiteController extends AbstractActionController
{
    private $WebsiteManager;
    public function __construct(
        WebsiteManager $WebsiteManager
        )
    {
        $this->WebsiteManager = $WebsiteManager;
    }
    
    //edit the website infomation
    public function editAction()
    {
        //获取用户提交表单
        $name = $this->params()->fromPost('name');
        $value = $this->params()->fromPost('value');
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
        $view = new JsonModel($res);
        return $view;
    }
}
