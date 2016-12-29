<?php
    Yii::setAlias('common', dirname(__DIR__));
    Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
    Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
    Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');

    Yii::setAlias('wskeee', dirname(__DIR__) . '/wskeee');

    Yii::setAlias('filedata', dirname(dirname(__DIR__)) . '/frontend/web/filedata');
    
    defined('FRONTEND_DIR') or define('FRONTEND_DIR',dirname(dirname(__DIR__)) . '/frontend');
    defined('WEB_ROOT') or define('WEB_ROOT',defined('YII_ENV_TT') ? 'http://tt.ccoa.gzedu.net' :'http://ccoa.gzedu.net');
    defined('WEB_ADMIN_ROOT') or define('WEB_ADMIN_ROOT',defined('YII_ENV_TT') ? 'http://tt.ccoaadmin.gzedu.net' :'http://ccoaadmin.gzedu.net');