<?php

namespace frontend\modules\worksystem;

/**
 * worksystem module definition class
 */
class Module extends \yii\base\Module
{
    public $layout = 'worksystem';
    
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\worksystem\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
