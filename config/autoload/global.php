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
use Zend\Session\Validator\RemoteAddr;
use Zend\Session\Validator\HttpUserAgent;

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
    
    /**
     * session
     * 系统需要保证使用唯一的session管理
     * 默认设置：
     * session回收机制默认，一般是用户失去响应2小时之后回收
     * cookie失效机制：默认关闭浏览器之后失效，即使cookie设置长时间失效，但是因为session失效和cookie失效不同所以
     * 
     * WARNING
     * 因为session cookie name代表着session
     * 本系统内其他组件用到session服务时，如果未指定session manager就会自动
     * 执行session start 关键这个时候回更改session cookie name也就是将 
     * 原来的session cookie name失效
     * 是一个非常严重的问题，
     * 解决方法：
     * 1、不设置名称
     * 或者
     * 2、在启动时，默认将此session manager设置为全局唯一的
     * 
     * 
     * ***********保护session安全性必须使用https协议***************
     */
    'session_config' => [
        'cookie_httponly' => true,//js不可读取cookie
        'name' => 'main-session-cookie',//session cookie name
        'gc_maxlifetime'      => 60 * 60 * 24 * 10, // 最长保存10天
        'cookie_lifetime'     => 0,//cookie在浏览器保存时间，默认为关闭浏览器时清除cookie
    ],
    
    // Session storage configuration.
    'session_storage' => [
        'type' => SessionArrayStorage::class//这也是zend默认值
    ],
    'session_containers' => [
        'mySession',//定义session container 
        'TokenSession',//use for token in form
        
        //anothe cantainer goes here 
        //like namespace
        //but controlle by one manager
    ],
    'session_manager' => [
        'validators' => [
            RemoteAddr::class,//验证user ip
            HttpUserAgent::class,//验证用户两次发生的sessionid来自同一user agent信息
        ],
    ],
    
    'caches'=>[
        
        //将缓存内容保存到文件中
        'main-cache'=>[
            'adapter' => [
                'name'    => 'filesystem',
                'options' => [
                    'cache_dir' => './data/cache',
                    /**
                    * 失效判断原则：
                    * cache保存到文件中会带有创建时间
                    */
                    'ttl' => 24 * 60 * 60 * 30,
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
