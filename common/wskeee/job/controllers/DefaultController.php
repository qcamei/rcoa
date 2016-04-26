<?php

namespace common\wskeee\job\controllers;

use common\wskeee\job\JobManager;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionCreate()
    {
        $jobManager = new JobManager();
        /** 创建任务 */
        $jobManager->createJob('shoot', 11, '第一个任务', 'http://www.wskeee.com','待指派','10');
        
        $jobManager->addNotification('shoot', 11, 3);
        $jobManager->addNotification('shoot', 11, [6,4,5]);
        
        return $this->render('index');
    }
    
    public function actionUpdate()
    {
        $jobManager = new JobManager();
        /** 更新任务 */
        $jobManager->updateJob('shoot', 11, ['status'=>'摄影中']);
        
        return $this->render('index');
    }
    
    public function actionRemoveNotification()
    {
        /** @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        //修改job表任务
        $jobManager->updateJob(2,1295,['status' => '已取消']); 
        //修改通知
        $jobManager->cancelNotification(2, 1295,['117','12','13','74']);
       
        return $this->render('index');
    }
    
    public function actionAddNotification()
    {
        $jobManager = new JobManager();
        /** 添加 */
        
        return $this->render('index');
    }
}
