<?php
/**
 * @link      http://github.com/zendframework/Web for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Api;

use Zend\Mvc\MvcEvent;
use Api\Service\Bootstraper;

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
        $app        = $e->getApplication();
        $evt        = $app->getEventManager();
        $container  = $app->getServiceManager();
        $this->container = $container;
        $log_debug  = $container->get('MyLoggerDebug');
        //关于错误信息处理
        //处理php原生错误信息
        $this->logPhpError($log_debug);
        //处理本框架错误信息
        $evt->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'logDispatchError'), 100);
        $evt->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'logDispatchError'), 100);
        
        //程序启动时
        $Bootstraper = $container->get(Bootstraper::class);
//         $evt->attach(MvcEvent::EVENT_DISPATCH, array($Bootstraper, 'doInit'), 100);
    }
    //将php原始的错误信息记录到debug中
    public function logPhpError($log_debug)
    {
        \Zend\Log\Logger::registerErrorHandler($log_debug);
        \Zend\Log\Logger::registerExceptionHandler($log_debug);
        \Zend\Log\Logger::registerFatalErrorShutdownFunction($log_debug);
    }
    
    //当发生404或500错误时,记录错误信息到error中
    public function logDispatchError(MvcEvent $e)
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
}
