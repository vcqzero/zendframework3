<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Api\Service\WebsiteManager;
use Zend\View\Model\JsonModel;
use Api\Uploader\ImageUploader;

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
        $type  = $this->params()->fromQuery('type');
        $res   = $this->WebsiteManager->edit($name, $value, $type);
        $view = new JsonModel($res);
        return $view;
    }
    
    //上传logo
    public function uploadAction()
    {
        $files = $this->params()->fromFiles();
        $type  = $this->params()->fromPost('type');
        switch ($type) {
            case 'ico' :
                $target = 'public/_application/upload/ico/ico';
                break;
            case 'logo' :
                $target = 'public/_application/upload/logo/logo';
                break;
            default:
                $target = '';
        }
        $Uploader = new ImageUploader();
        $Uploader->upload($files, $target, true);
        $url = $Uploader->getUrl();
        $res  = $this->WebsiteManager->edit($type, $url);
        $res['url'] = $url;
        $view = new JsonModel($res);
        return $view;
    }
}
