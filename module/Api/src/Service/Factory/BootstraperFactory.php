<?php
namespace Api\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Service\UserManager;
use Api\Service\Bootstraper;
use Api\Service\AclPermissioner;
use Zend\Authentication\AuthenticationService;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class BootstraperFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $userManager = $container->get(UserManager::class);
        $AclPermissioner= $container->get(AclPermissioner::class);
        $AuthService = new AuthenticationService();
        return new Bootstraper($userManager, $AclPermissioner, $AuthService);
    }
}