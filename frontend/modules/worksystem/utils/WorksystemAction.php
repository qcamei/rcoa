<?php

namespace frontend\modules\worksystem\utils;

use common\config\AppGlobalVariables;
use common\models\worksystem\WorksystemTask;
use common\models\worksystem\WorksystemTaskProducer;
use common\wskeee\job\JobManager;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;



class WorksystemAction 
{
    private static $instance = null;
   
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
                $_wsTool->saveWorksystemAddAttributes($model, $post);
                $_wsTool->saveWorksystemContentinfo($model, $post);
                $_wsTool->saveWorksystemAnnex($model->id, $post);
                $_wsTool->saveWorksystemOperation($model->id, $model->status, '任务创建', '任务创建');
                $_wsTool->saveWorksystemOperationUser($model->id, $model->create_by);
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
                $_wsTool->saveWorksystemAddAttributes($model, $post);
                $_wsTool->saveWorksystemContentinfo($model, $post);
                $_wsTool->saveWorksystemAnnex($model->id, $post);
                $_wsTool->saveWorksystemOperation($model->id, $model->status, '任务修改', '任务修改');
                $_wsTool->saveWorksystemOperationUser($model->id, $model->create_by);
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
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_CANCEL, '取消任务', '因客观原因取消原定任务', $des);
                $_wsTool->saveWorksystemOperationUser($model->id, $model->create_by, $model->is_brace, $model->is_epiboly);
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
     * @param WorksystemTool $_wsTool
     * @param WorksystemNotice $_wsNotice
     * @param JobManager $jobManager
     * @param type $post
     */
    public function SubmitCheckTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $_wsNotice = WorksystemNotice::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $users = $_wsTool->getTeamAssignPeople($model->create_team);
        $teamUserId = ArrayHelper::getValue($users, 'user_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                if($model->status == WorksystemTask::STATUS_WAITCHECK)
                    $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_WAITCHECK, '提交审核', '提交审核');
                else
                    $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_CHECKING, '提交审核', '提交审核');
                $_wsTool->saveWorksystemOperationUser($model->id, $teamUserId);
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
     * @param WorksystemTool $_wsTool
     * @param WorksystemNotice $_wsNotice
     * @param JobManager $jobManager
     * @param type $post
     */
    public function CreateCheckTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $_wsNotice = WorksystemNotice::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $teamNickname = ArrayHelper::getValue($_wsTool->getTeamAssignPeople($model->create_team), 'nickname');
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_ADJUSTMENTING, '审核不通过', 0, $des);
                $_wsTool->saveWorksystemOperationUser($model->id, $model->create_by);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status' => $model->getStatusName()]);
                $_wsNotice->sendCreateByNotification($model, '审核申请结果', 'worksystem/_create_check_task_html', $teamNickname, $des);
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
        $_wsTool = WorksystemTool::getInstance();
        $_wsNotice = WorksystemNotice::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $teamUsers = $_wsTool->getTeamAssignPeople($model->create_team);
        $teamUserId = ArrayHelper::getValue($teamUsers, 'user_id');
        $leaderUsers = $_wsTool->getTeamMembersUserLeaders($model);
        $leadersUserId = ArrayHelper::getValue($leaderUsers, 'user_id');
        $allUserId = ArrayHelper::merge($teamUserId, $leadersUserId);
        $allUserIds = ArrayHelper::merge([Yii::$app->user->id], $allUserId);
        $producers = ArrayHelper::getValue($post, 'WorksystemProducer.team_member_id');
       
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
                $_wsTool->saveWorksystemTaskProducer($model->id, $producers);
                $users = $_wsTool->getWorksystemTaskProducer($model->id);
                $producerUserId = ArrayHelper::getValue($users, 'user_id');
                $producerName = ArrayHelper::getValue($users, 'nickname');
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_TOSTART, '指派', '选定制作人【'.implode(',', $producerName).'】');
                $_wsTool->saveWorksystemOperationUser($model->id, $producerUserId);
                $_wsNotice->setAssignNotification($model, $producerUserId);
                $_wsNotice->sendCreateByNotification($model, '任务-指派', 'worksystem/_create_assign_task_html', $producerName);
                $_wsNotice->sendProducerNotification($model, $users, '任务-指派', 'worksystem/_create_assign_task_html');
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
     * @param WorksystemTool $_wsTool
     * @param WorksystemNotice $_wsNotice
     * @param JobManager $jobManager
     * @param type $post
     */
    public function CreateBraceTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $_wsNotice = WorksystemNotice::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $users = $_wsTool->getTeamMembersUserLeaders($model);
        $leadersUserId = ArrayHelper::getValue($users, 'user_id');
        $leadersName = ArrayHelper::getValue($users, 'nickname');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_WAITASSIGN, '寻求支撑', '寻求对象【'.implode(',', $leadersName).'】', $des);
                $_wsTool->saveWorksystemOperationUser($model->id, $leadersUserId, WorksystemTask::SEEK_BRACE_MARK);
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
        $teamUsers = $_wsTool->getTeamAssignPeople($model->create_team);
        $leaderUsers = $_wsTool->getTeamMembersUserLeaders($model);
        $teamUserId = ArrayHelper::getValue($teamUsers, 'user_id');
        $leaderUserId = ArrayHelper::getValue($leaderUsers, 'user_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_CHECKING, '取消支撑', '取消支撑', $des);
                $_wsTool->saveWorksystemOperationUser($model->id, $teamUserId);
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
     * @param WorksystemTool $_wsTool
     * @param WorksystemNotice $_wsNotice
     * @param JobManager $jobManager
     * @param type $post
     */
    public function CreateEpibolyTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $_wsNotice = WorksystemNotice::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $epibolyUsers = $_wsTool->getEpibolyTeamMembers();
        $epibolyUserId = ArrayHelper::getValue($epibolyUsers, 'user_id');
        $epibolyName = ArrayHelper::getValue($epibolyUsers, 'nickname');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_WAITUNDERTAKE, '寻求外包', '寻求对象【'.implode(',', $epibolyName).'】', $des);
                $_wsTool->saveWorksystemOperationUser($model->id, $epibolyUserId, null, WorksystemTask::SEEK_EPIBOLY_MARK);
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
     * @param WorksystemTool $_wsTool
     * @param JobManager $jobManager
     * @param type $post
     */
    public function CancelEpibolyTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $teamUsers = $_wsTool->getTeamAssignPeople($model->create_team);
        $teamUserId = ArrayHelper::getValue($teamUsers, 'user_id');
        $epibolyUsers = $_wsTool->getEpibolyTeamMembers();
        $epibolyUserId = ArrayHelper::getValue($epibolyUsers, 'user_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_CHECKING, '取消外包', '取消外包', $des);
                $_wsTool->saveWorksystemOperationUser($model->id, $teamUserId);
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
        $producerUserId = ArrayHelper::getValue($_wsTool->getWorksystemTaskProducer($model->id), 'user_id');
        //$producerName = '制作人：'.implode(',', ArrayHelper::getValue($_wsTool->getWorksystemTaskProducer($model->id), 'nickname'));
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_WORKING, '开始制作', '开始制作时间');
                $_wsTool->saveWorksystemOperationUser($model->id, $producerUserId);
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
     * @param WorksystemTool $_wsTool
     * @param WorksystemNotice $_wsNotice
     * @param JobManager $jobManager
     * @param type $post
     */
    public function CreateUndertakeTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $_wsNotice = WorksystemNotice::getInstance();
        $jobManager = Yii::$app->get('jobManager');
        $producers = ArrayHelper::getValue($post, 'WorksystemProducer.team_member_id');
        $epibolyUserid = ArrayHelper::getValue($_wsTool->getEpibolyTeamMembers(true), 'user_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemTaskProducer($model->id, $producers);
                $producerUsers = $_wsTool->getWorksystemTaskProducer($model->id);
                $producerUserId = ArrayHelper::getValue($producerUsers, 'user_id');
                $producerName = ArrayHelper::getValue($producerUsers, 'nickname');
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_TOSTART, '承接', '承接人【'.implode(',', $producerName).'】');
                $_wsTool->saveWorksystemOperationUser($model->id, $producerUserId);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status' => $model->getStatusName()]);
                $jobManager->removeNotification(AppGlobalVariables::getSystemId(), $model->id, $epibolyUserid);
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
        $epibolyUserid = ArrayHelper::getValue($_wsTool->getEpibolyTeamMembers(true), 'user_id');
        $allUsers = ArrayHelper::merge([Yii::$app->user->id], $epibolyUserid);
        $epibolyName = '寻求对象【'.implode(',', ArrayHelper::getValue($_wsTool->getEpibolyTeamMembers(true), 'nickname')).'】';
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                Yii::$app->db->createCommand()->delete(WorksystemTaskProducer::tableName(), ['worksystem_task_id' => $model->id])->execute();
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_TOSTART, '取消承接', $epibolyName, $des);
                $_wsTool->saveWorksystemOperationUser($model->id, $epibolyUserid);
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
        $producerUsers = $_wsTool->getWorksystemTaskProducer($model->id);
        $producerName = ArrayHelper::getValue($producerUsers, 'nickname');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->updateWorksystemContentinfo($model, $post);
                if($model->status == WorksystemTask::STATUS_WAITACCEPTANCE)
                    $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_WAITACCEPTANCE, '提交验收', '提交验收', $des);
                else
                    $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_ACCEPTANCEING, '提交验收', '提交验收', $des);
                $_wsTool->saveWorksystemOperationUser($model->id, $model->create_by);
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
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $producerUsers = $_wsTool->getWorksystemTaskProducer($model->id);
        $producerUserId = ArrayHelper::getValue($producerUsers, 'user_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_UPDATEING, '验收不通过', 0, $des);
                $_wsTool->saveWorksystemOperationUser($model->id, $producerUserId);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status' => $model->getStatusName()]);   
                $_wsNotice->sendProducerNotification($model, $producerUsers, '验收不通过', 'worksystem/_create_acceptance_task_html', $des);
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
        $producerUsers = $_wsTool->getWorksystemTaskProducer($model->id);
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_COMPLETED, '验收通过', 1);
                $_wsTool->saveWorksystemOperationUser($model->id, $model->create_by);
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
     * 获取单例
     * @return WorksystemAction
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new WorksystemAction();
        }
        return self::$instance;
    }
}
