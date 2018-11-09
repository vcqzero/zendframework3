<?php
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Filter\HtmlEntities;
use Zend\Filter\StringTrim;

return [
    'name'=>[
        'name'          => 'name',
        'required'      => true,
        'validators'    => [
            [
                'name' => NotEmpty::class,
            ],
            [
                'name' => StringLength::class,
                'options' => [
                    'min' => 1,
                    'max' => 128,
                ],
            ],
        ],
        
        'filters' => [
            [
                'name' => \Zend\Filter\StringTrim::class,//去掉首位空格
            ],
            
            [
                'name' => HtmlEntities::class,//html安全过滤
                'options' =>[
                    'quotestyle' => ENT_NOQUOTES,//保留单引号和双引号
                ],
            ],
        ],
    ],
    
    'note'=>[
        'name'          => 'note',
//         'required'      => true,
        'validators'    => [
            [
                'name' => NotEmpty::class,
            ],
            [
                'name' => StringLength::class,
                'options' => [
                    'min' => 1,
                    'max' => 512,
                ],
            ],
        ],
        
        'filters' => [
            [
                'name' => \Zend\Filter\StringTrim::class,//去掉首位空格
            ],
            
            [
                'name' => HtmlEntities::class,//html安全过滤
                'options' =>[
                    'quotestyle' => ENT_NOQUOTES,//保留单引号和双引号
                ],
            ],
        ],
    ],
    
    'address_path'=>[
        'name'          => 'address_path',
        'required'      => true,
        'validators'    => [
            [
                'name' => NotEmpty::class,
            ],
            [
                'name' => StringLength::class,
                'options' => [
                    'min' => 1,
                    'max' => 1024,
                ],
            ],
        ],
        
        'filters' => [
            [
                'name' => \Zend\Filter\StringTrim::class,//去掉首位空格
            ],
            
            [
                'name' => HtmlEntities::class,//html安全过滤
                'options' =>[
                    'quotestyle' => ENT_NOQUOTES,//保留单引号和双引号
                ],
            ],
        ],
    ],
    
    'address'=>[
        'name'          => 'address',
        'required'      => true,
        'validators'    => [
            [
                'name' => NotEmpty::class,
            ],
            [
                'name' => StringLength::class,
                'options' => [
                    'min' => 1,
                    'max' => 256,
                ],
            ],
        ],
        
        'filters' => [
            [
                'name' => \Zend\Filter\StringTrim::class,//去掉首位空格
            ],
            
            [
                'name' => HtmlEntities::class,//html安全过滤
                'options' =>[
                    'quotestyle' => ENT_NOQUOTES,//保留单引号和双引号
                ],
            ],
        ],
    ],
];
