<?php

namespace frontend\modules\demand\controllers;

class BasedataController extends \yii\web\Controller
{
    /* 重构 layout */
    public $layout = 'basedata';
    
    public function actionIndex()
    {
        return $this->render('index');
    }

}
