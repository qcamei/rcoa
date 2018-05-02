<?php

namespace frontend\modules\worksystem\utils;

use common\config\AppGlobalVariables;
use common\models\demand\DemandTask;
use common\models\team\TeamCategory;
use common\models\team\TeamMember;
use common\models\User;
use common\models\worksystem\WorksystemAddAttributes;
use common\models\worksystem\WorksystemAnnex;
use common\models\worksystem\WorksystemContentinfo;
use common\models\worksystem\WorksystemOperation;
use common\models\worksystem\WorksystemOperationUser;
use common\models\worksystem\WorksystemTask;
use common\models\worksystem\WorksystemTaskProducer;
use common\wskeee\job\JobManager;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\db\Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class WorksystemAction 
{
    private static $instance = null;
    
    /**
     * 获取单例
     * @return WorksystemAction
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new WorksystemAction();
        }
        return self::$instance;
    }
       
    /**
     * 创建任务操作
     * @param WorksystemTask $model
     * @param WorksystemTool $_wsTool
     * @param WorksystemNotice $_wsNotice
     * @param type $post
     */
    public function CreateTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $_wsNotice = WorksystemNotice::getInstance();
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->saveWorksystemAddAttributes($model, $post);
                $this->saveWorksystemContentinfo($model, $post);
                $this->saveWorksystemAnnex($model->id, $post);
                $this->saveWorksystemOperation($model->id, $model->status, '创建', '任务创建');
                $this->saveWorksystemOperationUser($model->id, $model->create_by);
                $_wsNotice->saveJobManager($model);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            throw new NotFoundHttpException("操作失败！".$ex->getMessage()); 
        }
    }
    
    /**
     * 更新任务操作
     * @param WorksystemTask $model
     * @param WorksystemTool $_wsTool
     * @param type $post
     */
    public function UpdateTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->saveWorksystemAddAttributes($model, $post);
                $this->saveWorksystemContentinfo($model, $post);
                $this->saveWorksystemAnnex($model->id, $post);
                $this->saveWorksystemOperation($model->id, $model->status, '修改', '任务修改');
                $this->saveWorksystemOperationUser($model->id, $model->create_by);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            throw new NotFoundHttpException("操作失败！".$ex->getMessage()); 
        }
    }
    
    /**
     * 取消任务操作
     * @param WorksystemTask $model
     * @param WorksystemTool $_wsTool
     * @param type $post
     */
    public function CancelTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->saveWorksystemOperation($model->id, WorksystemTask::STATUS_CANCEL, '取消', '因客观原因取消原定任务', $des);
                $this->saveWorksystemOperationUser($model->id, $model->create_by, $model->is_brace, $model->is_epiboly);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 提交审核任务操作
     * @param WorksystemTask $model
     * @param WorksystemNotice $_wsNotice
     * @param JobManager $jobManager
     * @param type $post
     */
    public function SubmitCheckTask($model, $post)
    {
        $_wsNotice = WorksystemNotice::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $users = $this->getTeamMembersUserLeaders($model->create_team);
        $teamUserId = ArrayHelper::getValue($users, 'user_id');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                if($model->status == WorksystemTask::STATUS_WAITCHECK)
                    $this->saveWorksystemOperation($model->id, WorksystemTask::STATUS_WAITCHECK, '审核', '提交审核');
                else
                    $this->saveWorksystemOperation($model->id, WorksystemTask::STATUS_CHECKING, '审核', '提交审核');
                $this->saveWorksystemOperationUser($model->id, $teamUserId);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status' => $model->getStatusName()]);
                if(!in_array($model->create_by, $teamUserId)){
                    $jobManager->addNotification(AppGlobalVariables::getSystemId(), $model->id, $teamUserId);
                    $_wsNotice->sendAssignPeopleNotification($model, $users, '审核申请', 'worksystem/_submit_check_task_html');
                }
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 创建审核任务操作
     * @param WorksystemTask $model
     * @param WorksystemNotice $_wsNotice
     * @param JobManager $jobManager
     * @param type $post
     */
    public function CreateCheckTask($model, $post)
    {
        $_wsNotice = WorksystemNotice::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $users = $this->getTeamMembersUserLeaders($model->create_team);
        $leaderNickname = ArrayHelper::getValue($users, 'nickname');
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->saveWorksystemOperation($model->id, WorksystemTask::STATUS_ADJUSTMENTING, '审核', 0, $des);
                $this->saveWorksystemOperationUser($model->id, $model->create_by);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status' => $model->getStatusName()]);
                $_wsNotice->sendCreateByNotification($model, '审核申请结果', 'worksystem/_create_check_task_html', $leaderNickname, $des);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 创建指派任务操作
     * @param WorksystemTask $model
     * @param WorksystemTool $_wsTool
     * @param WorksystemNotice $_wsNotice
     * @param JobManager $jobManager
     * @param type $post
     */
    public function CreateAssignTask($model, $post)
    {
        $_wsNotice = WorksystemNotice::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $teamUsers = $this->getTeamMembersUserLeaders();
        $allUserIds = ArrayHelper::merge([Yii::$app->user->id], ArrayHelper::getValue($teamUsers, 'user_id'));
        $producers = ArrayHelper::getValue($post, 'WorksystemProducer.team_member_id');
        $external_team = ArrayHelper::getValue($post, 'WorksystemTask.external_team');
        if($model->create_team != $external_team)
            $model->is_brace = WorksystemTask::SEEK_BRACE_MARK;
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $jobManager->removeNotification(AppGlobalVariables::getSystemId(), $model->id, $allUserIds);
                if(in_array($model->create_by, $allUserIds)){
                    $jobManager->addNotification (AppGlobalVariables::getSystemId(), $model->id, $model->create_by);
                    $jobManager->setNotificationHasReady(AppGlobalVariables::getSystemId(), $model->create_by, $model->id);
                }
                $this->saveWorksystemTaskProducer($model->id, $producers);
                $users = $this->getWorksystemTaskProducer($model->id);
                $producerUserId = ArrayHelper::getValue($users, 'user_id');
                $producerName = ArrayHelper::getValue($users, 'nickname');
                $this->saveWorksystemOperation($model->id, WorksystemTask::STATUS_TOSTART, '分配', '选定制作人【'.implode(',', $producerName).'】');
                $this->saveWorksystemOperationUser($model->id, $producerUserId);
                $_wsNotice->setAssignNotification($model, $producerUserId);
                $_wsNotice->sendCreateByNotification($model, '分配人员', 'worksystem/_create_assign_task_html', $producerName);
                $_wsNotice->sendProducerNotification($model, $users, '分配人员', 'worksystem/_create_assign_task_html');
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 创建支撑任务操作
     * @param WorksystemTask $model
     * @param WorksystemNotice $_wsNotice
     * @param JobManager $jobManager
     * @param type $post
     */
    public function CreateBraceTask($model, $post)
    {
        $_wsNotice = WorksystemNotice::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $users = $this->getTeamMembersUserLeaders();
        $leadersUserId = ArrayHelper::getValue($users, 'user_id');
        $leadersName = array_unique(ArrayHelper::getValue($users, 'nickname'));
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->saveWorksystemOperation($model->id, WorksystemTask::STATUS_WAITASSIGN, '分配', '寻求对象【'.implode(',', $leadersName).'】', $des);
                $this->saveWorksystemOperationUser($model->id, $leadersUserId, WorksystemTask::SEEK_BRACE_MARK);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status' => $model->getStatusName()]);
                $jobManager->addNotification(AppGlobalVariables::getSystemId(), $model->id, $leadersUserId);
                $_wsNotice->sendAssignPeopleNotification($model, $users, '寻求支撑', 'worksystem/_create_brace_task_html', $des);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 取消支撑任务操作
     * @param WorksystemTask $model
     * @param WorksystemTool $_wsTool
     * @param JobManager $jobManager
     * @param type $post
     */
    public function CancelBraceTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $leaderUsers = $this->getTeamMembersUserLeaders();
        $leaderUserId = ArrayHelper::getValue($leaderUsers, 'user_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->saveWorksystemOperation($model->id, WorksystemTask::STATUS_CHECKING, '分配', '取消支撑', $des);
                $this->saveWorksystemOperationUser($model->id, Yii::$app->user->id);
                $jobManager->removeNotification(AppGlobalVariables::getSystemId(), $model->id, $leaderUserId);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 创建外包任务操作
     * @param WorksystemTask $model
     * @param WorksystemNotice $_wsNotice
     * @param JobManager $jobManager
     * @param type $post
     */
    public function CreateEpibolyTask($model, $post)
    {
        $_wsNotice = WorksystemNotice::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $epibolyUsers = $this->getEpibolyTeamMembers();
        $epibolyUserId = ArrayHelper::getValue($epibolyUsers, 'user_id');
        $epibolyName = array_unique(ArrayHelper::getValue($epibolyUsers, 'nickname'));
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->saveWorksystemOperation($model->id, WorksystemTask::STATUS_WAITUNDERTAKE, '分配', '寻求对象【'.implode(',', $epibolyName).'】', $des);
                $this->saveWorksystemOperationUser($model->id, $epibolyUserId, null, WorksystemTask::SEEK_EPIBOLY_MARK);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status' => $model->getStatusName()]);
                $jobManager->addNotification(AppGlobalVariables::getSystemId(), $model->id, $epibolyUserId);
                $_wsNotice->sendEpibolyNotification($model, $epibolyUsers, '外包', 'worksystem/_create_epiboly_task_html', $des);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 取消外包任务操作
     * @param WorksystemTask $model
     * @param JobManager $jobManager
     * @param type $post
     */
    public function CancelEpibolyTask($model, $post)
    {
        $jobManager = Yii::$app->get('jobManager');
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $teamUsers = $this->getTeamMembersUserLeaders($model->create_team);
        $teamUserId = ArrayHelper::getValue($teamUsers, 'user_id');
        $epibolyUsers = $this->getEpibolyTeamMembers();
        $epibolyUserId = ArrayHelper::getValue($epibolyUsers, 'user_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->saveWorksystemOperation($model->id, WorksystemTask::STATUS_CHECKING, '分配', '取消外包', $des);
                $this->saveWorksystemOperationUser($model->id, $teamUserId);
                $jobManager->removeNotification(AppGlobalVariables::getSystemId(), $model->id, $epibolyUserId);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 开始制作任务操作
     * @param WorksystemTask $model
     * @param WorksystemTool $_wsTool
     * @param JobManager $jobManager
     * @param type $post
     */
    public function StartMakeTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $producers = $this->getWorksystemTaskProducer($model->id);
        $producerUserId = ArrayHelper::getValue($producers, 'user_id');
        //$producerName = '制作人：'.implode(',', ArrayHelper::getValue($this->getWorksystemTaskProducer($model->id), 'nickname'));
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->saveWorksystemOperation($model->id, WorksystemTask::STATUS_WORKING, '制作', '开始制作时间');
                $this->saveWorksystemOperationUser($model->id, $producerUserId);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status' => $model->getStatusName()]);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 承接制作任务操作
     * @param WorksystemTask $model
     * @param WorksystemNotice $_wsNotice
     * @param JobManager $jobManager
     * @param type $post
     */
    public function CreateUndertakeTask($model, $post)
    {
        $_wsNotice = WorksystemNotice::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $producers = ArrayHelper::getValue($post, 'WorksystemProducer.team_member_id');
        $epibolyUsers = $this->getEpibolyTeamMembers(true);
        $epibolyUserId = ArrayHelper::getValue($epibolyUsers, 'user_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->saveWorksystemTaskProducer($model->id, $producers);
                $producerUsers = $this->getWorksystemTaskProducer($model->id);
                $producerUserId = ArrayHelper::getValue($producerUsers, 'user_id');
                $producerName = ArrayHelper::getValue($producerUsers, 'nickname');
                $this->saveWorksystemOperation($model->id, WorksystemTask::STATUS_TOSTART, '分配', '承接人【'.implode(',', $producerName).'】');
                $this->saveWorksystemOperationUser($model->id, $producerUserId);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status' => $model->getStatusName()]);
                $jobManager->removeNotification(AppGlobalVariables::getSystemId(), $model->id, $epibolyUserId);
                $jobManager->addNotification(AppGlobalVariables::getSystemId(), $model->id, $producerUserId);
                $_wsNotice->sendCreateByNotification($model, '承接', 'worksystem/_create_undertake_task_html', $producerName);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 取消承接制作任务操作
     * @param WorksystemTask $model
     * @param WorksystemTool $_wsTool
     * @param JobManager $jobManager
     * @param type $post
     */
    public function CancelUndertakeTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $epibolyUserid = ArrayHelper::getValue($this->getEpibolyTeamMembers(true), 'user_id');
        $allUsers = ArrayHelper::merge([Yii::$app->user->id], $epibolyUserid);
        $epibolyName = '寻求对象【'.implode(',', ArrayHelper::getValue($this->getEpibolyTeamMembers(true), 'nickname')).'】';
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                Yii::$app->db->createCommand()->delete(WorksystemTaskProducer::tableName(), ['worksystem_task_id' => $model->id])->execute();
                $this->saveWorksystemOperation($model->id, WorksystemTask::STATUS_TOSTART, '分配', $epibolyName, $des);
                $this->saveWorksystemOperationUser($model->id, $epibolyUserid);
                $jobManager->removeNotification(AppGlobalVariables::getSystemId(), $model->id, $allUsers);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 提交验收任务操作
     * @param WorksystemTask $model
     * @param WorksystemTool $_wsTool
     * @param WorksystemNotice $_wsNotice
     * @param JobManager $jobManager
     * @param type $post
     */
    public function SubmitAcceptanceTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $_wsNotice = WorksystemNotice::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $producerUsers = $this->getWorksystemTaskProducer($model->id);
        $producerName = ArrayHelper::getValue($producerUsers, 'nickname');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->updateWorksystemContentinfo($model, $post);
                if($model->status == WorksystemTask::STATUS_WAITACCEPTANCE)
                    $this->saveWorksystemOperation($model->id, WorksystemTask::STATUS_WAITACCEPTANCE, '验收', '提交验收', $des);
                else
                    $this->saveWorksystemOperation($model->id, WorksystemTask::STATUS_ACCEPTANCEING, '验收', '提交验收', $des);
                $this->saveWorksystemOperationUser($model->id, $model->create_by);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status' => $model->getStatusName()]);   
                $_wsNotice->sendCreateByNotification($model, '验收申请', 'worksystem/_submit_acceptance_task_html', $producerName);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 创建验收任务操作
     * @param WorksystemTask $model
     * @param WorksystemTool $_wsTool
     * @param WorksystemNotice $_wsNotice
     * @param JobManager $jobManager
     * @param type $post
     */
    public function CreateAcceptanceTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $_wsNotice = WorksystemNotice::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $content = ArrayHelper::getValue($post, 'WorksystemOperation.content');
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $producerUsers = $this->getWorksystemTaskProducer($model->id);
        $producerUserId = ArrayHelper::getValue($producerUsers, 'user_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->saveWorksystemOperation($model->id, WorksystemTask::STATUS_UPDATEING, '验收', $content, $des);
                $this->saveWorksystemOperationUser($model->id, $producerUserId);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status' => $model->getStatusName()]);   
                $_wsNotice->sendProducerNotification($model, $producerUsers, '验收结果', 'worksystem/_create_acceptance_task_html', $des);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 完成验收任务操作
     * @param WorksystemTask $model
     * @param WorksystemTool $_wsTool
     * @param WorksystemNotice $_wsNotice
     * @param JobManager $jobManager
     * @param type $post
     */
    public function CompleteAcceptanceTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $_wsNotice = WorksystemNotice::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $producerUsers = $this->getWorksystemTaskProducer($model->id);
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->saveWorksystemOperation($model->id, WorksystemTask::STATUS_COMPLETED, '验收', 1);
                $this->saveWorksystemOperationUser($model->id, $model->create_by);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status' => $model->getStatusName()]);   
                $_wsNotice->sendProducerNotification($model, $producerUsers, '验收通过', 'worksystem/_complete_acceptance_task_html');
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 获取所有团队开发经理
     * @param TeamMemberTool $_tmTool                         
     * @param integer $teamId                          团队id                   
     * @return array
     */
    public function getTeamMembersUserLeaders($teamId = null)
    {
        $_tmTool = TeamMemberTool::getInstance();
        $leaders = $_tmTool->getTeamMembersUserLeaders(TeamCategory::TYPE_WORKSYSTEM_TEAM);
        $leaderUser = [];
        if($teamId == null){
            foreach ($leaders as $item){
                if($item['u_id'] != Yii::$app->user->id)
                    $leaderUser[] = $item;
            }
        }else {
            foreach ($leaders as $item){
                if($item['team_id'] == $teamId)
                    $leaderUser[] = $item;
            }
        } 
        
        $users = [
            'user_id' => ArrayHelper::getColumn($leaderUser, 'u_id'),
            'nickname' => ArrayHelper::getColumn($leaderUser, 'nickname'),
            'guid' => ArrayHelper::getColumn($leaderUser, 'guid'),
            'email' => ArrayHelper::getColumn($leaderUser, 'email'),
        ];
        
        return $users;
    }
    
    /**
     * 获取所有外包团队成员
     * @param TeamMemberTool $_tmTool                         
     * @param boolean $is_cancel                    是否是取消操作：默认为false               
     * @return array
     */
    public function getEpibolyTeamMembers($is_cancel = false)
    {
        $epibolyUsers = [];
        $_tmTool = TeamMemberTool::getInstance();
        $teamCategory = $_tmTool->getTeamsByCategoryId(TeamCategory::TYPE_EPIBOLY_TEAM);
        $epibolys = $_tmTool->getTeamMembersByTeamId(ArrayHelper::getColumn($teamCategory, 'id'));
        if($is_cancel){
           foreach ($epibolys as $item) {
               if($item['u_id'] != Yii::$app->user->id)
                   $epibolyUsers[] = $item;
           }
        }
        $epibolyUsers = !$is_cancel ? $epibolys : $epibolyUsers;
        
        $users = [
            'user_id' => ArrayHelper::getColumn($epibolyUsers, 'u_id'),
            'nickname' => ArrayHelper::getColumn($epibolyUsers, 'nickname'),
            'guid' => ArrayHelper::getColumn($epibolyUsers, 'guid'),
            'email' => ArrayHelper::getColumn($epibolyUsers, 'email'),
        ];
        
        return $users;
    }
    
    /**
     * 获取工作系统任务制作人
     * @param integer $taskId                         工作系统任务id
     * @return array
     */
    public function getWorksystemTaskProducer($taskId)
    {
        $producers = (new Query())
                ->select(['Producer.worksystem_task_id', 'Team_member.u_id', 'User.nickname', 'User.guid', 'User.email'])
                ->from(['Producer' => WorksystemTaskProducer::tableName()])
                ->leftJoin(['Team_member' => TeamMember::tableName()], 'Team_member.id = Producer.team_member_id')
                ->leftJoin(['User' => User::tableName()], 'User.id = Team_member.u_id')
                ->where(['Producer.worksystem_task_id' => $taskId])
                ->all();
        
        $user = [
            'user_id' => ArrayHelper::map($producers, 'worksystem_task_id', 'u_id'),
            'nickname' => ArrayHelper::map($producers, 'worksystem_task_id', 'nickname'),
            'guid' => ArrayHelper::map($producers, 'worksystem_task_id', 'guid'),
            'email' => ArrayHelper::map($producers, 'worksystem_task_id', 'email'),
        ];
        
        return $user;
    }
    
    /**
     * 保存工作系统操作
     * @param integer $taskId                   工作系统任务id
     * @param integer $status                   状态
     * @param string $title                     标题
     * @param string $content                   内容
     * @param string $des                       描述
     */
    public function saveWorksystemOperation($taskId, $status, $title = null, $content = null, $des = null)
    {
        $values[] = [
            'worksystem_task_id' => $taskId,
            'worksystem_task_status' => $status,
            'controller_action' => Yii::$app->controller->id.'/'.Yii::$app->controller->action->id,
            'title' => $title,
            'content' => $content,
            'des' => $des == null ? '无' : $des,
            'create_by' => Yii::$app->user->id,
            'created_at' => time(),
            'updated_at' => time(),
        ];
        if($values != null)
            /** 添加$values数组到表里 */
            Yii::$app->db->createCommand()->batchInsert(WorksystemOperation::tableName(), [
                'worksystem_task_id', 'worksystem_task_status', 'controller_action',
                'title', 'content', 'des',
                'create_by', 'created_at', 'updated_at'], $values)->execute();
    }
    
    /**
     * 保存工作系统操作用户
     * @param integer $taskId                   工作系统任务id
     * @param array $userIds                    用户id
     * @param boolean $brace                    支撑标识：0表示不支撑，1表示支撑
     * @param boolean $epiboly                  外包标识：0表示不外包，1表示外包
     */
    public function saveWorksystemOperationUser($taskId, $userIds, $brace = null, $epiboly = null)
    {
        $operation = (new Query()) 
                ->from(WorksystemOperation::tableName())
                ->where(['worksystem_task_id' => $taskId])
                ->orderBy('id desc')
                ->one();
        
        $values = [];
        /** 重组提交的数据为$values数组 */
        $userIds = !is_array($userIds) ? [$userIds] : $userIds;
        foreach($userIds as $key => $value)
        {
            $values[] = [
                'worksystem_operation_id' => $operation['id'],
                'user_id' => $value,
                'brace_mark' => $brace == null ? WorksystemTask::CANCEL_BRACE_MARK : $brace,
                'epiboly_mark' => $epiboly == null ? WorksystemTask::CANCEL_EPIBOLY_MARK : $epiboly,
                'created_at' => time(),
                'updated_at' => time(),
            ];
        }
        
        if($values != null)
            /** 添加$values数组到表里 */
            Yii::$app->db->createCommand()->batchInsert(WorksystemOperationUser::tableName(), 
            ['worksystem_operation_id', 'user_id', 'brace_mark', 'epiboly_mark', 'created_at', 'updated_at'], $values)->execute();
    }
    
    /**
     * 保存工作系统任务附加属性
     * @param WorksystemTask $model
     * @param type $post
     */
    public function saveWorksystemAddAttributes($model, $post)
    {
        $attributes = ArrayHelper::getValue($post, 'WorksystemAddAttributes');
        
        $values = [];
        if(isset($attributes['value'])){
            foreach ($attributes['value'] as $index => $items){
                $values[] = [
                    'worksystem_task_id' => $model->id,
                    'worksystem_attributes_id' => $index,
                    'value' => !is_array($items) ? $items : implode(",", $items),
                    'index' => $attributes['index'][$index],
                    'is_delete' => $attributes['is_delete'][$index],
                    'created_at' => time(),
                    'updated_at' => time(),
                ];
            }
        } else {
            throw new NotFoundHttpException("操作失败！附加属性值不能为空！"); 
        }
        if($values != null){
            Yii::$app->db->createCommand()->delete(WorksystemAddAttributes::tableName(), ['worksystem_task_id' => $model->id])->execute();
            /** 添加$values数组到表里 */
            Yii::$app->db->createCommand()->batchInsert(WorksystemAddAttributes::tableName(),[
                'worksystem_task_id', 'worksystem_attributes_id', 'value',  'index', 'is_delete', 'created_at', 'updated_at'
            ], $values)->execute();
        }
    }
    
    /**
     * 保存工作系统任务内容信息
     * @param WorksystemTask $model
     * @param WorksystemQuery $_wsQuery
     * @param DemandTask $results
     * @param type $post
     */
    public function saveWorksystemContentinfo($model, $post)
    {
        $_wsQuery = WorksystemQuery::getInstance();
        $results = $_wsQuery->findDemandTaskTable($model->course_id);
        $contents = ArrayHelper::getValue($post, 'WorksystemContentinfo');
        $budgetCost = ArrayHelper::getValue($post, 'WorksystemTask.budget_cost');
        $budgetBonus = $budgetCost * $results->score;
        
        $values = [];
        if($contents != null){
            foreach ($contents as $index => $items){
                $items += [
                    'worksystem_task_id' => $model->id,
                    'created_at' => time(),
                    'updated_at' => time(),
                ];

                $values[] = $items;
            }
        } else {
            throw new NotFoundHttpException("操作失败！内容信息不能为空！"); 
        }
        if($values != null){
            Yii::$app->db->createCommand()->update(WorksystemTask::tableName(), ['budget_bonus' => $budgetBonus], ['id' => $model->id])->execute();
            Yii::$app->db->createCommand()->delete(WorksystemContentinfo::tableName(), ['worksystem_task_id' => $model->id])->execute();
            /** 添加$values数组到表里 */
            Yii::$app->db->createCommand()->batchInsert(WorksystemContentinfo::tableName(),[
                'worksystem_content_id', 'is_new',  'price', 'budget_number', 'budget_cost', 'worksystem_task_id', 'created_at', 'updated_at'
            ], $values)->execute();
            
        }
    }
    
    /**
     * 修改工作系统任务内容信息
     * @param WorksystemTask $model
     * @param WorksystemQuery $_wsQuery
     * @param DemandTask $results
     * @param type $post
     */
    public function updateWorksystemContentinfo($model, $post)
    {
        $_wsQuery = WorksystemQuery::getInstance();
        $results = $_wsQuery->findDemandTaskTable($model->course_id);
        $contents = ArrayHelper::getValue($post, 'WorksystemContentinfo');
        $realityCost = ArrayHelper::getValue($post, 'WorksystemTask.reality_cost');
        $realityBonus = $realityCost * $results->score;
        
        Yii::$app->db->createCommand()->update(WorksystemTask::tableName(), ['reality_bonus' => $realityBonus], ['id' => $model->id])->execute();
        
        foreach ($contents as $index => $items){
            Yii::$app->db->createCommand()->update(WorksystemContentinfo::tableName(), [
                'reality_number' => $items['reality_number'], 'reality_cost' => $items['reality_cost']
            ], ['id' => $index])->execute();
        }
    }
    
    
    
    /**
     * 保存工作系统任务制作人
     * @param integer $taskId                               工作系统任务id
     * @param array $producers                              制作人
     */
    public function saveWorksystemTaskProducer($taskId, $producers)
    {
        $producers = !is_array($producers) ? [$producers] : $producers;
        $values = [];
        /** 重组提交的数据为$values数组 */
        foreach($producers as $key => $value)
        {
            $values[] = [
                'worksystem_task_id' => $taskId,
                'team_member_id' => $value,
                'created_at' => time(),
                'updated_at' => time(),
            ];
        }
        
        if($values != null){
            Yii::$app->db->createCommand()->delete(WorksystemTaskProducer::tableName(), ['worksystem_task_id' => $taskId])->execute();
            /** 添加$values数组到表里 */
            Yii::$app->db->createCommand()->batchInsert(WorksystemTaskProducer::tableName(), 
            ['worksystem_task_id', 'team_member_id', 'created_at', 'updated_at'], $values)->execute();
        }
    }

    /**
     * 保存附件到表里
     * @param integer $taskId                       工作系统任务id
     * @param type $post                          
     */
    public function saveWorksystemAnnex($taskId, $post)
    {
        $annex = ArrayHelper::getValue($post, 'WorksystemAnnex');
        $annex = $annex != null ? $annex : ['name'=> [], 'path'=> []];
        
        /** 重组提交的数据为$values数组 */
        $values = [];
        foreach ($annex['name'] as $key => $value) {
           $values[] = [
               'worksystem_task_id' => $taskId,
               'name' => $value,
               'path' => $annex['path'][$key],
               'create_by' => Yii::$app->user->id,
               'created_at' => time(),
               'updated_at' => time(),
           ];
        }
        
        Yii::$app->db->createCommand()->delete(WorksystemAnnex::tableName(), ['worksystem_task_id' => $taskId])->execute();
        if($values != null){
            /** 添加$values数组到表里 */
            Yii::$app->db->createCommand()->batchInsert(WorksystemAnnex::tableName(), [
                'worksystem_task_id', 'name', 'path', 'create_by', 'created_at', 'updated_at'], $values)->execute();
        }
    }
}
