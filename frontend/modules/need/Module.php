<?php

namespace frontend\modules\need;

/**
 * need module definition class
 */
class Module extends \yii\base\Module
{
    
    public $layout = 'main';
    
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\need\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
