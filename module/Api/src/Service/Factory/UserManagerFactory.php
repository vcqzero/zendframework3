<?php
namespace Api\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Service\UserManager;
use Api\Filter\FormFilter;
use Api\Repository\PdoMysql;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class UserManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $PdoMysql = $container->get(PdoMysql::class);
        
        //FormFilter
        $FormFilter = $container->get(FormFilter::class);
        $FormFilter ->setRules(include 'module/Api/src/Filter/rules/User.php');
        
        //super admin config
        $config = $container->get('config');
        $super_admin_config = $config['super_admin'];
        $UserManager = new UserManager(
            $PdoMysql,
            $FormFilter,
            $super_admin_config
            );
        
        return  $UserManager;
    }
}