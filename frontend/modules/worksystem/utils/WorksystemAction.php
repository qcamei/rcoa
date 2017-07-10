<?php

namespace frontend\modules\worksystem\utils;

use common\models\worksystem\WorksystemTask;
use common\models\worksystem\WorksystemTaskProducer;
use Exception;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;



class WorksystemAction 
{
    private static $instance = null;
   
    /**
     * 创建任务操作
     * @param WorksystemTask $model
     * @param WorksystemTool $_wsTool
     * @param type $post
     */
    public function CreateTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemAddAttributes($model, $post);
                $_wsTool->saveWorksystemContentinfo($model, $post);
                $_wsTool->saveWorksystemOperation($model->id, $model->status, '任务创建', '任务创建');
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
     * 提交审核任务操作
     * @param WorksystemTask $model
     * @param WorksystemTool $_wsTool
     * @param type $post
     */
    public function SubmitCheckTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $teamUserid = ArrayHelper::getValue($_wsTool->getTeamAssignPeople($model->create_team), 'user_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                if($model->status == WorksystemTask::STATUS_WAITCHECK)
                    $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_WAITCHECK, '提交审核', '提交审核');
                else
                    $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_CHECKING, '提交审核', '提交审核');
                $_wsTool->saveWorksystemOperationUser($model->id, $teamUserid);
            }else
                throw new \Exception($model->getErrors());
            
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
     * @param type $post
     */
    public function CreateCheckTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_ADJUSTMENTING, '审核不通过', 0, $des);
                $_wsTool->saveWorksystemOperationUser($model->id, $model->create_by);
            }else
                throw new \Exception($model->getErrors());
            
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
     * @param type $post
     */
    public function CreateAssignTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $producers = ArrayHelper::getValue($post, 'WorksystemProducer.team_member_id');
       
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemTaskProducer($model->id, $producers);
                $producerUserId = ArrayHelper::getValue($_wsTool->getWorksystemTaskProducer($model->id), 'user_id');
                $producerName = '选定制作人【'.implode(',', ArrayHelper::getValue($_wsTool->getWorksystemTaskProducer($model->id), 'nickname')).'】';
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_TOSTART, '指派', $producerName);
                $_wsTool->saveWorksystemOperationUser($model->id, $producerUserId);
            }else
                throw new \Exception($model->getErrors());
            
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
     * @param type $post
     */
    public function CreateBraceTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $leadersUserid = ArrayHelper::getValue($_wsTool->getTeamMembersUserLeaders($model), 'user_id');
        $leadersName = '寻求对象【'.implode(',', ArrayHelper::getValue($_wsTool->getTeamMembersUserLeaders($model), 'nickname')).'】';
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_WAITASSIGN, '寻求支撑', $leadersName, $des);
                $_wsTool->saveWorksystemOperationUser($model->id, $leadersUserid, WorksystemTask::SEEK_BRACE_MARK);
            }else
                throw new \Exception($model->getErrors());
            
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
     * @param type $post
     */
    public function CancelBraceTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $teamUserid = ArrayHelper::getValue($_wsTool->getTeamAssignPeople($model->create_team), 'user_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_CHECKING, '取消支撑', '取消支撑', $des);
                $_wsTool->saveWorksystemOperationUser($model->id, $teamUserid);
            }else
                throw new \Exception($model->getErrors());
            
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
     * @param type $post
     */
    public function CreateEpibolyTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $epibolyUserid = ArrayHelper::getValue($_wsTool->getEpibolyTeamMembers(), 'user_id');
        $epibolyName = '寻求对象【'.implode(',', ArrayHelper::getValue($_wsTool->getEpibolyTeamMembers(), 'nickname')).'】';
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_WAITUNDERTAKE, '寻求外包', $epibolyName, $des);
                $_wsTool->saveWorksystemOperationUser($model->id, $epibolyUserid, null, WorksystemTask::SEEK_EPIBOLY_MARK);
            }else
                throw new \Exception($model->getErrors());
            
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
     * @param type $post
     */
    public function CancelEpibolyTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $teamUserid = ArrayHelper::getValue($_wsTool->getTeamAssignPeople($model->create_team), 'user_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_CHECKING, '取消外包', '取消外包', $des);
                $_wsTool->saveWorksystemOperationUser($model->id, $teamUserid);
            }else
                throw new \Exception($model->getErrors());
            
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
     * @param type $post
     */
    public function StartMakeTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $producerUserId = ArrayHelper::getValue($_wsTool->getWorksystemTaskProducer($model->id), 'user_id');
        //$producerName = '制作人：'.implode(',', ArrayHelper::getValue($_wsTool->getWorksystemTaskProducer($model->id), 'nickname'));
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_WORKING, '开始制作', '开始制作');
                $_wsTool->saveWorksystemOperationUser($model->id, $producerUserId);
            }else
                throw new \Exception($model->getErrors());
            
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
     * @param type $post
     */
    public function CreateUndertakeTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $producers = ArrayHelper::getValue($post, 'WorksystemProducer.team_member_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemTaskProducer($model->id, $producers);
                $producerUserId = ArrayHelper::getValue($_wsTool->getWorksystemTaskProducer($model->id), 'user_id');
                $producerName = '承接人【'.implode(',', ArrayHelper::getValue($_wsTool->getWorksystemTaskProducer($model->id), 'nickname')).'】';
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_TOSTART, '承接', $producerName);
                $_wsTool->saveWorksystemOperationUser($model->id, $producerUserId);
            }else
                throw new \Exception($model->getErrors());
            
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
     * @param type $post
     */
    public function CancelUndertakeTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $epibolyUserid = ArrayHelper::getValue($_wsTool->getEpibolyTeamMembers(true), 'user_id');
        $epibolyName = '寻求对象【'.implode(',', ArrayHelper::getValue($_wsTool->getEpibolyTeamMembers(true), 'nickname')).'】';
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                Yii::$app->db->createCommand()->delete(WorksystemTaskProducer::tableName(), ['worksystem_task_id' => $model->id])->execute();
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_TOSTART, '取消承接', $epibolyName, $des);
                $_wsTool->saveWorksystemOperationUser($model->id, $epibolyUserid);
            }else
                throw new \Exception($model->getErrors());
            
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
     * @param type $post
     */
    public function SubmitAcceptanceTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        
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
            }else
                throw new \Exception($model->getErrors());
            
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
     * @param type $post
     */
    public function CreateAcceptanceTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        $des = ArrayHelper::getValue($post, 'WorksystemOperation.des');
        $producerUserId = ArrayHelper::getValue($_wsTool->getWorksystemTaskProducer($model->id), 'user_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_UPDATEING, '验收不通过', 0, $des);
                $_wsTool->saveWorksystemOperationUser($model->id, $producerUserId);
            }else
                throw new \Exception($model->getErrors());
            
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
     * @param type $post
     */
    public function CompleteAcceptanceTask($model, $post)
    {
        $_wsTool = WorksystemTool::getInstance();
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $_wsTool->saveWorksystemOperation($model->id, WorksystemTask::STATUS_COMPLETED, '验收通过', 1);
                $_wsTool->saveWorksystemOperationUser($model->id, $model->create_by);
            }else
                throw new \Exception($model->getErrors());
            
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
