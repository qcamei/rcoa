<?php

namespace common\wskeee\job\controllers;

use common\wskeee\job\JobManager;
use common\wskeee\job\models\Job;
use common\wskeee\job\models\JobNotification;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
    
    /**
     * 清除用户关联的所有通知
     */
    public function actionHasReady(){
        Yii::$app->getResponse()->format = 'json';
        $user = Yii::$app->user->id;
        $post = Yii::$app->getRequest()->post();
        $systemId = $post['systemId'];
        $this->getJobNotificatio($systemId, $user);
        return[
            'result' => 0,      //是否请求正常 0:为不正常请求
            'data' => [$systemId,$user],
        ];
    }
    
    /**
     * 在单击全部清除时
     * 只有状态为【未读】的时候才执行设置为【已读】
     * @param type $system_id   系统ID
     * @param type $user        当前用户
     */
    public function getJobNotificatio($system_id, $user) 
    {
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        $jobNotice = JobNotification::find()
                ->where(['u_id' => $user])
                ->with('u')
                ->with('job')
                ->with('jobs')
                ->all();
        $status = [];
        /* @var $value JobNotification */
        foreach ($jobNotice as $value) {
            $status[] = $value->status;
            if(in_array(JobNotification::STATUS_INIT, $status) && $value->u_id == $user)
                $jobManager->setNotificationHasReady($system_id, $user);
        }
        
    }
}
