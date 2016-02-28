<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=172.16.163.111 ;dbname=yii2advanced',
            'username' => 'wskeee',
            'password' => '1234',
            'charset' => 'utf8',
        ],
        'rmsdb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=172.16.163.111;dbname=rms',  //数据库连接&名称
            'username' => 'wskeee',     //用户名
            'password' => '1234',       //用户密码
            'charset' => 'utf8',        //数据库字符类型
            'tablePrefix' => 'rms_'   //加入前缀名称fc_
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
