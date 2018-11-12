<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;
use Api\Service\UserManager;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            'auth' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/auth[/:action]',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            'user' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/user[/:action]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
            'website' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/website[/:action]',
                    'defaults' => [
                        'controller' => Controller\WebsiteController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
            Controller\AuthController::class  => InvokableFactory::class,
            Controller\UserController::class  => InvokableFactory::class,
            Controller\WebsiteController::class  => InvokableFactory::class,
        ],
    ],
    
    'permission'=> [
        Controller\IndexController::class => [
            'allow' => [
                UserManager::ROLE_GUEST,
            ]
        ],
        Controller\AuthController::class => [
            'allow' => [
                UserManager::ROLE_GUEST,
            ]
        ],
        Controller\UserController::class => [
            'allow' => [
                UserManager::ROLE_GUEST,
            ]
        ],
        Controller\WebsiteController::class => [
            'allow' => [
                UserManager::ROLE_GUEST,
            ]
        ],
    ],
    
    'view_helpers' => [
        'factories' => [
            View\Helper\MenuHelper::class => InvokableFactory::class,
        ],
        
        'aliases' => [
            'Menu' => View\Helper\MenuHelper::class,
        ],
    ],
    
    'view_manager' => [
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/application/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
