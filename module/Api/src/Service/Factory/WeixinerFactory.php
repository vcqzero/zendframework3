<?php
namespace Api\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Service\Weixiner;
use Api\Service\WebsiteManager;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class WeixinerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config     =include WebsiteManager::PATH_API_CONFIG;
        $config     = $config[WebsiteManager::API_WEIXIN];
        $cache      = $container->get('main-cache');
        return new Weixiner($cache, $config);
    }
}