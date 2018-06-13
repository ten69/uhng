<?php
// use \aabc\web\Request;
// $baseUrl = str_replace('/frontend/web', '', (new Request)->getBaseUrl());
use common\cont\D;
$template = temp; //Template

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',  
    
    'layoutPath' => '@app/views/template/'.$template,

    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@frontend/views'=>'@frontend/views/template/'.$template.'/',
                ],
            ],
        ],



        '_model' => [
           'class' => 'common\components\_model',
        ],
        '_sanphamngonngu' => [
            'class' => 'common\components\_sanphamngonngu',
        ],

        '_danhmuc' => [
            'class' => 'common\components\_danhmuc',
        ],
        '_sanphamdanhmuc' => [
            'class' => 'common\components\_sanphamdanhmuc',
        ],


        '_chinhsach' => [
            'class' => 'common\components\_chinhsach',
        ],


        '_sanphamchinhsach' => [
            'class' => 'common\components\_sanphamchinhsach',
        ],

        '_danhmucchinhsach' => [
            'class' => 'common\components\_danhmucchinhsach',
        ],

        
        '_image' => [
            'class' => 'common\components\_image',
        ],



        'frontCache' => [
            'class' => 'aabc\caching\FileCache',
            'cachePath' => '/path/to/frontend/runtime/cache'
        ],

         'settings' => [
            'class' => 'common\components\Settings'
        ],

        'request' => [
            'baseUrl' => '',
            'csrfParam' => '_csrf-frontend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
        ],
        'log' => [
            'traceLevel' => AABC_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'aabc\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        
        'urlManager' => [
            'baseUrl' => '',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'suffix' => '.html',
            'rules' => [                
                // 'p-<id:\d+>.html' => 'sanpham/view',
                // 'd-<id:\d+>.html' => 'danhmuc/view',

                [
                    'pattern' => '<slug:[A-Za-z0-9 -_.]+>-'.D::url_sp.'<id:\d+>',
                    'route' => 'sanpham/view',
                    'defaults' => [/*'type' => 1*/],
                    // 'suffix' => '.html',
                ],
                [
                    'pattern' => '<slug:[A-Za-z0-9 -_.]+>-'.D::url_dm.'<id:\d+>',
                    'route' => 'danhmuc/view',
                    'defaults' => [/*'type' => 1*/],
                    // 'suffix' => '.html',
                ],


                [
                    'pattern' => '<slug:[A-Za-z0-9 -_.]+>-'.D::url_bv.'<id:\d+>',
                    'route' => 'baiviet/view',
                    'defaults' => [/*'type' => 2*/],
                    // 'suffix' => '.html',
                ],
                [
                    'pattern' => '<slug:[A-Za-z0-9 -_.]+>-'.D::url_cm.'<id:\d+>',
                    'route' => 'chuyenmuc/view',
                    'defaults' => [/*'type' => 2*/],
                    // 'suffix' => '.html',
                ],



                // '/<controller:\w+>' => '/<controller>',
                // '/<controller:\w+>/<action:\w+>' => '/<controller>/<action>',
                // '/sp' => '/<controller>/<action>',
                //  [
                //     'pattern' => '<k>/<f>',
                //     'route' => 'site/i',
                //     'defaults' => ['k' => '', 'f' => ''],
                // ],
            ],
        ],
        
    ],
    'params' => $params,
];
