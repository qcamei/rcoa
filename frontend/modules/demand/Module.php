<?php

namespace frontend\modules\demand;

/**
 * demand module definition class
 */
class Module extends \yii\base\Module
{
    public $layout = 'demand';
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\demand\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
