<?php

namespace common\wskeee\job\controllers;

use common\wskeee\job\JobManager;
use Yii;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionCreate()
    {
       
        return $this->render('index');
    }
    
    public function actionUpdate()
    {
        
    }
    
   
    /**
     * 清除用户关联的所有通知
     */
    public function actionHasReady(){
        Yii::$app->getResponse()->format = 'json';
        $user = Yii::$app->user->id;
        $post = Yii::$app->getRequest()->post();
        $systemId =  $post['systemId'];
        /** @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        $jobManager->setNotificationHasReady($systemId,$user);
        return[
            'result' => 0,      //是否请求正常 0:为不正常请求
            'data' => [$systemId,$user],
        ];
    }
}
