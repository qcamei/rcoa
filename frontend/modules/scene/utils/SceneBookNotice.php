<?php

namespace frontend\modules\scene\utils;

use common\models\scene\SceneBook;
use common\models\User;
use wskeee\notification\NotificationMailManager;
use wskeee\notification\NotificationManager;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


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
    private static function createAbsoluteUrl($model)
    {
        return Url::to(WEB_ROOT."/scene/scene-book/view?id=".$model->id);
    }
        
    /**
     * 创建成功 给所有摄影组长 发送通知
     * @param SceneBook $model              
     * @param string $title                         标题
     * @param string $views                         视图
     */
    public function sendShootLeaderNotification($model, $title, $views)
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
        //$users = $authManager->getItemUsers(RbacName::ROLE_SHOOT_LEADER);
        //查找场地管理员
        $users = User::find()->where(['id' => $model->sceneSite->manager_id])->all();
        
        //所有摄影师组长guid
        $receivers = array_filter(ArrayHelper::getColumn($users, 'guid'));
        //所有摄影师组长邮箱地址
        $receivers_mail = $this->separateEmailInGuid($receivers);
        
        //发送消息 
        if(count($receivers) > 0){
            //有企业微信优先发
            NotificationManager::sendByView($views, $params, $receivers, $subject, self::createAbsoluteUrl($model));
        }else{
            //没有即送邮件
            NotificationMailManager::sendByView($views.'_mail', $params, $receivers_mail, $subject, self::createAbsoluteUrl($model));
        }
    }
    
    /** 
     * 指派成功 给预约人、接洽人 and 摄影师 发送通知
     * @param SceneBook $model              
     * @param array $contacter                      接洽人
     * @param array $oldShootMan                    旧摄影师
     * @param array $newShootMan                    新摄影师
     * @param string $title                         标题
     * @param string $views                         视图
     */
    public function sendAssignSceneBookUserNotification($model, $contacter, $oldShootMan, $newShootMan, $title, $views)
    {
        //传进view 模板参数
        $params = [
            'model' => $model,
            'contacter' => implode('、', ArrayHelper::getColumn($contacter, 'nickname')),
            'oldShootMan' => implode('、', ArrayHelper::getColumn($oldShootMan, 'nickname')),
            'newShootMan' => implode('、', ArrayHelper::getColumn($newShootMan, 'nickname')),
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
        NotificationManager::sendByView($views, $params, $receivers, $subject, self::createAbsoluteUrl($model));
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($receivers_email)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * 申请转让 给所有预约人 发送通知
     * @param SceneBook $model
     * @param string $content                       原因
     * @param string $title                         标题
     * @param string $views                         视图
     */
    public  function sendTransferBookerNotification($model, $content, $title, $views)
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
        NotificationManager::sendByView($views, $params, $receivers, $subject, self::createAbsoluteUrl($model));
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($receivers_email)
            ->setSubject($subject)
            ->send();*/
    } 

    /**
     * 转让成功 给接洽人 and 摄影师 发送通知
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
        $receivers_mail = $this->separateEmailInGuid($receivers);
        
        //企业微信优先发
        NotificationManager::sendByView($views, $params, $receivers, $subject, self::createAbsoluteUrl($model));
        //没有即送邮件
        NotificationMailManager::sendByView($views.'_mail', $params, $receivers_mail, $subject, self::createAbsoluteUrl($model));
    }
    
    /**
     * 给预约人、接洽人、所以摄影组长|摄影师 发送通知
     * @param type $model
     * @param type $status          状态
     * @param type $content         取消原因
     * @param type $contacter       接洽人
     * @param type $shootMan        摄影师
     * @param type $title           标题
     * @param type $views           视图
     */
    public function sendAllManNotification($model, $status, $content, $contacter, $shootMan, $title, $views)
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
        
        //查找所有摄影组长
        //$users = $authManager->getItemUsers(RbacName::ROLE_SHOOT_LEADER);
        //查找场地管理员
        $users = User::find()->where(['id' => $model->sceneSite->manager_id])->all();
        //该任务的预约人、接洽人的guid
        $receivers = array_merge([$model->booker->guid], ArrayHelper::getColumn($contacter, 'guid'));
        //该任务的预约人、接洽人的邮箱地址
        //$receivers_mail = [];//array_merge([$model->booker->email],ArrayHelper::getColumn($contacter, 'email')); //目前所有编导都是公司内部人员，都有企业微信
        if($status == SceneBook::STATUS_ASSIGN){
            //该任务的预约人、接洽人、所有摄影师组长的guid
            $receivers = array_merge($receivers, array_filter(ArrayHelper::getColumn($users, 'guid')));
            //该任务的预约人、接洽人、所有摄影师组长的邮箱地址
            //$receivers_mail = array_merge($receivers_mail, array_filter(ArrayHelper::getColumn($users, 'email')));
        }else{
            //该任务的预约人、接洽人、摄影师的guid
            $receivers = array_merge($receivers, ArrayHelper::getColumn($shootMan, 'guid'));
            //该任务的预约人、接洽人、所有摄影师组长的邮箱地址
            //$receivers_mail = array_merge($receivers_mail, ArrayHelper::getColumn($shootMan, 'email'));
        }
        //获取需要发送邮件的地址
        $receivers_mail = $this->separateEmailInGuid($receivers);
        //发送消息 
        NotificationManager::sendByView($views, $params, $receivers, $subject, self::createAbsoluteUrl($model));
        //发送邮件消息 
        NotificationMailManager::sendByView($views.'_mail', $params, $receivers_mail, $subject, self::createAbsoluteUrl($model));
    }
    
    /**
     * 从guid数组里分离出属于邮箱地址的guid
     * @param array $guids
     * @remove array $email
     */
    private function separateEmailInGuid(&$guids) {
        $pattern = '/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';
        $emails = [];
        foreach ($guids as $key => $guid) {
            //检查是否为邮箱地址，是则加到邮箱地址数组并且从原数组移除
            $valid = preg_match($pattern, $guid);
            if(preg_match($pattern, $guid)){
                unset($guids[$key]);
                $emails[] = $guid;
            }
        }
        return $emails;
    }

}
