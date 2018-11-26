<?php
namespace Api\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Controller\IndexController;
use Api\Controller\WebsiteController;
use Api\Service\WebsiteManager;
use Api\Service\Weixiner;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class WebsiteControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        return new WebsiteController(
            $container->get(WebsiteManager::class),
            $container->get(Weixiner::class)
           );
    }
}