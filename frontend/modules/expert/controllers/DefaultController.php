<?php

namespace frontend\modules\expert\controllers;

use yii\web\Controller;
use \common\models\expert\Expert;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionSearch($u_id)
    {
        \Yii::$app->getResponse()->format = 'json';
        
        $expert = Expert::findOne($id);
        
        return [
            'result' => 0/1,
            'data'=>[
                'img' => $expert->personal_image,
                'phone' => $expert->user->phone,
                'email' => $expert->user->email,
            ]
        ];
    }
}
