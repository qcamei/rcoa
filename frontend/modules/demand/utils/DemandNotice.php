<?php
namespace frontend\modules\demand\utils;

use common\config\AppGlobalVariables;
use common\models\demand\DemandTask;
use common\wskeee\job\JobManager;
use wskeee\notification\NotificationManager;
use Yii;
use yii\helpers\ArrayHelper;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DemandNotice {
   
    private static $subjectModule = "需求-";
    
    /**
     * 访问链接
     * @return string
     */
    private static function createAbsoluteUrl($model)
    {
        return Yii::$app->urlManager->createAbsoluteUrl(['/demand/task/view','id' => $model->id]);
    }
    
    /**
     * 给所在团队审核人 发送通知
     * @param DemandTask $model
     * @param array $users                    用户信息
     * @param string $mode                    标题
     * @param string $views                   视图
     * @param string $des                     备注
     */
    public static function sendAuditorNotification($model, $users, $title, $views, $des = '无')
    {
        //传进view 模板参数
        $params = [
            'model' => $model,
            'des' => $des,
        ];
        //主题 
        $subject = self::$subjectModule.$title;
        //查找团队审核人
        $receivers = array_filter(ArrayHelper::getValue($users, 'guid'));
        //团队审核人邮箱地址
        $receivers_email = array_filter(ArrayHelper::getValue($users, 'email'));
        //发送消息 
        NotificationManager::sendByView($views, $params, $receivers, $subject, self::createAbsoluteUrl($model));
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($receivers_email)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * 给创建者 发送通知
     * @param DemandTask $model
     * @param array $users                    用户信息
     * @param string $mode                    标题
     * @param string $views                   视图
     * @param string $des                     备注
     */
    public static function sendCreateByNotification($model, $users, $title, $views, $des = '无')
    {
        //传进view 模板参数
        $params = [
            'model' => $model,
            'des' => $des,
        ];
        //主题 
        $subject = self::$subjectModule.$title;
        //查找创建者
        $receivers = ArrayHelper::getValue($users, 'guid');
        //创建者邮箱地址
        $receivers_email = ArrayHelper::getValue($users, 'email');
        //发送消息 
        NotificationManager::sendByView($views, $params, $receivers, $subject, self::createAbsoluteUrl($model));
        //发送邮件消息
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($receivers_email)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * 给承接人 发送通知
     * @param DemandTask $model
     * @param array $users                    用户信息
     * @param string $mode                    标题
     * @param string $views                   视图
     * @param string $des                     备注
     */
    public static function sendUndertakerNotification($model, $users, $title, $views, $des = '无')
    {    
        //传进view 模板参数 
        $params = [
            'model' => $model,
            'des' => $des,
        ];
        //主题 
        $subject = self::$subjectModule.$title;
        //查找承接人
        $receivers = is_array($users) ? array_filter(ArrayHelper::getValue($users, 'guid')) : ArrayHelper::getValue($users, 'guid');
        //承接人邮箱地址
        $receivers_email = is_array($users) ? array_filter(ArrayHelper::getValue($users, 'email')) : ArrayHelper::getValue($users, 'email');
        //发送消息 
        NotificationManager::sendByView($views, $params, $receivers, $subject, self::createAbsoluteUrl($model));
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($receivers_email)
            ->setSubject($subject)
            ->send();*/
    }

    /**
     * jobManager 添加用户任务通知关联
     * @param DemandTask $model
     */
    public static function saveJobManager($model)
    {
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        
        //创建job表任务
        $jobManager->createJob(AppGlobalVariables::getSystemId(), $model->id, $model->course->name, 
                '/demand/task/view?id='.$model->id, $model->getStatusName(), $model->progress);
        //添加通知
        $jobManager->addNotification(AppGlobalVariables::getSystemId(), $model->id, $model->create_by);
    }
    
    /**
     * jobManager 取消用户任务通知关联
     * @param DemandTask $model
     * @param integer|array $users                      用户信息
     */
    public static function cancelJobManager($model, $users){
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
       
        //全并两个数组的值
        $jobUserAll = ArrayHelper::merge([$model->create_by], $users);
        //修改job表任务
        $jobManager->updateJob(AppGlobalVariables::getSystemId(),$model->id,['progress'=> $model->progress, 'status'=> $model->getStatusName()]); 
        //修改通知
        $jobManager->cancelNotification(AppGlobalVariables::getSystemId(), $model->id, $jobUserAll);
    }
    
}