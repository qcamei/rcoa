<?php
return [
    'bootstrap' => ['gii'],
    'modules' => [
        'gii' => 'yii\gii\Module',
    ],
    'as access' => [
        'class' => 'console\components\AccessControl',
    ],
];
