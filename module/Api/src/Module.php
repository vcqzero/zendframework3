<?php
/**
 * 系统启动入口文件
 * 全部module均指向本module内容
 */

namespace Api;

use Zend\Mvc\MvcEvent;
use Zend\Session\SessionManager;
use Api\Service\UserManager;
use Api\Service\Auther;
use Api\Service\AclPermissioner;
use Api\Controller\AuthController;
use Zend\Cache\PatternFactory;
use Zend\Session\Container;
use Api\Service\RoleManager;

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
        $this->initSuperUser($e);
        $this->initPermission($e);
    }
    
    private function initSuperUser(MvcEvent $e)
    {
        $app        = $e->getApplication();
        $evt        = $app->getEventManager();
        $container  = $app->getServiceManager();
        $UserManager= $container->get(UserManager::class);
        $evt->attach(MvcEvent::EVENT_DISPATCH, array($UserManager, 'createSuperUser'), 100);
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
        
        $this->emptyDebugFile();
        
        \Zend\Log\Logger::registerErrorHandler($log_debug);
        \Zend\Log\Logger::registerExceptionHandler($log_debug);
        \Zend\Log\Logger::registerFatalErrorShutdownFunction($log_debug);
        
        //处理本框架错误信息
        $evt->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'logFrameworkError'), 100);
        $evt->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'logFrameworkError'), 100);
        
    }
    
    private function emptyDebugFile()
    {
        $debug = 'log/debug.log';
        $maxsize = 1024 * 1024 * 2; //2M
        if(!file_exists($debug)) return ;
        if(filesize($debug) < $maxsize) return;
        fclose(fopen($debug, 'w'));//empty the debug
    }
    private function initSession(MvcEvent $e)
    {
        $ServerManager  = $e->getApplication()->getServiceManager();
        $SessionManager = $ServerManager->get(SessionManager::class);
        Container::setDefaultManager($SessionManager);
        $SessionManager->start();
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
        
        //通过缓存处理acl
        $AclPermission    = $container->get(AclPermissioner::class);
        $storage          = $container->get('main-cache');
        $AclCached = PatternFactory::factory('object', [
            'object'  => $AclPermission,
            'storage' => $storage,
            'object_key' => 'AclPermission',
            'cache_output' => false,
        ]);
        $Acl = $AclCached->getAclObject();
        
        //默认角色
        $role       = RoleManager::ROLE_GUEST;
        //请求的资源
        $controller = $routeMatch->getParam('controller');
        //请求的module
        $module = substr($controller, 0, strpos($controller, "\\"));
        
        $Auther = $container->get(Auther::class);
        //先判断默认角色是否有权限
        if ($Acl->isAllowed($role, $controller)) {
            //do nothing
            return ;
        }
        //如果默认角色没有权限，则判断是否登录
        if (empty($Auther->isLogin())) {
            //go to login
            $routeMatch ->setParam('controller', AuthController::class)
                        ->setParam('action', 'loginPage')
                        ->setParam('module', $module);
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
