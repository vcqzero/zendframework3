<?php
namespace Api\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Controller\TestController;
use Api\Service\UserManager;
use Zend\Session\SessionManager;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class TestControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $UserCache = $container->get('UserCache');
        $filesystem= $container->get('filesystem');
        
        // DEBUG INFORMATION START
        echo '------debug start------<br/>';
        echo "<pre>";
        var_dump(__METHOD__ . ' on line: ' . __LINE__);
        var_dump($UserCache);
        echo "</pre>";
        exit('------debug end------');
        // DEBUG INFORMATION END
        
        return new TestController(
            $container->get(UserManager::class),
            $container->get(SessionManager::class)
            );
    }
}