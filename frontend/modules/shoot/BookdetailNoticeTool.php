<?php
namespace frontend\modules\shoot;

use common\wskeee\job\JobManager;
use wskeee\ee\EeManager;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use Yii;
use yii\helpers\ArrayHelper;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class BookdetailNoticeTool {
   
    /**
     * 给所有摄影组长 发送 ee通知 email
     * @param type $model
     * @param type $mode  标题模式
     * @param type $views       视图
     */
    public function sendShootLeadersNotification($model, $mode, $views){
        /* @var $authManager RbacManager */
        $authManager = Yii::$app->authManager;
        //传进view 模板参数
         $params = [
            'b_id' => $model->id,
            'model' => $model,
            'bookTime' => date('Y/m/d ',$model->book_time).Yii::t('rcoa', 'Week '.date('D',$model->book_time)).' '.$model->getTimeIndexName(),
        ];
         //主题 
        $subject = "拍摄-".$mode."-".$model->fwCourse->name;
        //查找所有摄影组长
        $shootLeaders = $authManager->getItemUsers(RbacName::ROLE_SHOOT_LEADER);
        //所有摄影师组长ee
        $receivers_ee = array_filter(ArrayHelper::getColumn($shootLeaders, 'ee'));
       
        //所有摄影师组长邮箱地址
        $receivers_mail = array_filter(ArrayHelper::getColumn($shootLeaders, 'email'));
         //var_dump($receivers_mail);exit;
        //发送ee消息 
        EeManager::sendEeByView($views, $params, $receivers_ee, $subject);
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($receivers_mail)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * 给编导 发送 ee通知 email
     * @param type $model
     * @param type $mode  标题模式
     * @param type $views       视图
     */
    public  function sendBookerNotification($model, $mode, $views){
        //传进view 模板参数
         $params = [
            'b_id' => $model->id,
            'model' => $model,
            'bookTime' => date('Y/m/d ',$model->book_time).Yii::t('rcoa', 'Week '.date('D',$model->book_time)).' '.$model->getTimeIndexName(),
        ];
        //主题
        $subject = "拍摄-".$mode."-".$model->fwCourse->name;
        //查找编导ee和mail 
        $shootBooker_ee = $model->booker->ee;
        $shootBooker_mail = $model->booker->email;
         //发送ee消息
        EeManager::sendEeByView($views, $params,$shootBooker_ee, $subject);
        //发送邮件消息
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($shootBooker_mail)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * 给接洽人 发送 ee通知 email
     * @param type $model
     * @param type $mode  标题模式
     * @param type $views       视图
     */
    public  function sendContacterNotification($model, $mode, $views){
        //传进view 模板参数 
         $params = [
            'b_id' => $model->id,
            'model' => $model,
            'bookTime' => date('Y/m/d ',$model->book_time).Yii::t('rcoa', 'Week '.date('D',$model->book_time)).' '.$model->getTimeIndexName(),
        ];
         //主题 
        $subject = "拍摄-".$mode."-".$model->fwCourse->name;
        //查找接洽人ee和mail 
        $shootContacter_ee = $model->contacter->ee;
        $shootContacter_mail = $model->contacter->email;
        //发送ee消息
        EeManager::sendEeByView($views, $params,$shootContacter_ee, $subject);
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($shootContacter_mail)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * 给摄影师 发送 ee通知 email
     * @param type $model
     * @param type $mode  标题模式
     * @param type $views       视图
     * @param type $oldShootMan   旧摄影师
     */
    public  function sendShootManNotification($model, $mode, $views, $oldShootMan = null) {
        
        /** 传进view 模板参数 */
         $params = [
            'b_id' => $model->id,
            'model' => $model,
            'bookTime' => date('Y/m/d ',$model->book_time).Yii::t('rcoa', 'Week '.date('D',$model->book_time)).' '.$model->getTimeIndexName(),
            'u_shoot_man' => $oldShootMan,
        ];
        //主题
        $subject = "拍摄-".$mode."-".$model->fwCourse->name;
        //查找摄影师ee和mail 
        $shootMan_ee = $model->shootMan->ee;
        $shootMan_mail = $model->shootMan->email;
        //发送ee消息 
        EeManager::sendEeByView($views, $params, $shootMan_ee, $subject);
        // 发送邮件消息
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($shootMan_mail)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * 给老师 发送 ee通知 email
     * @param type $model
     * @param type $mode  标题模式
     * @param type $views       视图
     */
    public  function sendTeacherNotification($model, $mode, $views){
        /** 传进view 模板参数 */
         $params = [
            'b_id' => $model->id,
            'model' => $model,
            'bookTime' => date('Y/m/d ',$model->book_time).Yii::t('rcoa', 'Week '.date('D',$model->book_time)).' '.$model->getTimeIndexName(),
        ];
         
         //主题 
        $subject = "拍摄-".$mode."-".$model->fwCourse->name;
        //查找老师ee和mail 
        $shootTeacher_ee = $model->teacher->user->ee;
        $shootTeacher_mail = $model->teacher->user->email;
        //发送ee消息 
        EeManager::sendEeByView($views, $params, $shootTeacher_ee, $subject);
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($shootTeacher_mail)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * jobManager 添加用户任务通知关联
     * @param type $model
     * @param type $post
     */
    public function saveJobManager($model, $post){
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /* @var $authManager RbacManager */
        $authManager = Yii::$app->authManager;
        $shootLeaders = $authManager->getItemUsers(RbacName::ROLE_SHOOT_LEADER);
        $shootLeadersId = array_filter(ArrayHelper::getColumn($shootLeaders, 'id'));
        $jobUsers = ArrayHelper::merge($post, $shootLeadersId);
        
        //创建job表任务
        $jobManager->createJob(2, $model->id, $model->fwCourse->name, '/shoot/bookdetail/view?id='.$model->id, $model->getStatusName(),35); 
        //添加通知
        $jobManager->addNotification(2, $model->id, $jobUsers);
    }
    
    /**
     * 设置指派摄影师用户任务通知关联
     * @param type $model
     * @param type $oldRoleNmae 旧角色
     * @param type $assignedRoleNmae 已经被指派的角色
     * @param type $post
     */
    public  function setAssignNotification($model, $oldRoleNmae = null, $assignedRoleNmae, $post){
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /* @var $authManager RbacManager */
        $authManager = Yii::$app->authManager;
        $shootLeaders = $authManager->getItemUsers(RbacName::ROLE_SHOOT_LEADER);
        $shootLeadersId = array_filter(ArrayHelper::getColumn($shootLeaders, 'id'));
        if($oldRoleNmae != null){
            $roleNmae = [];
            foreach ($assignedRoleNmae as $key => $value)
                $roleNmae[] = (string)$key;
        }
        
        //更新任务通知表
        $jobManager->updateJob(2, $model->id, ['progress'=> 70, 'status' => $model->getStatusName()]); 
        //清空用户任务通知关联
        $jobManager->removeNotification(2, $model->id, ($oldRoleNmae != null ? $roleNmae : $shootLeadersId));
        //添加用户任务通知关联
        $jobManager->addNotification(2, $model->id, $post);
    }
    
    /**
     * jobManager 取消用户任务通知关联
     * @param type $model
     * @param type $roleNmaeAll 所有角色
     */
    public  function cancelJobManager($model, $roleNmaeAll){
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /* @var $authManager RbacManager */
        $authManager = Yii::$app->authManager;
        
        $shootLeaders = $authManager->getItemUsers(RbacName::ROLE_SHOOT_LEADER);
        $shootLeadersId = array_filter(ArrayHelper::getColumn($shootLeaders, 'id'));
        $roleNmaeAlls = [];
        foreach ($roleNmaeAll as $key => $value)
            $roleNmaeAlls[] = (string)$key;
        
        //全并两个数组的值
        $jobUserAll = ArrayHelper::merge($roleNmaeAlls, $shootLeadersId);
        
        //修改job表任务
        $jobManager->updateJob(2,$model->id,['progress'=> 100, 'status'=>$model->getStatusName()]); 
        //修改通知
        $jobManager->cancelNotification(2, $model->id, $jobUserAll);
    }
}