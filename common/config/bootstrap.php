<?php
    Yii::setAlias('common', dirname(__DIR__));
    Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
    Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
    Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');

    Yii::setAlias('wskeee', dirname(__DIR__) . '/wskeee');

    Yii::setAlias('filedata', dirname(dirname(__DIR__)) . '/frontend/web/filedata');

    defined('FILEDATA_PATH') or define('FILEDATA_PATH','http://ccoa.gzedu.net');