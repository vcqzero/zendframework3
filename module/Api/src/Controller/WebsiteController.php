<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Api\Service\WebsiteManager;
use Zend\View\Model\JsonModel;
use Api\Uploader\ImageUploader;
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
        $name  = $this->params()->fromPost('name');
        $value = $this->params()->fromPost('value');
        $website  = $this->params()->fromQuery('website');
        $res   = $this->WebsiteManager->edit($name, $value, $website);
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
        $res  = $this->WebsiteManager->edit($name, $url, $website);
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
}
