<?php

namespace frontend\modules\scene;

/**
 * scene module definition class
 */
class Module extends \yii\base\Module
{
    public $layout = 'scene';
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\scene\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
