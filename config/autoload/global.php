<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
use Zend\Session\Storage\SessionArrayStorage;
use Zend\Log\Logger;
use Zend\Cache\Storage\Adapter\Filesystem;

return [
    
    //配置mysql数据库 pdo
    'db' => array(
        'driver'         => 'Pdo',
        'dsn'            => 'mysql:dbname=zendframework3;host=127.0.0.1',
        'charset'        => 'UTF8',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
        ),
    ),
    
    //配置session
    'session_config' => [
        'name' => 'main-session-manager',
        'cookie_lifetime'     =>  7 * 24 * 60 * 60 , // 默认浏览器关闭时将客户端cookie设置为失效
        'gc_maxlifetime'      => 60 * 60 * 10, // How long to store session data on server (for 30 days).
    ],
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class
    ],
    'session_containers' => [
        'mySession',//定义session container 
    ],
    'session_manager' => [
        'validators' => [
//             RemoteAddr::class,
//             HttpUserAgent::class,
        ],
    ],
    
    'caches'=>[
        
        //将缓存内容保存到文件中
        'filesystem'=>[
            'adapter' => [
                'name'    => 'filesystem',
                'options' => [
                    'cache_dir' => './data/cache',
                    /**
                    * 失效判断原则：
                    * cache保存到文件中会带有创建时间
                    * 读取时根据这里的有效期和创建时间作比较
                    * 也就是不同cache根据业务逻辑应该有不同的有效期
                    * 这里默认设置为一天
                    */
                    'ttl' => 24 * 60 * 60,
                ],
            ],
            'plugins' => [
                // Don't throw exceptions on cache errors
                'exception_handler' => 
                [
                    'throw_exceptions' => false,
                ],
                // We store database rows on filesystem so we need to serialize them
                [
                    'name' => 'serializer',
                    'options' => [
                    ],
                ],
            ],
        ],
    ],
    
    //日志service
    //日志
    'log' => [
        //记录debug信息
        'MyLoggerDebug' => [
            'writers' => [
                [
                    'name' => 'stream',
                    'priority' => Logger::DEBUG,//调试或者错误级别使用此writer
                    'options' => [
                        'stream' => 'log/debug.log',
                        /* 'formatter' => [
                            'name' => 'MyFormatter',
                        ],
                        'filters' => [
                            [
                                'name' => 'MyFilter',
                            ],
                        ], */
                    ],
                ],
            ],
        ],
        //记录普通日志信息
        'MyLoggerInfo' => [
            'writers' => [
                [
                    'name' => 'stream',
                    'priority' => Logger::INFO,//调试或者错误级别使用此writer
                    'options' => [
                        'stream' => 'log/log.log',
                        /* 'formatter' => [
                            'name' => 'MyFormatter',
                        ],
                        'filters' => [
                            [
                                'name' => 'MyFilter',
                            ],
                        ], */
                    ],
                ],
            ],
        ],
    ],
];
