<?php
namespace frontend\modules\demand\utils;

use common\config\AppGlobalVariables;
use common\models\demand\DemandTask;
use common\models\demand\DemandTaskAuditor;
use common\models\team\TeamMember;
use common\wskeee\job\JobManager;
use wskeee\ee\EeManager;
use Yii;
use yii\helpers\ArrayHelper;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DemandNoticeTool {
   
    private static $instance = null;
    
    /**
     * 获取团队审核人
     * @param integer $teamId        团队Id
     * @return array
     */
    public function getAuditor($teamId)
    {
        /* @var $auditor DemandTaskAuditor */
        $auditor = DemandTaskAuditor::find()
                   ->where(['team_id' => $teamId])
                   ->with('taskUser')
                   ->all();
        
        $checkUser = [
            'u_id' => ArrayHelper::getColumn($auditor, 'u_id'),
            'ee' => ArrayHelper::getColumn($auditor, 'taskUser.ee'),
            'email' => ArrayHelper::getColumn($auditor, 'taskUser.email')
        ];
        
        return $checkUser;
    }
    
    /**
     * 获取所有承接人
     * @param string $uId           用户ID
     * @return array             
     */
    public function getUndertakePerson($uId = null)
    {
        $undertakes = TeamMember::find()
                    ->where([
                        'position_id' => 1, 
                        'is_delete' => TeamMember::CANCEL_DELETE, 
                        'is_leader' => TeamMember::TEAMLEADER
                    ])
                    ->andFilterWhere(['u_id' => $uId])
                    ->with('user')
                    ->all();
        
        $undertake = [
            'u_id' => ArrayHelper::getColumn($undertakes, 'u_id'),
            'name' => ArrayHelper::getColumn($undertakes, 'user.nickname'),
            'ee' => ArrayHelper::getColumn($undertakes, 'user.ee'),
            'email' => ArrayHelper::getColumn($undertakes, 'user.email')
        ];
        
        return $undertake;
    }
    
    /**
     * 给所在团队审核人 发送 ee通知 email
     * @param DemandTask $model
     * @param integer|array $teamId           团队ID
     * @param string $mode                    标题模式
     * @param string $views                   视图
     * @param string $cancel                  临时变量
     */
    public function sendAuditorNotification($model, $teamId, $mode, $views, $cancel = null){
        /* @var $model DemandTask */
        $auditor = $this->getAuditor($teamId);
        //传进view 模板参数
        $params = [
            'model' => $model,
            'cancel' => $cancel,
        ];
        //主题 
        $subject = "课程需求-".$mode;
        //查找审核人ee和mail 
        $auditor_ee = implode(',', array_filter(ArrayHelper::getValue($auditor, 'ee')));
        $auditor_email = array_filter(ArrayHelper::getValue($auditor, 'email'));
        //发送ee消息 
        EeManager::sendEeByView($views, $params, $auditor_ee, $subject);
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($auditor_email)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * 给创建者 发送 ee通知 email
     * @param DemandTask $model
     * @param string $mode            标题模式
     * @param string $views           视图
     * @param integer $createBy_ee    创建者ee
     * @param string $createBy_email  创建者email
     * @param string $cancel          临时变量
     */
    public  function sendCreateByNotification($model, $mode, $views, $createBy_ee = null, $createBy_email = null, $cancel = null){
        /* @var $model DemandTask */
        //传进view 模板参数
        $params = [
            'model' => $model,
            'cancel' => $cancel,
        ];
        //主题
        $subject = "课程需求-".$mode;
        
        //发送ee消息
        EeManager::sendEeByView($views, $params,$createBy_ee, $subject);
        //发送邮件消息
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($createBy_email)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * 给承接人 发送 ee通知 email
     * @param DemandTask $model
     * @param string $mode            标题模式
     * @param string $views           视图
     * @param string $uid             承接人
     * @param string $cancel          临时变量
     */
    public  function sendUndertakePersonNotification($model, $mode, $views, $uid = null, $cancel = null){
        /* @var $model DemandTask */
        $undertake = $this->getUndertakePerson($uid);
        //传进view 模板参数 
        $params = [
            'model' => $model,
            'cancel' => $cancel,
        ];
        //主题 
        $subject = "课程需求-".$mode;
        //查找承接人ee和mail 
        $undertakePerson_ee = implode(',', array_filter(ArrayHelper::getValue($undertake, 'ee')));
        $undertakePerson_mail = array_filter(ArrayHelper::getValue($undertake, 'email'));
        //发送ee消息
        EeManager::sendEeByView($views, $params,$undertakePerson_ee, $subject);
        //发送邮件消息 
        /*Yii::$app->mailer->compose($views, $params)
            ->setTo($undertakePerson_mail)
            ->setSubject($subject)
            ->send();*/
    }
    
    /**
     * jobManager 添加用户任务通知关联
     * @param DemandTask $model
     */
    public function saveJobManager($model){
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /* @var $model DemandTask */
        $auditor = $this->getAuditor($model->create_team);
        $auditorId = ArrayHelper::getValue($auditor, 'u_id');
        
        //创建job表任务
        $jobManager->createJob(AppGlobalVariables::getSystemId(), $model->id, $model->course->name, 
                '/demand/task/view?id='.$model->id, $model->getStatusName(), $model->progress);
        //添加通知
        $jobManager->addNotification(AppGlobalVariables::getSystemId(), $model->id, ArrayHelper::merge([$model->create_by], $auditorId));
    }
    
    /**
     * 设置承接人用户任务通知关联
     * @param DemandTask $model
     */
    public  function setUndertakeNotification($model){
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /* @var $model DemandTask */
        $undertake = $this->getUndertakePerson();
        $undertakeId = array_filter(ArrayHelper::getValue($undertake, 'u_id'));
       
        //更新任务通知表
        $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->getStatusProgress(), 'status' => $model->getStatusName()]); 
        //清空用户任务通知关联
        $jobManager->removeNotification(AppGlobalVariables::getSystemId(), $model->id, $undertakeId);
        //添加用户任务通知关联
        if(empty($model->undertake_person))
            $jobManager->addNotification(AppGlobalVariables::getSystemId(), $model->id, $undertakeId);
        else
            $jobManager->addNotification(AppGlobalVariables::getSystemId(), $model->id, $model->undertakePerson->u_id);
        
    }
    
    /**
     * jobManager 取消用户任务通知关联
     * @param DemandTask $model
     * @param integer|array $teamId     团队ID
     */
    public  function cancelJobManager($model, $teamId){
        $team = [];
        $undertakeId = [];
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        if(!$model->getIsStatusUndertake())
            $team = array_filter(ArrayHelper::getValue($this->getAuditor($teamId), 'u_id'));
        else
            $undertakeId = array_filter(ArrayHelper::getValue($this->getUndertakePerson(), 'u_id'));
        //全并两个数组的值
        $jobUserAll = ArrayHelper::merge(ArrayHelper::merge([$model->create_by], $team), $undertakeId);
        //修改job表任务
        $jobManager->updateJob(AppGlobalVariables::getSystemId(),$model->id,['progress'=> $model->progress, 'status'=> $model->getStatusName()]); 
        //修改通知
        $jobManager->cancelNotification(AppGlobalVariables::getSystemId(), $model->id, $jobUserAll);
    }
    
    /**
     * 获取单例
     * @return DemandNoticeTool
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DemandNoticeTool();
        }
        return self::$instance;
    }
}