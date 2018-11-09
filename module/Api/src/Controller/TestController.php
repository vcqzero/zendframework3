<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class TestController extends AbstractActionController
{
    public function __construct()
    {
    }
    
    //index
    public function indexAction()
    {
//         $items = 'sdjkfsjfksd';
//         $viewModel = new JsonModel();
//         $viewModel->setVariable('items', $items);
        
        $viewModel = new ViewModel();
        
        return $viewModel;
    }
}
