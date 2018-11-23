<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Api\Service\UserManager;
use Api\Tool\MyAjax;
use Zend\View\Model\JsonModel;
use Api\Repository\Table\User;

class AccountController extends AbstractActionController
{
    private $UserManager;
    
    public function __construct(
        UserManager $UserManager
        )
    {
        $this->UserManager  = $UserManager;
    }
    
    public function editAction()
    {
        //get id
        $identity = $this->identity();
        $id       = $identity->id;
        //get value 
        $name   = $this->params()->fromPost('name');
        $vaule  = $this->params()->fromPost('value');
        $set    = [
            $name => $vaule
        ];
        //filter
        $set = $this->UserManager->FormFilter->filter($set);
        //do update mysql
        $res      = $this->UserManager->MyTableGateway->updateById($set, $id);
        $res      = [
            MyAjax::SUBMIT_SUCCESS => $res,
            MyAjax::SUBMIT_MSG => '数据库错误',
        ];
        return new JsonModel($res);
    }
    
    public function validPasswordAction()
    {
        //get id
        $identity = $this->identity();
        $id       = $identity->id;
        $password = $this->params()->fromQuery('old-password');
        $hash     = $this->UserManager->getUser($id, User::FILED_PASSWORD);
        $res      = $this->UserManager->validPassword($password, $hash);
        exit(json_encode($res));
    }
    
    public function passwordAction()
    {
        
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
        var_dump();
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
        
        //get id
        $identity = $this->identity();
        $id       = $identity->id;
        $password = $this->params()->fromPost('password');
        $password = $this->UserManager->password_hash($password);
        $set = [
            User::FILED_PASSWORD => $password
        ];
        $res = $this->UserManager->MyTableGateway->updateById($set, $id);
        $res      = [
            MyAjax::SUBMIT_SUCCESS => $res,
            MyAjax::SUBMIT_MSG => '数据库错误',
        ];
        return new JsonModel($res);
    }
}