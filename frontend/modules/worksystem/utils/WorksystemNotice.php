<?php

namespace frontend\modules\worksystem\utils;

use common\config\AppGlobalVariables;
use common\models\worksystem\WorksystemTask;
use common\wskeee\job\JobManager;
use wskeee\ee\EeManager;
use Yii;
use yii\helpers\ArrayHelper;


class WorksystemNotice 
{
    private static $instance = null;
    
    /**
     * 给所在团队指派人 发送 ee通知 email
     * @param WorksystemTask $model
     * @param array $users                                  用户信息
     * @param string $mode                                  标题模式
     * @param string $views                                 视图
     * @param string $des                                   备注
     */
    public function sendAssignPeopleNotification($model, $users, $mode, $views, $des = null)
    {
        //传进view 模板参数
        $params = [
            'model' => $model,
            'des' => $des != null ? $des : '无',
        ];
        //主题 
        $subject = "工作系统-".$mode;
        //团队指派人ee
        $assignPerson_ee = array_filter(ArrayHelper::getValue($users, 'ee'));
        //团队指派人邮箱地址
        $assignPerson_email = array_filter(ArrayHelper::getValue($users, 'email'));
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
     * @param WorksystemTask $model
     * @param string $mode                                  标题模式
     * @param string $views                                 视图
     * @param string $nickname                              昵称
     * @param string $des                                   备注
     */
    public  function sendCreateByNotification($model, $mode, $views, $nickname = null, $des = null)
    {
        //传进view 模板参数
        $params = [
            'model' => $model,
            'nickname' => implode(',', $nickname),
            'des' => $des != null ? $des : '无',
        ];
        //主题
        $subject = "工作系统-".$mode;
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
     * @param WorksystemTask $model
     * @param string $mode                                  标题模式
     * @param array $users                                  用户信息
     * @param string $views                                 视图
     * @param string $des                                   备注
     */
    public  function sendProducerNotification($model, $users, $mode, $views, $des = null)
    {
        //传进view 模板参数 
        $params = [
            'model' => $model,
            'des' => $des != null ? $des : '无',
        ];
        //主题 
        $subject = "工作系统-".$mode;
        //查找接洽人ee和mail 
        $producer_ee = array_filter(ArrayHelper::getValue($users, 'ee'));
        $producer_mail = array_filter(ArrayHelper::getValue($users, 'email'));
        //发送ee消息
        EeManager::sendEeByView($views, $params,$producer_ee, $subject);
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($shootContacter_mail)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * 给外包成员 发送 ee通知 email
     * @param WorksystemTask $model
     * @param string $mode                                  标题模式
     * @param array $users                                  用户信息
     * @param string $views                                 视图
     * @param string $des                                   备注
     */
    public  function sendEpibolyNotification($model, $users, $mode, $views, $des = null)
    {
        //传进view 模板参数 
        $params = [
            'model' => $model,
            'des' => $des != null ? $des : '无',
        ];
        //主题 
        $subject = "工作系统-".$mode;
        //查找接洽人ee和mail 
        $producer_ee = array_filter(ArrayHelper::getValue($users, 'ee'));
        $producer_mail = array_filter(ArrayHelper::getValue($users, 'email'));
        //发送ee消息
        EeManager::sendEeByView($views, $params,$producer_ee, $subject);
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($shootContacter_mail)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * 添加用户任务通知关联
     * @param WorksystemTask $model
     * @param JobManager $jobManager
     */
    public function saveJobManager($model)
    {
        $jobManager = Yii::$app->get('jobManager');
        
        //创建job表任务
        $jobManager->createJob(AppGlobalVariables::getSystemId(), $model->id, $model->name, 
                '/worksystem/task/view?id='.$model->id, $model->getStatusName(), $model->progress);
        $jobManager->addNotification(AppGlobalVariables::getSystemId(), $model->id, $model->create_by); 
            
    }
       
    /**
     * 设置指派制作人用户任务通知关联
     * @param WorksystemTask $model
     * @param JobManager $jobManager
     * @param array $users                          用户信息
     */
    public  function setAssignNotification($model, $users)
    {
        $jobManager = Yii::$app->get('jobManager');
       
        //更新任务通知表
        $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status' => $model->getStatusName()]); 
        //清空用户任务通知关联
        $jobManager->removeNotification(AppGlobalVariables::getSystemId(), $model->id, $users);
        //添加用户任务通知关联
        $jobManager->addNotification(AppGlobalVariables::getSystemId(), $model->id, $users);
    }
    
    /**
     * jobManager 取消用户任务通知关联
     * @param WorksystemTask $model
     * @param JobManager $jobManager
     * @param array $users                              用户信息
     */
    public  function cancelJobManager($model, $users)
    {
        $jobManager = Yii::$app->get('jobManager');
        
        //修改job表任务
        $jobManager->updateJob(AppGlobalVariables::getSystemId(),$model->id,['progress'=> $model->progress, 'status'=>$model->getStatusName()]); 
        //修改通知
        $jobManager->cancelNotification(AppGlobalVariables::getSystemId(), $model->id, $users);
    }
    
    /**
     * 获取单例
     * @return WorksystemNotice
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new WorksystemNotice();
        }
        return self::$instance;
    }
}