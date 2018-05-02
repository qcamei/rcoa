<?php
    Yii::setAlias('common', dirname(__DIR__));
    Yii::setAlias('frontend', dirname(dirname(__DIR__)) . '/frontend');
    Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
    Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');
    Yii::setAlias('mconline', dirname(dirname(__DIR__)) . '/mconline');

    Yii::setAlias('wskeee', dirname(__DIR__) . '/wskeee');

    Yii::setAlias('filedata', dirname(dirname(__DIR__)) . '/frontend/web/filedata');
    
    defined('WEB_ROOT') or define('WEB_ROOT',defined('YII_ENV_TT') ? 'http://tt.ccoa.gzedu.net' :'http://ccoa.gzedu.net');
    defined('MCONLINE_WEB_ROOT') or define('MCONLINE_WEB_ROOT',defined('YII_ENV_TT') ? 'http://tt.mconline.gzedu.net' :'http://mconline.gzedu.net');
    defined('WEB_ADMIN_ROOT') or define('WEB_ADMIN_ROOT',defined('YII_ENV_TT') ? 'http://tt.ccoaadmin.gzedu.net' :'http://ccoaadmin.gzedu.net');