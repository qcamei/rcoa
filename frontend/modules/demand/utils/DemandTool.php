<?php
namespace frontend\modules\demand\utils;

use common\config\AppGlobalVariables;
use common\models\demand\DemandCheck;
use common\models\demand\DemandOperation;
use common\models\demand\DemandOperationUser;
use common\models\demand\DemandTask;
use common\models\demand\DemandTaskAnnex;
use common\models\demand\DemandTaskAuditor;
use common\wskeee\job\JobManager;
use frontend\modules\teamwork\utils\TeamworkTool;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\NotAcceptableHttpException;


class DemandTool {
    
   private static $instance = null;
   
   /**
    * 数据表
    * @var Query 
    */
   public static $table = null;
   
   /**
    * 创建任务操作
    * @param DemandTask $model
    * @param type $post
    */
    public function CreateTask($model, $post)
    {
        /* @var $demandNotice DemandNoticeTool */
        $demandNotice = DemandNoticeTool::getInstance();
        $user = ArrayHelper::getValue($demandNotice->getAuditor($model->create_team), 'u_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            /* @var $model DemandTask*/
            if($model->save()){
                $this->saveDemandOperation($model->id, $model->status);
                $this->saveOperationUser($model->id, $user);
                $this->saveDemandTaskAnnex($model->id, (!empty($post['DemandTaskAnnex']) ? $post['DemandTaskAnnex'] : []));
                $demandNotice->saveJobManager($model);
                $demandNotice->sendAuditorNotification($model, $model->create_team, '任务待审核', 'demand/Create-html');
            }
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            $model->getErrors();
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 更新任务操作
     * @param DemandTask $model
     * @param type $post
     */
    public function UpdateTask($model, $post)
    {
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model->save()){
                DemandTaskAnnex::deleteAll(['task_id' => $model->id]);
                $this->saveDemandTaskAnnex($model->id, (!empty($post['DemandTaskAnnex']) ? $post['DemandTaskAnnex'] : []));
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['subject' => $model->course->name]);
            }
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            $model->getErrors();
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 通过审核任务操作
     * @param DemandTask $model
     */
    public function PassCheckTask($model)
    {
        /* @var $demandNotice DemandNoticeTool */
        $demandNotice = DemandNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        $undertakeId = ArrayHelper::getValue($demandNotice->getUndertakePerson(), 'u_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if ($model->save(false, ['status', 'progress'])){
                $this->saveDemandOperation($model->id, $model->status);
                $this->saveOperationUser($model->id, $undertakeId);
                $demandNotice->setUndertakeNotification($model);
                $demandNotice->sendCreateByNotification($model, '审核已通过', 'demand/PassCheck-html', $model->createBy->ee);
                $demandNotice->sendUndertakePersonNotification($model, '新任务发布', 'demand/Undertake-html');
            }
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            $model->getErrors();
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 创建审核记录操作
     * @param DemandCheck $model
     */
    public function CreateCheckTask($model)
    {
        /* @var $demandNotice DemandNoticeTool */
        $demandNotice = DemandNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            $number = DemandTask::updateAll(['status' => DemandTask::STATUS_ADJUSTMENTING], ['id' => $model->task_id]);
            if($model->save() && $number > 0){
                $this->saveDemandOperation($model->task_id, DemandTask::STATUS_ADJUSTMENTING);
                $this->saveOperationUser($model->task_id, [$model->task->create_by]);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->task_id, ['status'=> $model->task->getStatusName()]);
                $demandNotice->sendCreateByNotification ($model, '审核不通过', 'demand/CreateCheck-html', $model->task->createBy->ee);
            }
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            $model->getErrors();
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 提交审核操作
     * @param DemandCheck $model
     */
    public function SubmitCheckTask($model)
    {
        /* @var $demandNotice DemandNoticeTool */
        $demandNotice = DemandNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        $user = ArrayHelper::getValue($demandNotice->getAuditor($model->task->create_team), 'u_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            $number = DemandTask::updateAll(['status'=> DemandTask::STATUS_CHECKING], ['id' => $model->task_id]);
            if($model->save(false, ['complete_time', 'status']) && $number > 0){
                $this->saveDemandOperation($model->task_id, DemandTask::STATUS_CHECKING);
                $this->saveOperationUser($model->task_id, $user);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->task_id, ['status'=> $model->task->getStatusName()]);
                $demandNotice->sendAuditorNotification($model, $model->task->create_team, '任务待审核', 'demand/SubmitCheck-html');
            }
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            $model->getErrors();
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 承接任务操作
     * @param DemandTask $model
     */
    public function UndertakeTask($model)
    {
        /* @var $demandNotice DemandNoticeTool */
        $demandNotice = DemandNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if ($model->save(false, ['team_id', 'undertake_person', 'status', 'progress'])){
                $this->saveDemandOperation($model->id, $model->status);
                $this->saveOperationUser($model->id, [$model->undertakePerson->u_id]);
                $demandNotice->setUndertakeNotification($model);
                $demandNotice->sendCreateByNotification($model, '任务已承接 ', 'demand/AlreadyUndertake-html', $model->createBy->ee);
            }
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            $model->getErrors();
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 完成任务操作
     * @param DemandTask $model
     */
    public function CompleteTask($model)
    {
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if ($model->save(false, ['status', 'progress', 'reality_check_harvest_time'])){
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status'=>$model->getStatusName()]);
                $jobManager->cancelNotification(AppGlobalVariables::getSystemId(), $model->id, [$model->create_by, $model->undertakePerson->u_id]);
            }
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            $model->getErrors();
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 恢复任务操作
     * @param DemandTask $model
     */
    public function RecoveryTask($model)
    {
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if ($model->save(false, ['progress', 'status', 'reality_check_harvest_time'])){
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status'=>$model->getStatusName()]);
                $jobManager->removeNotification(AppGlobalVariables::getSystemId(), $model->id, $model->undertakePerson->u_id);
                $jobManager->addNotification (AppGlobalVariables::getSystemId(), $model->id, $model->undertakePerson->u_id);
            }
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            $model->getErrors();
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 取消任务操作
     * @param DemandTask $model
     * @param type $cancel              临时变量
     */
    public function CancelTask($model, $cancel)
    {
        /* @var $demandNotice DemandNoticeTool */
        $demandNotice = DemandNoticeTool::getInstance();
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model->save(false, ['status'])){
                $demandNotice->cancelJobManager($model, $model->create_team);
                if(!$model->getIsStatusAcceptance())
                    $demandNotice->sendAuditorNotification($model, $model->create_team,'任务取消', 'demand/Cancel-html', $cancel);
                else
                    $demandNotice->sendUndertakePersonNotification($model, '任务取消', 'demand/Cancel-html', $cancel);
            }
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            $model->getErrors();
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
   
    /**
     * 查询所有需求任务结果
     * @param integer $id                      任务ID
     * @param integer $status                  状态
     * @return Query
     */
    public function getDemandTaskInfo($id = null, $status = 1)
    {
        /* @var $dtQuery DemandQuery */
        $dtQuery = DemandQuery::getInstance();
        /* @var $results ActiveQuery */
        $results = $dtQuery->getDemandTaskTable();
        $results->andFilterWhere([
            'Demand_task.id' => $id,
        ]);
        $results->andFilterWhere(['IN', 'Demand_task.status', 
            ($status == DemandTask::STATUS_DEFAULT ? DemandTask::$defaultStatus : $status)
        ]);
        
        return $results;
    }
    
    /**
     * 保存附件到表里
     * @param integer $taskId               任务ID
     * @param type $post                    
     */
    public function saveDemandTaskAnnex($taskId, $post)
    {
        /* @var $twTool TeamworkTool*/
        $twTool = TeamworkTool::getInstance();
        /** 重组提交的数据为$values数组 */
        $values = [];
        if(!empty($post)){
            if(!($twTool->isSameValue($post['name']) || $twTool->isSameValue($post['path']))){
                foreach ($post['name'] as $key => $value) {
                   $values[] = [
                       'task_id' => $taskId,
                       'name' => $value,
                       'path' => $post['path'][$key],
                   ];
                }

                /** 添加$values数组到表里 */
                Yii::$app->db->createCommand()->batchInsert(DemandTaskAnnex::tableName(), [
                    'task_id', 'name', 'path'], $values)->execute();
            }else{
                throw new NotAcceptableHttpException('请不要重复上传相同附件！');
            }
        }
    }
    
    /**
     * 保存操作到表里
     * @param integer $taskId              任务ID
     * @param integer $status              状态
     */
    public function saveDemandOperation($taskId, $status){
        $values[] = [
            'task_id' => $taskId,
            'task_status' => $status,
            'action_id' => Yii::$app->controller->action->id,
            'create_by' => Yii::$app->user->id,
            'created_at' => time(),
            'updated_at' => time(),
        ];
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(DemandOperation::tableName(), 
        ['task_id', 'task_status', 'action_id', 'create_by', 'created_at', 'updated_at'], $values)->execute();
    }
    
    /**
     * 保存操作用户到表里
     * @param integer $taskId            需求任务ID
     * @param array $uId                 用户ID
     */
    public function saveOperationUser($taskId, $uId){
        $operation = DemandOperation::find()
                     ->where(['task_id' => $taskId])
                     ->orderBy('id desc')
                     ->one();
        
        $values = [];
        /** 重组提交的数据为$values数组 */
        foreach($uId as $key => $value)
        {
            $values[] = [
                'operation_id' => $operation->id,
                'u_id' => $value,
            ];
        }
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(DemandOperationUser::tableName(), 
        ['operation_id', 'u_id'], $values)->execute();
    }
    
    /**
     * 获取承接人所在团队成员ID
     * @return integer|array    
     */
    public function getHotelTeamMemberId()
    {
        $teamMember = TeamMemberTool::getInstance()->getUserLeaderTeamMembers(Yii::$app->user->id);
        $teamMemberId = ArrayHelper::getColumn($teamMember, 'id');
        if(!empty($teamMemberId) && count($teamMemberId) == 1)
            return $teamMemberId[0];
        else
            return ArrayHelper::map($teamMember, 'id', 'user.nickname');
    }
    
    /**
     * 获取是否属于自己操作
     * @param array $taskId                          任务ID
     * @param array $status                          状态
     * @return boolean                               true为是
     */ 
    public function getIsBelongToOwnOperate($taskId, $status)
    {
        $operation = [];
        $isBelong = [];
        $operates = DemandOperation::find()
                   ->where(['task_id' => $taskId])
                   ->all();
        if(!empty($operates)){
            /* @var $value DemandOperation */
            foreach ($operates as $value) {
                $operation[$value->task_id] = [
                    'id' => $value->id,
                    'status' => $value->task_status == $status[$value->task_id] ? true : false,
                ];
            }
            $operationUsers = DemandOperationUser::find()
                            ->where(['operation_id' => ArrayHelper::getColumn($operation, 'id')])
                            ->with('operation')
                            ->asArray()
                            ->all();
            $operations = ArrayHelper::map($operation, 'id', 'status');
            $operationUser = ArrayHelper::map($operationUsers, 'id', 'u_id', 'operation_id');
            $taskIds = ArrayHelper::map($operationUsers, 'operation_id', 'operation.task_id');
            
            if(!empty($operationUser)){
                /* @var $value DemandOperationUser */
                foreach ($operationUser as $index => $element){
                    if(in_array(Yii::$app->user->id, $element) && $operations[$index])
                        $isBelong[$taskIds[$index]] = true;
                    else
                        $isBelong[$taskIds[$index]] = false;
                }
            }
        }
        return $isBelong;
    }
    
    /**
     * 获取是否为审核人
     * @param integer $teamId           团队ID
     * @return boolean                  true为是
     */
    public function getIsAuditor($teamId)
    {
        $auditor = DemandTaskAuditor::findOne(['team_id' => $teamId]);
        if(!empty($auditor) && isset($auditor)){
            if(Yii::$app->user->id == $auditor->u_id)
                return true;
        }
        return false;
    }
    
    /**
     * 获取是否为承接人
     * @return boolean                 true为是
     */
    public function getIsUndertakePerson()
    {
        /* @var $demandNotice DemandNoticeTool */
        $demandNotice = DemandNoticeTool::getInstance();
        $undertake = ArrayHelper::getValue($demandNotice->getUndertakePerson(), 'u_id');
        if(!empty($undertake) && in_array(Yii::$app->user->id, $undertake))
            return true;
        
        return false;
    }
    
    /**
     * 获取已存在的记录是否有未完成
     * @param integer $taskId           任务
     * @return boolean                  true 为是      
     */
    public function getIsCompleteCheck ($taskId)
    {
        $check =  (new Query())
                  ->from(self::$table)
                  ->where(['task_id' => $taskId])
                  ->all();
        if(!empty($check) || isset($check)){
            $isComplete = ArrayHelper::getColumn($check, 'status');
            if(in_array(DemandCheck::STATUS_NOTCOMPLETE, $isComplete))
                return true;  
        }
        return false;
    }

    /**
     * 获取单例
     * @return DemandTool
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DemandTool();
        }
        return self::$instance;
    }
}
