<?php
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Filter\HtmlEntities;
use Zend\Filter\ToInt;
use Zend\Filter\StringTrim;
use Zend\Filter\StringToUpper;

return [
    'username'=>[
        'name' => 'username',
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
    
    'password'=>[
        'name' => 'password',
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
    
    'realname'=>[
        'name' => 'realname',
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
    
    'email'=>[
        'name' => 'email',
        'required' => false,
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
    
    'role'=>[
        'name' => 'role',
        'required' => false,
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
    
    'tel'=>[
        'name' => 'tel',
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
    
    'status'=>[
        'name' => 'status',
        //                 'required' => false,
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
                'name' => StringToUpper::class,//去掉首位空格
            ],
            
            [
                'name' => HtmlEntities::class,//html安全过滤
                'options' =>[
                    'quotestyle' => ENT_NOQUOTES,//保留单引号和双引号
                ],
            ],
        ],
    ],
    
    'avatar'=>[
        'name' => 'avatar',
        'required' => false,
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
