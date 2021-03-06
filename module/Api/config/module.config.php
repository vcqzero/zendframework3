<?php
namespace Api;

use Zend\Router\Http\Segment;
use Api\Service\UserManager;
use Zend\ServiceManager\Factory\InvokableFactory;
use Api\Service\RoleManager;
// use Zend\Router\Http\Literal;
// use Zend\Router\Http\Hostname;

return [
    'router' => [
        'routes' => [
//             'guard.tanhansi.com' => [
//                 'type' => Hostname::class,
//                 'options' => [
//                     'route' => ':subdomain.tanhansi.com',
//                     'constraints' => [
//                         /**
//                         * 声明子域名部分
//                         * 只有子域名不同，则路由就会严格按照域名进行匹配
//                         * 
//                         * 举例：
//                         * admin.xxx.com/home 和 custom.xxx.com/home 进入的是不同控制器
//                         */
//                         'subdomain' => 'guard',
//                     ],
//                     'defaults' => [
//                     ],
//                 ],
                
//                 'child_routes'=>[
//                     'home' => [
//                         'type'    => Literal::class,
//                         'options' => [
//                             'route'    => '/',
//                             'defaults' => [
//                                 'controller' => Controller\AuthController::class,
//                                 'action'     => 'index',
//                             ],
//                         ],
//                     ],
//                  ],
//              ],
            
            //进行登录验证
            'api/test' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/test[/:action]',
                    'defaults' => [
                        'controller' => Controller\TestController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            //进行登录验证
            'api/auth' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/auth[/:action]',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            'api/user' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/user[/:action][/:userID]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'index',
                        'userId'     => '0',
                    ],
                ],
            ],
            
            //account
            'api/account' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/account[/:action][/:id]',
                    'defaults' => [
                        'controller' => Controller\AccountController::class,
                        'action'     => 'index',
                        'id'         => '0',
                    ],
                ],
            ],
            
            'api/website' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/website[/:action]',
                    'constraints' => [//设置router规则
                        'action' => '[a-zA-Z][a-zA-Z0-9]*',//字母和数字组成 字母开头
                    ],
                    'defaults' => [
                        'controller' => Controller\WebsiteController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            //值班班次
            'api/weixin' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/api/weixin[/:action][/:shiftID]',
                    'defaults' => [
                        'controller' => Controller\WeixinController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
        ],
    ],
    
    'controllers' => [
        'factories' => [
            Controller\TestController::class => Controller\Factory\TestControllerFactory::class,
            Controller\UserController::class => Controller\Factory\UserControllerFactory::class,
            Controller\AccountController::class => Controller\Factory\AccountControllerFactory::class,
            Controller\AuthController::class => Controller\Factory\AuthControllerFactory::class,
            Controller\WebsiteController::class => Controller\Factory\WebsiteControllerFactory::class,
            Controller\WeixinController::class => Controller\Factory\WeixinControllerFactory::class,
        ],
    ],
    'permission' => [
        Controller\TestController::class => [
            'allow'=> [
                RoleManager::ROLE_GUEST,
            ],
        ],
        Controller\UserController::class => [
            'allow'=> [
                RoleManager::ROLE_SUPER_USER,
            ],
        ],
        Controller\AccountController::class => [
            'allow'=> [
                RoleManager::ROLE_SUPER_USER,
            ],
        ],
        Controller\AuthController::class => [
            'allow'=> [
                RoleManager::ROLE_SUPER_USER,
                RoleManager::ROLE_GUEST,
            ],
        ],
        Controller\WebsiteController::class => [
            'allow'=> [
                RoleManager::ROLE_SUPER_USER,
            ],
        ],
        Controller\WeixinController::class => [
            'allow'=> [
                RoleManager::ROLE_SUPER_USER,
            ],
        ],
    ],
    
    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\TokenPlugin::class  => Controller\Plugin\Factory\TokenPluginFactory::class,
        ],
        
        'aliases' => [
            'Token'      => Controller\Plugin\TokenPlugin::class,
        ],
    ],
    //service_manager
    'service_manager' => [
        'factories' => [
            //不可删除AuthenticationService
            \Zend\Authentication\AuthenticationService::class => InvokableFactory::class,
            //Controller\Plugin
            Controller\Plugin\TokenPlugin::class => Controller\Plugin\Factory\TokenPluginFactory::class,
            //Server
            Service\Weixiner::class   => Service\Factory\WeixinerFactory::class,
            Service\AclPermissioner::class => Service\Factory\AclPermissionerFactory::class,
            Service\Auther::class => Service\Factory\AutherFactory::class,
            //TableManager
            Service\RoleManager::class      => InvokableFactory::class,
            Service\UserManager::class      => Service\Factory\UserManagerFactory::class,
            Service\WebsiteManager::class   => Service\Factory\WebsiteManagerFactory::class,
            Service\TestManager::class   => InvokableFactory::class,
        ],
        
        'shared' => [
            Filter\FormFilter::class     =>false,
        ]
    ],
    
    'view_helpers' => [
        'factories' => [
            View\Helper\RoleHelper::class => View\Helper\Factory\RoleHelperFactory::class,
            View\Helper\UserHelper::class => View\Helper\Factory\UserHelperFactory::class,
            View\Helper\WebsiteHelper::class => View\Helper\Factory\WebsiteHelperFactory::class,
            View\Helper\TokenHelper::class => View\Helper\Factory\TokenHelperFactory::class,
        ],
        
        'aliases' => [
            'Role'      => View\Helper\RoleHelper::class,
            'User'      => View\Helper\UserHelper::class,
            'Website'   => View\Helper\WebsiteHelper::class,
            'Token'     => View\Helper\TokenHelper::class,
        ],
    ],
    
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        
        'template_path_stack' => [
            'api' => __DIR__ . '/../view',
        ],
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ],
];

