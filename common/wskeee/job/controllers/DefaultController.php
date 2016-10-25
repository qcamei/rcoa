<?php

namespace common\wskeee\job\controllers;

use common\wskeee\job\JobManager;
use common\wskeee\job\models\JobNotification;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

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
        
        $systemId = ArrayHelper::getValue($post, 'systemId');
        $this->getJobNotificatio($user);
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
    public function getJobNotificatio($user) 
    {
        $jobNotice = (new Query())->select(['job_id'])
                ->from(JobNotification::tableName())
                ->where(['u_id' => $user, 'status' => JobNotification::STATUS_INIT])
                ->column(Yii::$app->db);
        Yii::$app->db->createCommand()
                ->update(JobNotification::tableName(), ['status'=>  JobNotification::STATUS_NORMAL], [
                    'job_id' => $jobNotice])
                ->execute();
    }
}
