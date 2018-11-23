<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
// use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Api\Service\Auther;
use Zend\Stdlib\ArrayObject;
use Zend\View\Model\JsonModel;

class TestController extends AbstractActionController
{
    private $Auther;
    public function __construct(Auther $Auther)
    {
        $this->Auther = $Auther;
    }
    
    //index
    public function indexAction()
    {
        echo json_encode(true);
        exit();
        $view = new JsonModel();
        return $view;
    }
}
