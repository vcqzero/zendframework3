<?php
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Filter\HtmlEntities;
use Zend\Filter\ToInt;
use Zend\Filter\StringTrim;

return [
    'shift_type_name'=>[
        'name' => 'shift_type_name',
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
    'times'=>[
        'name' => 'times',
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
    
    'start_time'=>[
        'name' => 'start_time',
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
    
    'end_time'=>[
        'name' => 'end_time',
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
    
    'note'=>[
        'name' => 'note',
        'required' => true,
        'validators' => [
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
    
];
