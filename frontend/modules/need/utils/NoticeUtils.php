<?php
namespace frontend\modules\need\utils;

use common\models\need\NeedTask;
use wskeee\notification\NotificationManager;
use Yii;
use yii\helpers\ArrayHelper;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class NoticeUtils {
   
    private static $subjectModule = "需求任务-";
    
    /**
     * 访问链接
     * @param NeedTask $model
     * @return string
     */
    private static function createAbsoluteUrl($model)
    {
        return Yii::$app->urlManager->createAbsoluteUrl(['/need/task/view','id' => $model->id]);
    }
    
    /**
     * 给审核人 发送通知
     * @param NeedTask $model
     * @param string $mode                    标题
     * @param string $views                   视图
     * @param array $results                  结果
     */
    public static function sendAuditByNotification($model, $title, $views, $results = [])
    {
        //传进view 模板参数
        $params = [
            'model' => $model,
            'results' => $results,
        ];
        //主题 
        $subject = self::$subjectModule. $title;
        //查找审核人
        $receivers = $model->auditBy->guid;
        //审核人邮箱地址
        $receivers_email = $model->auditBy->email;
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
     * @param NeedTask $model
     * @param string $mode                    标题
     * @param string $views                   视图
     * @param array $results                  结果   
     */
    public static function sendCreateByNotification($model, $title, $views, $results = [])
    {
        //传进view 模板参数
        $params = [
            'model' => $model,
            'results' => $results,
        ];
        //主题 
        $subject = self::$subjectModule . $title;
        //查找创建者
        $receivers = $model->createdBy->guid;
        //创建者邮箱地址
        $receivers_email = $model->createdBy->email;
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
     * @param NeedTask $model
     * @param array $users                    用户信息
     * @param string $mode                    标题
     * @param string $views                   视图
     * @param array $results                  结果   
     */
    public static function sendReceiveByNotification($model, $users, $title, $views, $results = [])
    {    
        //传进view 模板参数 
        $params = [
            'model' => $model,
            'results' => $results,
        ];
        //主题 
        $subject = self::$subjectModule . $title;
        //查找承接人
        $receivers = ArrayHelper::getValue($users, 'guid');
        //承接人邮箱地址
        $receivers_email = ArrayHelper::getValue($users, 'email');
        //发送消息 
        NotificationManager::sendByView($views, $params, $receivers, $subject, self::createAbsoluteUrl($model));
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($receivers_email)
            ->setSubject($subject)
            ->send();*/
    }
}