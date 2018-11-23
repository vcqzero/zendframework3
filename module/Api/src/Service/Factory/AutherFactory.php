<?php
namespace Api\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Authentication\Adapter\DbTable\CallbackCheckAdapter;
use Api\Service\Auther;
use Zend\Session\SessionManager;
use Api\Service\UserManager;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class AutherFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $dbAdapter      = $container->get(Adapter::class);
        $SessionManager = $container->get(SessionManager::class);
        $UserManager    = $container->get(UserManager::class);
        return new Auther(
            new CallbackCheckAdapter($dbAdapter),
            $SessionManager,
            $UserManager
            );
    }
}