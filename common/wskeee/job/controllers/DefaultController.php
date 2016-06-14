<?php

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

namespace common\wskeee\job\controllers;

class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            //验证delete时为post传值
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            //access验证是否有登录
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }
    
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
        return $this->render('index');
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
