<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            //'dsn' => 'mysql:host=172.16.131.180;dbname=ccoa',     //公司服务器
            'dsn' => 'mysql:host=10.80.130.32;dbname=ccoa', //换阿里云服务器
            'username' => 'ccoa',
            'password' => 'ccoa0405',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            'tablePrefix' => 'ccoa_'   //加入前缀名称fc_
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.163.com',
                'username' => 'gzedu_reserve@163.com',
                'password' => '123456/q',
                'port' => '25',
                'encryption' => 'tls',
            ],
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['gzedu_reserve@163.com' => '资源中心工作平台']
            ],
            'useFileTransport' => false,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>s' => '<controller>/index',
            //'<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            ],
        ],
        'authManager' => [
            'class' => 'wskeee\rbac\RbacManager',
            'cache' => [
                'class' => 'yii\caching\FileCache',
                'cachePath' => dirname(dirname(__DIR__)) . '/frontend/runtime/cache'
            ]
        ],
        'fwManager' => [
            'class' => 'wskeee\framework\FrameworkManager',
            'url' => 'http://rcoaadmin.tt.gzedu.net/framework/api/list',
            'cache' => [
                'class' => 'yii\caching\FileCache',
            ]
        ],
        'jobManager' => [
            'class' => 'common\wskeee\job\JobManager',
            //'url'=>'http://rcoaadmin.tt.gzedu.net/framework/api/list',
            'cache' => [
                'class' => 'yii\caching\FileCache',
            ]
        ],
        'bookdetailTool' => [
            'class' => 'frontend\modules\shoot\BookdetailTool',
            //'url'=>'http://rcoaadmin.tt.gzedu.net/framework/api/list',
            'cache' => [
                'class' => 'yii\caching\FileCache',
            ]
        ],
        'bdNoticeTool' => [
            'class' => 'frontend\modules\shoot\BookdetailNoticeTool',
            //'url'=>'http://rcoaadmin.tt.gzedu.net/framework/api/list',
            'cache' => [
                'class' => 'yii\caching\FileCache',
            ]
        ],
        'fileManage' => [
            'class' => 'wskeee\filemanage\FileManageTool',
            //'url'=>'http://rcoaadmin.tt.gzedu.net/framework/api/list',
            'cache' => [
                'class' => 'yii\caching\FileCache',
            ]
        ],
    ],
    'modules' => [
        'rbac' => [
            'class' => 'wskeee\rbac\Module',
        ],
        'framework' => [
            'class' => 'wskeee\framework\Module'
        ],
        'job' => [
            'class' => 'common\wskeee\job\Module'
        ],
        'filemanage' => [
            'class' => 'common\wskeee\filemanage\Module',
        ],
        'webuploader' => [
            'class' => 'wskeee\webuploader\Module',
        ],
        'preview' => [
            'class' => 'common\modules\preview\Module',
        ],
    ],
    'as access' => [
        'class' => 'wskeee\rbac\components\AccessControl',
        'allowActions' => [
            'site/*',
            'gii/*',
            'debug/*',
            'webuploader/*',
            'preview/*',
        // The actions listed here will be allowed to everyone including guests.
        // So, 'admin/*' should not appear here in the production, of course.
        // But in the earlier stages of your development, you may probably want to
        // add a lot of actions here until you finally completed setting up rbac,
        // otherwise you may not even take a first step.
        ]
    ],
];
