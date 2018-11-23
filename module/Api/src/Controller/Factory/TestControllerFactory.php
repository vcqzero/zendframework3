<?php
namespace Api\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Controller\TestController;
use Api\Service\Auther;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class TestControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
//         $UserCache = $container->get('UserCache');
//         $filesystem= $container->get('main-cache');
        $log_debug  = $container->get('MyLoggerDebug');
        return new TestController(
            $log_debug
            );
    }
}