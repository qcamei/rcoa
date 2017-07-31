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
        'expert' => [
            'class' => 'frontend\modules\expert\Module'
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
