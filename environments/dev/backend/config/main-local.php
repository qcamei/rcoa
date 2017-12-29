<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
    'modules' => [
        'shoot_admin' => [
            'class' => 'backend\modules\shoot_admin\Module',
        ],
        'question_admin' => [
            'class' => 'backend\modules\question_admin\Module',
        ],
        'user_admin' => [
            'class' => 'backend\modules\user_admin\Module',
        ],
        'position_admin' => [
            'class' => 'backend\modules\position_admin\Module',
        ],
        'expert_admin' => [
            'class' => 'backend\modules\expert_admin\Module',
        ],
        'system_admin' => [
            'class' => 'backend\modules\system_admin\Module'
        ],
        /*
        'banner_admin' => [
            'class' => 'backend\modules\banner_admin\Module'
        ],
        'resource_admin' => [
            'class' => 'backend\modules\resource_admin\Module',
        ],
        'filemanage_admin' => [
            'class' => 'common\wskeee\filemanage_admin\Module',
        ],*/
        'teammanage_admin' => [
            'class' => 'backend\modules\team_admin\Module',
        ],
        'teamwork_admin' => [
            'class' => 'backend\modules\teamwork_admin\Module',
        ],
        'unittest_admin' => [
            'class' => 'backend\modules\unittest_admin\Module',
        ],
        /*
        'multimedia_admin' => [
            'class' => 'backend\modules\multimedia_admin\Module',
        ],
        'product_admin' => [
            'class' => 'backend\modules\product_admin\Module',
        ],*/
        'demand_admin' => [
            'class' => 'backend\modules\demand_admin\Module',
        ],
        'workitem_admin' => [
            'class' => 'backend\modules\workitem_admin\Module',
        ],
        'worksystem_admin' => [
            'class' => 'backend\modules\worksystem_admin\Module',
        ],
        'mconline_admin' => [
            'class' => 'backend\modules\mconline_admin\Module',
        ],
        'backup' => [
            'class' => 'spanjeta\modules\backup\Module',
        ],
        'helpcenter_admin' => [
            'class' => 'backend\modules\helpcenter_admin\Module',
        ],
        'scene_admin' => [
            'class' => 'backend\modules\scene_admin\Module',
        ],
    ],
    'as access' => [
        'allowActions' => [
            // The actions listed here will be allowed to everyone including guests.
            // So, 'admin/*' should not appear here in the production, of course.
            // But in the earlier stages of your development, you may probably want to
            // add a lot of actions here until you finally completed setting up rbac,
            // otherwise you may not even take a first step.
        ]
    ],
];

if (!YII_ENV_TEST) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
