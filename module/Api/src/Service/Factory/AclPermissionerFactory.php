<?php
namespace Api\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Service\AclPermissioner;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class AclPermissionerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $resources  = $container->get('config')['controllers']['factories'];
        $permission = $container->get('config')['permission'];
        return new AclPermissioner($resources, $permission);
    }
}