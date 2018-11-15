<?php
namespace Api\Service;

use Zend\Mvc\MvcEvent;
use Api\Controller\AuthController;

class Bootstraper
{
    private $UserManager;
    private $AclPermissioner;
    private $Auther;
    public function __construct(
        UserManager $UserManager,
        AclPermissioner $AclPermissioner,
        Auther $Auther
        )
    {
        $this->UserManager = $UserManager;
        $this->AclPermissioner = $AclPermissioner;
        $this->Auther= $Auther;
    }
    
    /**
    * 当程序启动时执行如下操作
    * 具体是在分发时，也就是加载页面之前
    * 完成超级用户设置
    * 完成用户验证
    * 
    * @param  
    * @return        
    */
    public function doInit(MvcEvent $e)
    {
        $this->initSuperUser();
        $this->checkPermission($e);
        //do futhure check
    }
    
    private function initSuperUser()
    {
        $this->UserManager->createSuperAdmin();
    }
    
    private function checkPermission(MvcEvent $e)
    {
        $Auther = $this->Auther;
        $Acl    = $this->AclPermissioner->getAcl();
        $routeMatch = $e->getRouteMatch();
        //默认角色
        $role       = UserManager::ROLE_GUEST;
        $controller = $routeMatch->getParam('controller');
        
        //先判断默认角色是否有权限
        if ($Acl->isAllowed($role, $controller)) {
            //do nothing
            return ;
        }
        //如果默认角色没有权限，则判断是否登录
        if (empty($Auther->isLogin())) {
            //go to login
            $routeMatch ->setParam('controller', AuthController::class)
                        ->setParam('action', 'loginPage');
            return ;
        }else {
            //已登录->获取角色->验证权限
            $identity = $Auther->getIdentity();
            $User = $this->UserManager->findUserByIdentity($identity);
            $role = $User->getRole();
            if ($Acl->isAllowed($role, $controller)) {
                //is alllowed
                //do nothing
                return ;
            }
            //is not allowed
            //go to no permission page
            $routeMatch ->setParam('controller', AuthController::class)
                        ->setParam('action', 'noPermissionPage');
            return ;
        }
    }
}
