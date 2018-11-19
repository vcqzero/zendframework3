<?php
namespace Api\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Api\Service\UserManager;
use Api\Filter\FormFilter;
use Api\Repository\MyTableGateway;
use Api\Repository\Table\User;

/**
 * This is the factory for IndexController. Its purpose is to instantiate the
 * controller and inject dependencies into it.
 */
class UserManagerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        //MyTableGateway
        $DbAdapter = $container->get('Zend\Db\Adapter\Adapter');
        $TableGateway = new MyTableGateway(User::TABLE_NAME, $DbAdapter);
        //FormFilter
        $rules      = include 'module/Api/src/Filter/rules/User.php';
        $FormFilter = new FormFilter($rules);
        //super admin config
        $config = $container->get('config');
        $super_user = $config['super_user'];
        return $UserManager = new UserManager(
            $FormFilter, $TableGateway, $super_user);
    }
}