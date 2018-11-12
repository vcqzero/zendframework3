<?php
namespace Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Api\Service\UserManager;
use Api\Controller\Plugin\AjaxPlugin;
use Api\Repository\Repositories\User;

class UserController extends AbstractActionController
{
    private $UserManager;
    
    public function __construct(
        UserManager $UserManager
        )
    {
        $this->UserManager  = $UserManager;
    }
    
    public function validNameAction()
    {
        $username     = $this->params()->fromPost('username');
        $where        = [
            User::FILED_USERNAME => $username,
        ];
        $count      = $this->UserManager->MyOrm->count($where);
        $this->AjaxPlugin->valid(empty($count));
    }
    
    public function validPasswordAction()
    {
        $from = $this->params()->fromQuery('from');
        $identity     = $this->identity();
        $password     = $this->params()->fromPost('password_old');
        $UserEntiyt   = $this->UserManager->findUserByIdentity($identity);
        $password_hash= $UserEntiyt->getPassword();
        $valid        = password_verify($password, $password_hash);
        if($from == 'weixin')
        {
            echo json_encode($valid);
            exit();
        }
        $this->AjaxPlugin->valid($valid);
    }
    
    //response add
    public function addAction()
    {
    }
    
    //do edit 
    public function editAction()
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        $userID = $this->params()->fromRoute('userID', 0);
        //获取用户提交表单
        $values = $this->params()->fromPost();
        //处理表单数据
        $values = $this->UserManager->FormFilter->getFilterValues($values);
        //执行增加操作
        $res = $this->UserManager->MyOrm->update($userID, $values);
        $this->AjaxPlugin->success($res);
    }
    
    //主动修改密码
    public function changePasswordAction()
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        $userID    = $this->params()->fromRoute('userID', 0);
        $change_initial_password = $this->params()->fromQuery('change_initial_password');
        //获取用户提交表单
        $password  = $this->params()->fromPost('password');
        $password_hash  = $this->UserManager->password_hash($password);
        $values    = [
            User::FILED_PASSWORD  => $password_hash,
        ];
        if ($change_initial_password == 'true') {
            $values[User::FILED_STATUS] = UserManager::STATUS_ENABLED;
        }
        //执行增加操作
        $res = $this->UserManager->MyOrm->update($userID, $values);
        $this->AjaxPlugin->success($res);
    }
    
    //管理员重置密码
    public function resetPasswordAction()
    {
        $token  = $this->params()->fromQuery('token');
        if (!$this->Token()->isValid($token))
        {
            $this->ajax()->success(false);
        }
        $userID    = $this->params()->fromRoute('userID', 0);
        //获取密码
        $password       = $this->UserManager->buildNewPassword();
        $password_hash  = $this->UserManager->password_hash($password);
        $values = [
            User::FILED_PASSWORD  => $password_hash,
            User::FILED_STATUS    => UserManager::STATUS_WAIT_CHANGE_PASSWORD_RESET_PASSWORD
        ];
        
        $res = $this->UserManager->MyOrm->update($userID, $values);
        $this->AjaxPlugin->success($res);
    }
}
