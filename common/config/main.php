<?php
return [
    'timeZone' => 'PRC',
    'language' => 'zh-CN',
    'name' => '工匠',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'i18n' => [
            'translations' => [
                'rcoa*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'fileMap' => [
                        'rcoa' => 'rcoa.php',
                        'rcoa/system' => 'system.php',
                        'rcoa/banner' => 'banner.php',
                        'rcoa/resource' => 'resource.php',
                        'rcoa/fileManage' => 'fileManage.php',
                        'rcoa/team' => 'team.php',
                        'rcoa/framework' => 'framework.php',
                        'rcoa/teamwork' => 'teamwork.php',
                        'rcoa/position' => 'position.php',
                        'rcoa/multimedia' => 'multimedia.php',
                        'rcoa/demand' => 'demand.php',
                        'rcoa/basedata' => 'basedata.php',
                        'rcoa/product' => 'product.php',
                        'rcoa/workitem' => 'workitem.php',
                        'rcoa/worksystem' => 'worksystem.php',
                        'rcoa/rbac' => 'rbac.php',
                        'rcoa/mconline' => 'mconline.php',
                    ],
                ],
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'fileMap' => [
                        'app' => 'rcoa.php',
                        'app/rbac' => 'rbac.php',
                    ],
                ],
               
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                ],
            ],
        ],
    ],
];
