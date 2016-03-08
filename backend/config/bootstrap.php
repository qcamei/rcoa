<?php
    Yii::setAlias('filedata', dirname(dirname(__DIR__)) . '/frontend/web/filedata');
    
    defined('FILEDATA_PATH') or define('FILEDATA_PATH', YII_DEBUG ? 'http://ccoa.tt.gzedu.net' : 'http://ccoa.gzedu.net');