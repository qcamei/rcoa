<?php

$config = [
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
    ],
    'modules' => [
        'shoot' => [
            'class' => 'backend\modules\shoot\Module',
        ],
        'question' => [
            'class' => 'backend\modules\question\Module',
        ],
        'user' => [
            'class' => 'backend\modules\user\Module',
        ],
        'position' => [
            'class' => 'backend\modules\position\Module',
        ],
        'expert' => [
            'class' => 'backend\modules\expert\Module',
        ],
        'news' => [
            'class' => 'backend\modules\news\Module'
        ],
        'banner' => [
            'class' => 'backend\modules\banner\Module'
        ],
        'resource' => [
            'class' => 'backend\modules\resource\Module',
        ],
        'filemanage' => [
            'class' => 'common\wskeee\filemanage\Module',
        ],
        'teammanage' => [
            'class' => 'backend\modules\team\Module',
        ],
        'teamwork' => [
            'class' => 'backend\modules\teamwork\Module',
        ],
        'unittest' => [
            'class' => 'backend\modules\unittest\Module',
        ],
        'multimedia' => [
            'class' => 'backend\modules\multimedia\Module',
        ],
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
