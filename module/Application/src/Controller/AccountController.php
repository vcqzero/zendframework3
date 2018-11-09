<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
* @desc 账户管理
* 
* 
*/
class AccountController extends AbstractActionController
{
    public function __construct(
        )
    {
    }
    
    //go to index page
    /**
    * 
    * 
    * @param  
    * @return     
    */
    public function changePasswordAction()
    {
        return new ViewModel([
        ]);
    }
    
}
