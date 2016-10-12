<?php

namespace frontend\modules\multimedia\controllers;

use frontend\modules\multimedia\utils\MultimediaTool;
use yii\web\Controller;

class StatisticsController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index',[
            'multimedia'=> MultimediaTool::getInstance(),
        ]);
    }

}
