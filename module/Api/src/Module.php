<?php
/**
 * @link      http://github.com/zendframework/Web for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Api;

use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;
use Api\Service\UserManager;
use Api\Service\Auther;
use Api\Service\AclPermissioner;
use Api\Controller\AuthController;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
    
    /**
    * 当系统启动时首先运行onBootstrap程序
    * 在其他module中不要定义onBootstrap程序了。
    * 
    * @param  
    * @return        
    */
    public function onBootstrap(MvcEvent $e)
    {
        $this->initHandleError($e);
        $this->initSession($e);
        $this->initMkdir($e);
//         $this->initUser($e);
        $this->initPermission($e);
    }
    
    private function initUser(MvcEvent $e)
    {
        $app        = $e->getApplication();
        $evt        = $app->getEventManager();
        $container  = $app->getServiceManager();
        $UserManager= $container->get(UserManager::class);
        $UserManager->createSuperAdmin();
    }
    private function initPermission(MvcEvent $e)
    {
        $app        = $e->getApplication();
        $evt        = $app->getEventManager();
        $container  = $app->getServiceManager();
        $evt->attach(MvcEvent::EVENT_DISPATCH, array($this, 'checkPermission'), 100);
    }
    private function initHandleError(MvcEvent $e)
    {
        $app        = $e->getApplication();
        $container  = $app->getServiceManager();
        $log_debug  = $container->get('MyLoggerDebug');
        $evt        = $app->getEventManager();
        
        \Zend\Log\Logger::registerErrorHandler($log_debug);
        \Zend\Log\Logger::registerExceptionHandler($log_debug);
        \Zend\Log\Logger::registerFatalErrorShutdownFunction($log_debug);
        
        //处理本框架错误信息
        $evt->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'logFrameworkError'), 100);
        $evt->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'logFrameworkError'), 100);
    }
    private function initSession(MvcEvent $e)
    {
        $ServerManager  = $e->getApplication()->getServiceManager();
        $sessionManager = $ServerManager->get(SessionManager::class);
        $sessionManager->start();
    }
    private function initMkdir(MvcEvent $e)
    {
        $dirs = [
            'data/cache',
        ];
        foreach ($dirs as $dir)
        {
            
            if (!file_exists($dir))
            {
                mkdir($dir);
            }
        }
    }
    //当发生404或500错误时,记录错误信息到error中
    public function logFrameworkError(MvcEvent $e)
    {
        $exception      = $e->getParam('exception');
        //不记录404级别错误
        if ($exception != null)
        {
            $exceptionName = $exception->getMessage();
            $file       = $exception->getFile();
            $line       = $exception->getLine();
            $stackTrace = $exception->getTraceAsString();
            
            $errorMessage   = $e->getError();
            $controllerName = $e->getController();
            
            $debug_mess = "An error occurred \r\n";
            $debug_mess = $debug_mess . "File:\r\n" . " $file : on line $line \r\n";
            $debug_mess = $debug_mess . "Message:\r\n" . $exceptionName . "\r\n";
            $debug_mess = $debug_mess . "Stack trace: \r\n" . $stackTrace;
            
            $log_debug  = $e->getApplication()->getServiceManager()->get('MyLoggerDebug');
            $log_debug  ->log(\Zend\Log\Logger::DEBUG, $debug_mess);
            $log_debug  ->log(\Zend\Log\Logger::DEBUG, "-------end--------\r\n");
        }
        //当发生错误时，直接显示404或500错误页面，不用layout
        $vm = $e->getViewModel();
        $vm->setTemplate('layout/blank');
    }
    
    public function checkPermission(MvcEvent $e)
    {
        $app        = $e->getApplication();
        $routeMatch = $e->getRouteMatch();
        $container  = $app->getServiceManager();
        
        $Auther = $container->get(Auther::class);
        $AclPermission    = $container->get(AclPermissioner::class);
        $Acl              = $AclPermission->getAcl();
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
            $role= $Auther->getRole();
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
