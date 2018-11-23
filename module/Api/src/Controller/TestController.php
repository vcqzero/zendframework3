<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
// use Zend\View\Model\JsonModel;
use Zend\View\Model\JsonModel;
use Zend\Log\Logger;

class TestController extends AbstractActionController
{
    private $Logger;
    public function __construct(Logger $Logger)
    {
        
        $this->Logger= $Logger;
    }
    
    //index
    public function indexAction()
    {
        $image_url = 'test.jpg';
        $res = file_get_contents($image_url);
        $file_name = 'test.jpg';
        $file = fopen($file_name, 'w');
        fwrite($file, $res);
        fclose($file);
        
        exit();
        
        
        
        $view = new JsonModel();
        return $view;
    }
}
