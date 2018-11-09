<?php
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Filter\HtmlEntities;
use Zend\Filter\StringTrim;

return [
    'website_record'=>[
        'name'          => 'website_record',
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
    
    'ico_url'=>[
        'name'          => 'ico_url',
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
];
