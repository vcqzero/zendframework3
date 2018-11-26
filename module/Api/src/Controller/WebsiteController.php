<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Api\Service\WebsiteManager;
use Zend\View\Model\JsonModel;
use Api\Uploader\ImageUploader;
use Api\Tool\MyAjax;
use Api\Service\Weixiner;

class WebsiteController extends AbstractActionController
{
    private $WebsiteManager;
    private $Weixiner;
    public function __construct(
        WebsiteManager $WebsiteManager,
        Weixiner $Weixiner
        )
    {
        $this->WebsiteManager = $WebsiteManager;
        $this->Weixiner = $Weixiner;
    }
    
    //edit the website infomation
    public function editWebsiteAction()
    {
        //获取用户提交表单
        $name  = $this->params()->fromPost('name');
        $value = $this->params()->fromPost('value');
        $website  = $this->params()->fromQuery('website');
        $res   = $this->WebsiteManager->editWebsite($name, $value, $website);
        $view = new JsonModel($res);
        return $view;
    }
    
    //edit the website api
    public function editApiAction()
    {
        //获取用户提交表单
        $name  = $this->params()->fromPost('name');
        $value = $this->params()->fromPost('value');
        $api   = $this->params()->fromQuery('api');
        $res   = $this->WebsiteManager->editApi($name, $value, $api);
        $view = new JsonModel($res);
        return $view;
    }
    
    //上传logo
    public function uploadAction()
    {
        $files = $this->params()->fromFiles();
        $name  = $this->params()->fromPost('name');
        $website = $this->params()->fromPost('website');
        $path_website = $this->WebsiteManager->getPathSaveImg($website);
        $target  = $path_website . '/' . $name;
        //upload
        $Uploader = new ImageUploader();
        $Uploader->upload($files, $target, true);
        //update config
        $url = $Uploader->getUrl();
        $res  = $this->WebsiteManager->editWebsite($name, $url, $website);
        $res['url'] = $url;
        $view = new JsonModel($res);
        return $view;
    }
    
    public function testEmailAction()
    {
        $email = $this->params()->fromPost('email');
        $res = $this->WebsiteManager->testMail($email);
        return new JsonModel([
            MyAjax::SUBMIT_SUCCESS => $res === true,
            MyAjax::SUBMIT_MSG => $res,
        ]);
    }
    
    public function testWeixinAction()
    {
        $res = $this->Weixiner->getAccessToken(true);
        return new JsonModel([
            MyAjax::SUBMIT_SUCCESS => is_string($res),
            MyAjax::SUBMIT_MSG => '',
        ]);
    }
}
