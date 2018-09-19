<?php
// use \aabc\web\Request;
// $baseUrl = str_replace('/frontend/web', '', (new Request)->getBaseUrl());
use common\cont\D;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

$config_main = array_merge(
    require(ROOT_PATH . '/frontend/modules/'.temp.'/config/main.php')
);



return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',  
    
    'layoutPath' => '@app/views/template/'.temp,

    'modules' => [
        'kehoach' => [                
            'class' => 'frontend\modules\kehoach\Kehoach',                
        ],
        temp => [                
            'class' => 'frontend\modules\\'.temp.'\\'.Temp,                
            'layoutPath' => '@app/modules/'.temp.'/views',
            'defaultRoute' => 'site',
        ],        
    ],

    'components' => [
        'view' => [
            'theme' => [
                'pathMap' => [
                    '@frontend/views'=>'@frontend/views/template/'.temp.'/',
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
            'csrfParam' => 'frsc',
        ],


        'user' => [
            'identityClass' => 'frontend\models\User',
            'enableAutoLogin' => false,
            'enableSession' => true,
            'authTimeout' => 60,  
            'identityCookie' => ['name' => '_tk_fr', 'httpOnly' => true],
            'loginUrl' => $config_main['loginUrl'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'ss-fr',
            'class' => 'aabc\web\Session',
            'cookieParams' => ['httponly' => true, 'lifetime' => 60],
            'timeout' => 60, //session expire
            'useCookies' => true,
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
            // 'suffix' => '.html',
            'rules' => $config_main['router'],
            // 'rules' => [         
                
                // [
                //     // 'class' => 'frontend\modules\\'.temp.'\\config\\main', 
                // ]
                // [
                //     'pattern' => '',
                //     'route' => temp,
                //     // 'defaults' => [/*'type' => 1*/],
                //     // 'suffix' => '.html',
                // ],

                // [
                //     'pattern' => 'san-pham', //san-pham.html
                //     'route' => temp.'/sanpham',
                //     'defaults' => [/*'type' => 1*/],
                //     // 'suffix' => '.html',
                // ],


                // // [
                // //     'pattern' => 'danh-muc', //san-pham.html
                // //     'route' => temp.'/danhmuc',
                // //     'defaults' => [/*'type' => 1*/],
                // //     // 'suffix' => '.html',
                // // ],


                // [
                //     'pattern' => '<slug:[A-Za-z0-9 -_.]+>-'.D::url_sp.'<id:\d+>',
                //     'route' => 'sanpham/view',
                //     'defaults' => [/*'type' => 1*/],
                //     // 'suffix' => '.html',
                // ],

                // [
                //     'pattern' => '<slug:[A-Za-z0-9 -_.]+>-'.D::url_dm.'<id:\d+>',
                //     'route' => 'danhmuc/view',
                //     'defaults' => [/*'type' => 1*/],
                //     // 'suffix' => '.html',
                // ],


                // [
                //     'pattern' => '<slug:[A-Za-z0-9 -_.]+>-'.D::url_bv.'<id:\d+>',
                //     'route' => 'baiviet/view',
                //     'defaults' => [/*'type' => 2*/],
                //     // 'suffix' => '.html',
                // ],
                // [
                //     'pattern' => '<slug:[A-Za-z0-9 -_.]+>-'.D::url_cm.'<id:\d+>',
                //     'route' => 'chuyenmuc/view',
                //     'defaults' => [/*'type' => 2*/],
                //     // 'suffix' => '.html',
                // ],



                // // '/<controller:\w+>' => '/<controller>',
                // // '/<controller:\w+>/<action:\w+>' => '/<controller>/<action>',
                // // '/sp' => '/<controller>/<action>',
                // //  [
                // //     'pattern' => '<k>/<f>',
                // //     'route' => 'site/i',
                // //     'defaults' => ['k' => '', 'f' => ''],
                // // ],
            // ],
        ],
        
    ],
    'params' => $params,
];
