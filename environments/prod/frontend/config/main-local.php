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
            'class' => 'frontend\modules\shoot\Module',
        ],
        'demand' => [
            'class' => 'frontend\modules\demand\Module',
        ],
        /*
        'sites' => [
            'class' => 'frontend\modules\sites\Module',
        ],
        'resource' => [
            'class' => 'frontend\modules\resource\Module',
        ],
       'filemanage' => [
            'class' => 'common\wskeee\filemanage\Module',
        ],*/
        'expert' => [
            'class' => 'frontend\modules\expert\Module'
        ],
       'teamwork' => [
            'class' => 'frontend\modules\teamwork\Module',
        ],
        'datecontrol' =>  [
            'class' => '\kartik\datecontrol\Module',
        ],
        /*
        'multimedia' => [
            'class' => 'frontend\modules\multimedia\Module',
        ],*/
        'worksystem' => [
            'class' => 'frontend\modules\worksystem\Module',
        ],
        'scene' => [
            'class' => 'frontend\modules\scene\Module',
        ],
    ],
    'as access' => [
        'class' => 'wskeee\rbac\components\AccessControl',
        'allowActions' => [
            'framework/*',
            'demand/workitem/list',
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
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
