<?php

namespace frontend\modules\scene\utils;

use common\models\scene\SceneBook;
use wskeee\ee\EeManager;
use wskeee\notification\NotificationManager;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use Yii;
use yii\helpers\ArrayHelper;


/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class SceneBookNotice {
   
    /**
     * 初始化类变量
     * @var SceneBookNotice 
     */
    private static $instance = null;
    
    /**
     * 模块主题
     * @var string 
     */
    private static $subjectModule = "拍摄-";
    
    /**
     * 获取单例
     * @return SceneBookNotice
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new SceneBookNotice();
        }
        return self::$instance;
    }
    
    /**
     * 访问链接
     * @return string
     */
    private function createAbsoluteUrl($model)
    {
        return Yii::$app->urlManager->createAbsoluteUrl(['/scene/scene-book/view','id' => $model->id]);
    }
        
    /**
     * 给所有摄影组长 发送通知
     * @param SceneBook $model              
     * @param string $title                         标题
     * @param string $views                         视图
     */
    public static function sendShootLeaderNotification($model, $title, $views)
    {
        /* @var $authManager RbacManager */
        $authManager = Yii::$app->authManager;
        //传进view 模板参数
        $params = [
            'model' => $model,
        ];
        //主题 
        $subject = self::$subjectModule.$title;
        //查找所有摄影组长
        $users = $authManager->getItemUsers(RbacName::ROLE_SHOOT_LEADER);
        //所有摄影师组长guid
        $receivers = array_filter(ArrayHelper::getColumn($users, 'guid'));
        //所有摄影师组长邮箱地址
        $receivers_mail = array_filter(ArrayHelper::getColumn($users, 'email'));
        
        //发送消息 
        NotificationManager::sendByView($views, $params, $receivers, $subject, $this->createAbsoluteUrl($model));
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($receivers_email)
            ->setSubject($subject)
            ->send();*/
    }
    
    /** 
     * 给指派预约用户 发送通知
     * @param SceneBook $model              
     * @param array $contacter                      接洽人
     * @param array $oldShootMan                    旧摄影师
     * @param array $newShootMan                    新摄影师
     * @param string $title                         标题
     * @param string $views                         视图
     */
    public  function sendAssignSceneBookUserNotification($model, $contacter, $oldShootMan, $newShootMan, $title, $views)
    {
        //传进view 模板参数
        $params = [
            'model' => $model,
            'contacter' => implode('、', ArrayHelper::getColumn($contacter, 'nickname')),
            'oldShootMan' => implode('、', array_diff(ArrayHelper::getColumn($oldShootMan, 'nickname'), ArrayHelper::getColumn($newShootMan, 'nickname'))),
            'newShootMan' => implode('、', array_diff(ArrayHelper::getColumn($newShootMan, 'nickname'), ArrayHelper::getColumn($oldShootMan, 'nickname'))),
        ];
        
        //主题 
        $subject = self::$subjectModule.$title;
        //所有编导guid
        $receivers = array_merge([$model->booker->guid], 
                array_merge(ArrayHelper::getColumn($contacter, 'guid'), 
                    array_diff(ArrayHelper::getColumn($newShootMan, 'guid'), 
                        ArrayHelper::getColumn($oldShootMan, 'guid'))));
        //所有编导邮箱地址
        $receivers_mail = array_merge([$model->booker->email], 
                array_merge(ArrayHelper::getColumn($contacter, 'email'), 
                    array_diff(ArrayHelper::getColumn($newShootMan, 'email'), 
                        ArrayHelper::getColumn($oldShootMan, 'email'))));
        
        //发送消息 
        NotificationManager::sendByView($views, $params, $receivers, $subject, $this->createAbsoluteUrl($model));
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($receivers_email)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * 给所有预约人 发送通知
     * @param SceneBook $model
     * @param string $content                       原因
     * @param string $title                         标题
     * @param string $views                         视图
     */
    public  function sendBookerNotification($model, $content, $title, $views)
    {
        /* @var $authManager RbacManager */
        $authManager = Yii::$app->authManager;
        //传进view 模板参数 
        $params = [
            'model' => $model,
            'content' => $content,
        ];
         
        //主题 
        $subject = self::$subjectModule.$title;
        //角色名
        $rbacNames = [
            RbacName::ROLE_ADMIN, RbacName::ROLE_COMMON_COURSE_DEV_MANAGER,
            RbacName::ROLE_SHOOT_LEADER, RbacName::ROLE_WD
        ];
        //查找所有预约人
        $users = $authManager->getItemUsers($rbacNames);
        //所有预约人guid
        $receivers = array_filter(ArrayHelper::getColumn($users, 'guid'));
        //所有预约人邮箱地址
        $receivers_mail = array_filter(ArrayHelper::getColumn($users, 'email'));
        
        //发送消息 
        NotificationManager::sendByView($views, $params, $receivers, $subject, $this->createAbsoluteUrl($model));
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($receivers_email)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * 给转让成功预约用户 发送通知
     * @param SceneBook $model
     * @param string $oldBookerName                 旧预约人
     * @param array $users                          接洽人 and 摄影师
     * @param string $title                         标题
     * @param string $views                         视图
     */
    public  function sendReceiveSceneBookUserNotification($model, $oldBookerName, $users, $title, $views)
    {
        /** 传进view 模板参数 */
         $params = [
            'model' => $model,
            'oldBookerName' => $oldBookerName,
        ];
         
        //主题 
        $subject = self::$subjectModule.$title;
        //转让成功预约用户guid
        $receivers = array_filter(ArrayHelper::getValue($users, 'guid'));
        //转让成功预约用户邮箱地址
        $receivers_mail = array_filter(ArrayHelper::getValue($users, 'email'));
        
        //发送消息 
        NotificationManager::sendByView($views, $params, $receivers, $subject, $this->createAbsoluteUrl($model));
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($receivers_email)
            ->setSubject($subject)
            ->send();*/
    }
}