<?php
namespace frontend\modules\multimedia\utils;

use common\config\AppGlobalVariables;
use common\models\multimedia\MultimediaAssignTeam;
use common\models\multimedia\MultimediaProducer;
use common\models\multimedia\MultimediaTask;
use common\wskeee\job\JobManager;
use wskeee\ee\EeManager;
use Yii;
use yii\helpers\ArrayHelper;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class MultimediaNoticeTool {
   
    private static $instance = null;
    
    /**
     * 获取团队指派人
     * @param integer|array $teamId        团队Id
     * @return type
     */
    public function getAssignPerson($teamId)
    {
        /* @var $assignPerson MultimediaAssignTeam */
        $assignPerson = MultimediaAssignTeam::find()
                        ->where(['team_id' => $teamId])
                        ->with('assignUser')
                        ->all();
        
        $assignUser = [
            'u_id' => ArrayHelper::getColumn($assignPerson, 'u_id'),
            'ee' => ArrayHelper::getColumn($assignPerson, 'assignUser.ee'),
            'email' => ArrayHelper::getColumn($assignPerson, 'assignUser.email')
        ];
        
        return $assignUser;
    }
    
    /**
     * 获取制作人
     * @param type $taskId      任务ID
     * @return type
     */
    public function getProducer($taskId)
    {
        $producers = MultimediaProducer::find()
                    ->where(['task_id' => $taskId])
                    ->with('multimediaProducer.user')
                    ->with('task')
                    ->all();
       
        $producer = [
            'u_id' => ArrayHelper::getColumn($producers, 'multimediaProducer.u_id'),
            'name' => ArrayHelper::getColumn($producers, 'multimediaProducer.user.nickname'),
            'ee' => ArrayHelper::getColumn($producers, 'multimediaProducer.user.ee'),
            'email' => ArrayHelper::getColumn($producers, 'multimediaProducer.user.email')
        ];
        
        return $producer;
    }

    /**
     * 给所在团队指派人 发送 ee通知 email
     * @param type $model
     * @param integer|array $teamId     团队ID
     * @param type $mode                标题模式
     * @param type $views               视图
     * @param type $cancel              临时变量
     */
    public function sendAssignPersonNotification($model, $teamId, $mode, $views, $cancel = null){
        /* @var $model MultimediaTask */
        $assignPerson = $this->getAssignPerson ($teamId);
        //传进view 模板参数
        $params = [
            'model' => $model,
            'cancel' => $cancel,
        ];
        //主题 
        $subject = "多媒体-".$mode;
        //团队指派人ee
        $assignPerson_ee = array_filter(ArrayHelper::getValue($assignPerson, 'ee'));
        //团队指派人邮箱地址
        $assignPerson_email = array_filter(ArrayHelper::getValue($assignPerson, 'email'));
        //发送ee消息 
        EeManager::sendEeByView($views, $params, $assignPerson_ee, $subject);
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($receivers_mail)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * 给创建者 发送 ee通知 email
     * @param type $model
     * @param type $mode        标题模式
     * @param type $views       视图
     * @param type $cancel       临时变量
     */
    public  function sendCreateByNotification($model, $mode, $views, $cancel = null){
        /* @var $model MultimediaTask */
        $producer = ArrayHelper::getValue($this->getProducer($model->id), 'name');
        //传进view 模板参数
        $params = [
            'model' => $model,
            'producer' => $producer,
            'cancel' => $cancel,
        ];
        //主题
        $subject = "多媒体-".$mode;
        //查找编导ee和mail 
        $createBy_ee = $model->createBy->ee;
        $createBy_mail = $model->createBy->email;
         //发送ee消息
        EeManager::sendEeByView($views, $params,$createBy_ee, $subject);
        //发送邮件消息
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($shootBooker_mail)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * 给制作人 发送 ee通知 email
     * @param type $model
     * @param type $taskId      任务ID
     * @param type $mode        标题模式
     * @param type $views       视图
     * @param type $cancel      临时变量
     */
    public  function sendProducerNotification($model, $taskId, $mode, $views, $cancel = null){
        /* @var $model MultimediaTask */
        $producers = $this->getProducer($taskId);
        //传进view 模板参数 
        $params = [
            'model' => $model,
            'cancel' => $cancel,
        ];
        //主题 
        $subject = "多媒体-".$mode;
        //查找接洽人ee和mail 
        $producer_ee = array_filter(ArrayHelper::getValue($producers, 'ee'));
        $producer_mail = array_filter(ArrayHelper::getValue($producers, 'email'));
        //发送ee消息
        EeManager::sendEeByView($views, $params,$producer_ee, $subject);
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($shootContacter_mail)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * jobManager 添加用户任务通知关联
     * @param type $model
     * @param type $post
     */
    public function saveJobManager($model){
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /* @var $model MultimediaTask */
        $assignPerson = $this->getAssignPerson($model->create_team);
        $assignPersonId = ArrayHelper::getValue($assignPerson, 'u_id');
        
        //创建job表任务
        $jobManager->createJob(AppGlobalVariables::getSystemId(), $model->id, $model->name, 
                '/multimedia/default/view?id='.$model->id, $model->getStatusName(), $model->progress);
        //添加通知
        $jobManager->addNotification(AppGlobalVariables::getSystemId(), $model->id, ArrayHelper::merge([$model->create_by], $assignPersonId));
    }
    
    /**
     * 设置指派制作人用户任务通知关联
     * @param type $model
     * @param type $post
     */
    public  function setAssignNotification($model, $post){
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /* @var $model MultimediaTask */
        $producer = $this->getProducer($model->id);
        $producerId = array_filter(ArrayHelper::getColumn($producer, 'u_id'));
       
        //更新任务通知表
        $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status' => $model->getStatusName()]); 
        //清空用户任务通知关联
        $jobManager->removeNotification(AppGlobalVariables::getSystemId(), $model->id, $producerId);
        //添加用户任务通知关联
        $jobManager->addNotification(AppGlobalVariables::getSystemId(), $model->id, $post);
    }
    
    /**
     * jobManager 取消用户任务通知关联
     * @param type $model
     * @param integer|array $teamId     团队ID
     */
    public  function cancelJobManager($model, $teamId){
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /* @var $model MultimediaTask */
        $team = array_filter(ArrayHelper::getValue($this->getAssignPerson($teamId), 'u_id'));
        $producer = $this->getProducer($model->id);
        $producerId = array_filter(ArrayHelper::getValue($producer, 'u_id'));
        //全并两个数组的值
        $jobUserAll = ArrayHelper::merge(ArrayHelper::merge([$model->create_by], $team), $producerId);
        //修改job表任务
        $jobManager->updateJob(AppGlobalVariables::getSystemId(),$model->id,['progress'=> $model->progress, 'status'=>$model->getStatusName()]); 
        //修改通知
        $jobManager->cancelNotification(AppGlobalVariables::getSystemId(), $model->id, $jobUserAll);
    }
    
    /**
     * 获取单例
     * @return MultimediaTool
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new MultimediaNoticeTool();
        }
        return self::$instance;
    }
}