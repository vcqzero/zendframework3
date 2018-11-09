<?php
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Filter\HtmlEntities;
use Zend\Filter\ToInt;
use Zend\Filter\StringTrim;

return [
    'name'=>[
        'name' => 'name',
        'required' => true,
        'validators' => [
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
    
    'workyard_id'=>[
        'name' => 'workyard_id',
        'required' => true,
        'validators' => [
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
                'name' => ToInt::class,//去掉首位空格
            ],
            
            [
                'name' => HtmlEntities::class,//html安全过滤
                'options' =>[
                    'quotestyle' => ENT_NOQUOTES,//保留单引号和双引号
                ],
            ],
        ],
    ],
    
    'qrcode_filename'=>[
        'name' => 'qrcode_filename',
        'required' => true,
        'validators' => [
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
                'name' => StringTrim::class,//去掉首位空格
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
        'name' => 'address',
        'required' => true,
        'validators' => [
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
                'name' => StringTrim::class,//去掉首位空格
            ],
            
            [
                'name' => HtmlEntities::class,//html安全过滤
                'options' =>[
                    'quotestyle' => ENT_NOQUOTES,//保留单引号和双引号
                ],
            ],
        ],
    ],
    
    'created_by'=>[
        'name' => 'created_by',
        'required' => true,
        'validators' => [
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
                'name' => StringTrim::class,//去掉首位空格
            ],
            
            [
                'name' => HtmlEntities::class,//html安全过滤
                'options' =>[
                    'quotestyle' => ENT_NOQUOTES,//保留单引号和双引号
                ],
            ],
        ],
    ],
    
    'created'=>[
        'name' => 'created',
        'required' => true,
        'validators' => [
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
                'name' => ToInt::class,//去掉首位空格
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
