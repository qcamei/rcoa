<?php

namespace frontend\modules\multimedia\controllers;

class StatisticsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index',[
            'multimedia'=>\Yii::$app->get('multimedia')
        ]);
    }

}
