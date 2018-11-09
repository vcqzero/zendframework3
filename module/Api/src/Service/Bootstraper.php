<?php
namespace Api\Service;

use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;
use Api\Controller\AuthController;

class Bootstraper
{
    private $UserManager;
    private $AclPermissioner;
    private $AuthenticationService;
    public function __construct(
        UserManager $UserManager,
        AclPermissioner $AclPermissioner,
        AuthenticationService $AuthenticationService
        )
    {
        $this->UserManager = $UserManager;
        $this->AclPermissioner = $AclPermissioner;
        $this->AuthenticationService = $AuthenticationService;
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
        $Auth   = $this->AuthenticationService;
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
        if (empty($Auth->hasIdentity())) {
            //go to login
            $routeMatch ->setParam('controller', AuthController::class)
                        ->setParam('action', 'loginPage');
            return ;
        }else {
            //已登录->获取角色->验证权限
            $identity = $Auth->getIdentity();
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
