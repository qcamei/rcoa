<?php
return [
    'timeZone' => 'PRC',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=172.16.163.111;dbname=ccoa',
            'username' => 'wskeee',
            'password' => '1234',
            'charset' => 'utf8',
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
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
            ],
        ],
        'authManager'=>[
            'class'=>'wskeee\rbac\RbacManager',
            'cache' => [
                'class' => 'yii\caching\FileCache',
                'cachePath' => dirname(dirname(__DIR__)) . '/frontend/runtime/cache'
            ]
        ],
        'fwManager'=>[
            'class'=>'wskeee\framework\FrameworkManager',
            'url'=>'http://rcoaadmin.tt.gzedu.net/framework/api/list',
            'cache' => [
                'class' => 'yii\caching\FileCache',
            ]
        ],
        'jobManager'=>[
            'class'=>'common\wskeee\job\JobManager',
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
        'expert' => [
            'class' => 'frontend\modules\expert\Module'
        ],
        'resource' => [
            'class' => 'frontend\modules\resource\Module',
        ],
    ],
];
