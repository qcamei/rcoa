<?php
namespace frontend\modules\demand\utils;

use common\config\AppGlobalVariables;
use common\models\demand\DemandAcceptance;
use common\models\demand\DemandAppeal;
use common\models\demand\DemandAppealReply;
use common\models\demand\DemandCheck;
use common\models\demand\DemandCheckReply;
use common\models\demand\DemandDelivery;
use common\models\demand\DemandOperation;
use common\models\demand\DemandOperationUser;
use common\models\demand\DemandTask;
use common\models\demand\DemandTaskAnnex;
use common\models\demand\DemandWeight;
use common\models\demand\DemandWeightTemplate;
use common\models\demand\DemandWorkitem;
use common\models\demand\DemandWorkitemTemplate;
use common\models\team\TeamCategory;
use common\models\team\TeamMember;
use common\models\workitem\WorkitemCost;
use common\wskeee\job\JobManager;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;


class DemandAction {
    
   private static $instance = null;
   
    /**
     * 获取单例
     * @return DemandAction
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DemandAction();
        }
        return self::$instance;
    }
   
   /**
    * 创建任务操作
    * @param DemandTask $model
    * @param type $post
    */
    public function DemandCreateTask($model, $post)
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            /* @var $model DemandTask*/
            if($model->save()){
                $this->saveDemandOperation($model);
                $this->saveOperationUser($model, $model->create_by);
                $this->saveDemandTaskAnnex($model, $post);
                $this->saveDemandWorkitem($model, $post);
                $this->saveDemandWeight($model);
                DemandNotice::saveJobManager($model);
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            throw new NotFoundHttpException("操作失败！".$ex->getMessage()); 
        }
    }    
    
    /**
     * 更新任务操作
     * @param DemandTask $model
     * @param type $post
     */
    public function DemandUpdateTask($model, $post)
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            /* @var $jobManager JobManager */
            $jobManager = Yii::$app->get('jobManager');
            if($model->save()){
                DemandTaskAnnex::deleteAll(['task_id' => $model->id]);
                $this->saveDemandTaskAnnex($model, $post);
                $this->UpdateDemandWorkitem($model, $post);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['subject' => $model->course->name]);
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            throw new NotFoundHttpException("操作失败！".$ex->getMessage());
        }
    }
    
    /**
     * 创建审核任务操作
     * @param DemandCheck $model
     */
    public function DemandCreateCheck($model)
    {
        //审核人
        $auditors = $this->getTeamMembersUserLeaders(TeamCategory::TYPE_PRODUCT_CENTER, $model->demandTask->create_team);
        $auditorUid = ArrayHelper::getValue($auditors, 'user_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            /* @var $jobManager JobManager */
            $jobManager = Yii::$app->get('jobManager');
            $number = DemandTask::updateAll(['status' => DemandTask::STATUS_CHECK, 'progress' => DemandTask::$statusProgress[DemandTask::STATUS_CHECK]], ['id' => $model->demand_task_id]);
            if ($model->save() && $number > 0){
                $this->saveDemandOperation($model->demandTask, DemandTask::STATUS_CHECK);
                $this->saveOperationUser($model->demandTask,$auditorUid);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->demand_task_id, ['status'=> DemandTask::$statusNmae[DemandTask::STATUS_CHECK]]);
                $jobManager->addNotification(AppGlobalVariables::getSystemId(), $model->id, $auditorUid);
                DemandNotice::sendAuditorNotification($model->demandTask, $auditors, '任务待审核', 'demand/CreateCheck-html', $model->des);
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 通过审核回复任务操作
     * @param DemandCheckReply $model
     */
    public function DemandPassCheckReply($model)
    {
        $createTeam = $model->demandCheck->demandTask->create_team;
        //查审核人
        $auditors = $this->getTeamMembersUserLeaders(TeamCategory::TYPE_PRODUCT_CENTER, $createTeam);
        $auditorId = ArrayHelper::getValue($auditors, 'user_id');
        //查找所有承接人
        $undertakers = $this->getTeamMembersUserLeaders( TeamCategory::TYPE_CCOA_DEV_TEAM);
        $undertakerIds = ArrayHelper::getValue($undertakers, 'user_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            /* @var $jobManager JobManager */
            $jobManager = Yii::$app->get('jobManager');
            $number = DemandTask::updateAll(['status' => DemandTask::STATUS_UNDERTAKE, 'progress' => DemandTask::$statusProgress[DemandTask::STATUS_UNDERTAKE]], ['id' => $model->demandCheck->demand_task_id]);
            if ($model->save() && $number > 0){
                $this->saveDemandOperation($model->demandCheck->demandTask, DemandTask::STATUS_UNDERTAKE);
                $this->saveOperationUser($model->demandCheck->demandTask, $undertakerIds);
                $jobManager->cancelNotification(AppGlobalVariables::getSystemId(), $model->demandCheck->demand_task_id, $auditorId);
                $jobManager->addNotification(AppGlobalVariables::getSystemId(), $model->demandCheck->demand_task_id, $undertakerIds);
                DemandNotice::sendCreateByNotification($model->demandCheck->demandTask, $model->demandCheck->demandTask->createBy, '审核已通过', 'demand/PassCheckReply-html', $model->des);
                DemandNotice::sendUndertakerNotification($model->demandCheck->demandTask, $undertakers, '新任务发布', 'demand/Undertake-html', $model->des);
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 不通过审核回复任务操作
     * @param DemandCheckReply $model
     */
    public function DemandNoPassCheckReply($model)
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            /* @var $jobManager JobManager */
            $jobManager = Yii::$app->get('jobManager');
            $number = DemandTask::updateAll(['status' => DemandTask::STATUS_ADJUSTMENTING], ['id' => $model->demandCheck->demand_task_id]);
            if($model->save() && $number > 0){
                $this->saveDemandOperation($model->demandCheck->demandTask, DemandTask::STATUS_ADJUSTMENTING);
                $this->saveOperationUser($model->demandCheck->demandTask, $model->demandCheck->create_by);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->demandCheck->demand_task_id, ['status'=> DemandTask::$statusNmae[DemandTask::STATUS_ADJUSTMENTING]]);
                DemandNotice::sendCreateByNotification($model->demandCheck->demandTask, $model->demandCheck->demandTask->createBy, '审核不通过', 'demand/CreateCheckReply-html', $model->des);
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 承接任务操作
     * @param DemandTask $model
     */
    public function DemandUndertakeTask($model)
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            /* @var $jobManager JobManager */
            $jobManager = Yii::$app->get('jobManager');
            if ($model->save(false, ['team_id', 'undertake_person',  'status', 'progress'])){
                $this->saveDemandOperation($model);
                $this->saveOperationUser($model, $model->undertake_person);
                $jobManager->addNotification(AppGlobalVariables::getSystemId(), $model->id, $model->undertake_person);
                DemandNotice::sendCreateByNotification($model, $model->demandCheck->demandTask->createBy, '任务已承接', 'demand/AlreadyUndertake-html');
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 创建交付任务操作
     * @param DemandDelivery $model
     * @param boolean $is_empty                     是否为空
     */
    public function DemandCreateDelivery($model, $is_empty)
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            /* @var $jobManager JobManager */
            $jobManager = Yii::$app->get('jobManager');
            if ($model != null){
                $this->saveDemandOperation($model->demandTask, (!$is_empty ? DemandTask::STATUS_ACCEPTANCE : DemandTask::STATUS_ACCEPTANCEING));
                $this->saveOperationUser($model->demandTask, $model->demandTask->create_by);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->demand_task_id, [
                    'progress'=> !$is_empty ? DemandTask::$statusProgress[DemandTask::STATUS_ACCEPTANCE] : DemandTask::$statusProgress[DemandTask::STATUS_ACCEPTANCE], 
                    'status'=> !$is_empty ? DemandTask::$statusNmae[DemandTask::STATUS_ACCEPTANCE] : DemandTask::$statusNmae[DemandTask::STATUS_ACCEPTANCEING]
                ]);
                DemandNotice::sendCreateByNotification($model->demandTask, $model->demandTask->createBy, '任务待验收', 'demand/CreateDelivery-html', $model->des);
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 创建验收任务记录操作
     * @param DemandAcceptance $model
     */
    public function DemandCreateAcceptance($model)
    {        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            /* @var $jobManager JobManager */
            $jobManager = Yii::$app->get('jobManager');
            if($model != null && $model->pass == false){
                $this->saveDemandOperation($model->demandTask, DemandTask::STATUS_UPDATEING);
                $this->saveOperationUser($model->demandTask, $model->demandTask->undertake_person);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->demand_task_id, ['status'=> DemandTask::$statusNmae[DemandTask::STATUS_UPDATEING]]);
                DemandNotice::sendUndertakerNotification($model->demandTask, $model->demandTask->undertakePerson, '验收不通过', 'demand/CreateAcceptance-html', $model->des);
            }else if($model != null && $model->pass == true){
                $this->saveDemandOperation($model->demandTask, DemandTask::STATUS_WAITCONFIRM);
                $this->saveOperationUser($model->demandTask, $model->demandTask->undertake_person);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->demand_task_id, [
                    'progress'=> DemandTask::$statusProgress[DemandTask::STATUS_WAITCONFIRM], 
                    'status'=> DemandTask::$statusNmae[DemandTask::STATUS_WAITCONFIRM],
                ]);
                DemandNotice::sendUndertakerNotification($model->demandTask, $model->demandTask->undertakePerson, '验收通过', 'demand/WaitConfirm-html', $model->des);
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 创建申诉任务操作
     * @param DemandAppeal $model
     */
    public function DemandCreateAppeal($model)
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            /* @var $jobManager JobManager */
            $jobManager = Yii::$app->get('jobManager');
            $number = DemandTask::updateAll(['status' => DemandTask::STATUS_APPEALING], ['id' => $model->demand_task_id]);
            if ($model->save() && $number > 0){
                $this->saveDemandOperation($model->demandTask, DemandTask::STATUS_APPEALING);
                $this->saveOperationUser($model->demandTask, $model->demandTask->create_by);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->demand_task_id, ['status'=> DemandTask::$statusNmae[DemandTask::STATUS_APPEALING]]);
                DemandNotice::sendCreateByNotification($model->demandTask, $model->demandTask->createBy, '任务申诉中', 'demand/CreateAppeal-html', $model->des);
                
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 待确认任务操作
     * @param DemandTask $model
     */
    public function DemandWaitConfirm($model)
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            /* @var $jobManager JobManager */
            $jobManager = Yii::$app->get('jobManager');
            if ($model->save(false, [ 'status',  'progress', 'finished_at'])){
               $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, [
                    'progress'=> DemandTask::$statusProgress[DemandTask::STATUS_COMPLETED], 
                    'status'=> DemandTask::$statusNmae[DemandTask::STATUS_COMPLETED],
                ]);
               $jobManager->cancelNotification(AppGlobalVariables::getSystemId(), $model->id, [$model->create_by, $model->undertake_person]);
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 创建申诉回复任务操作
     * @param DemandAppealReply $model
     */
    public function DemandCreateAppealReply($model)
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            /* @var $jobManager JobManager */
            $jobManager = Yii::$app->get('jobManager');
            $number = DemandTask::updateAll(['status' => DemandTask::STATUS_WAITCONFIRM], ['id' => $model->demandAppeal->demand_task_id]);
            if ($model->save() && $number > 0){
                $this->saveDemandOperation($model->demandAppeal->demandTask, DemandTask::STATUS_WAITCONFIRM);
                $this->saveOperationUser($model->demandAppeal->demandTask, $model->demandAppeal->demandTask->undertake_person);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->demandAppeal->demand_task_id, ['status'=> DemandTask::$statusNmae[DemandTask::STATUS_WAITCONFIRM]]);
                DemandNotice::sendUndertakerNotification($model->demandAppeal->demandTask, $model->demandAppeal->demandTask->undertakePerson, '任务回复', 'demand/CreateAppealReply-html', $model->des);
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 取消任务操作
     * @param DemandTask $model
     * @param integer $oldStatus                上一个状态
     * @param type $cancel                      临时变量
     */
    public function DemandCancelTask($model, $oldStatus, $cancel)
    {
        //审核人
        $auditor = $this->getTeamMembersUserLeaders(TeamCategory::TYPE_PRODUCT_CENTER, $model->create_team);
        $auditorUid = ArrayHelper::getValue($auditor, 'user_id');
        //承接人
        $undertakers = $this->getTeamMembersUserLeaders(TeamCategory::TYPE_CCOA_DEV_TEAM);
        $undertakerUids = ArrayHelper::getValue($undertakers, 'user_id');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model->save(false, ['status'])){
                DemandNotice::cancelJobManager($model, ArrayHelper::merge($auditorUid, $undertakerUids));
                if($oldStatus == DemandTask::STATUS_CHECK){
                    DemandNotice::sendAuditorNotification($model, $auditor,'任务取消', 'demand/Cancel-html', $cancel);
                }else
                    DemandNotice::sendUndertakerNotification($model, $undertakers, '任务取消', 'demand/Cancel-html', $cancel);
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 获取所有团队领导人
     * @param TeamMemberTool $_tmTool                         
     * @param string $teamCategory                        团队分类                
     * @param integer $teamId                             团队id                   
     * @return array
     */
    public function getTeamMembersUserLeaders($teamCategory, $teamId = null)
    {
        $_tmTool = TeamMemberTool::getInstance();
        $leaders = $_tmTool->getTeamMembersUserLeaders($teamCategory);
        $leaderUser = [];
        foreach ($leaders as $item){
            if($item['team_id'] == $teamId || $teamId == null)
                $leaderUser[] = $item;
        }
        
        return [
            'user_id' => ArrayHelper::getColumn($leaderUser, 'u_id'),
            'nickname' => ArrayHelper::getColumn($leaderUser, 'nickname'),
            'guid' => ArrayHelper::getColumn($leaderUser, 'guid'),
            'email' => ArrayHelper::getColumn($leaderUser, 'email'),
        ];
    }
    
    /**
     * 保存操作到表里
     * @param DemandTask $model 
     * @param integer $status                        状态
     */
    private function saveDemandOperation($model, $status = null)
    {
        $values[] = [
            'task_id' => $model->id,
            'task_status' => $status == null ? $model->status : $status,
            'action_id' => Yii::$app->controller->id.'/'.Yii::$app->controller->action->id,
            'create_by' => Yii::$app->user->id,
            'created_at' => time(),
            'updated_at' => time(),
        ];
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model != null && $values != null){
                /** 添加$values数组到表里 */
                Yii::$app->db->createCommand()->batchInsert(DemandOperation::tableName(), 
                ['task_id', 'task_status', 'action_id', 'create_by', 'created_at', 'updated_at'], $values)->execute();
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 保存操作用户到表里
     * @param DemandTask $model 
     * @param array $userId                         用户ID
     */
    private function saveOperationUser($model, $userId)
    {
        $userId = !is_array($userId) ? [$userId] : $userId;
        $operation = (new Query())
                ->from(DemandOperation::tableName())
                ->where(['task_id' => $model->id])
                ->orderBy('id desc')
                ->one();
        
        $values = [];
        /** 重组提交的数据为$values数组 */
        foreach($userId as $value)
        {
            $values[] = [
                'operation_id' => $operation['id'],
                'u_id' => $value,
            ];
        }
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model != null && $values != null){
                /** 添加$values数组到表里 */
                Yii::$app->db->createCommand()->batchInsert(DemandOperationUser::tableName(), 
                ['operation_id', 'u_id'], $values)->execute();
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 保存附件到表里
     * @param DemandTask $model               
     * @param type $post                    
     */
    private function saveDemandTaskAnnex($model, $post)
    {
        $annexNames = ArrayHelper::getValue($post, 'DemandTaskAnnex.name', []);
        $annexPaths = ArrayHelper::getValue($post, 'DemandTaskAnnex.path', []);
        /** 重组提交的数据为$values数组 */
        $values = [];
        foreach ($annexNames as $key => $value) {
            $values[] = [
                'task_id' => $model->id,
                'name' => $value,
                'path' => $annexPaths[$key],
            ];
        }
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model != null){
                /** 添加$values数组到表里 */
                Yii::$app->db->createCommand()->batchInsert(DemandTaskAnnex::tableName(), [
                'task_id', 'name', 'path'], $values)->execute();
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 保存数据到需求工作项表
     * @param DemandTask $model
     * @param type $post
     */
    private function saveDemandWorkitem($model, $post)
    {
        $query_child = (new Query())
            ->select(['CONCAT(DemandWorkitemTemp.workitem_id, "_", DemandWorkitemTemp.is_new) AS id',
                'DemandWorkitemTemp.workitem_type_id','DemandWorkitemTemp.workitem_id','DemandWorkitemTemp.is_new','DemandWorkitemTemp.value_type','DemandWorkitemTemp.index',
                'if(DemandWorkitemTemp.is_new = TRUE, Workitemcost.cost_new, Workitemcost.cost_remould) AS cost'
            ])
            ->from(['DemandWorkitemTemp'=> DemandWorkitemTemplate::tableName()])
            ->leftJoin(['Workitemcost'=> WorkitemCost::tableName()], 'Workitemcost.workitem_id = DemandWorkitemTemp.workitem_id')
            ->orderBy(['Workitemcost.target_month' => SORT_DESC, 'DemandWorkitemTemp.workitem_id' => SORT_DESC,  'DemandWorkitemTemp.is_new'=> SORT_DESC]);
        
        $results = (new Query())->select(['*'])->from(['QueryChild' => $query_child])->groupBy('QueryChild.id')->all();
        $values = ArrayHelper::getValue($post, 'DemandWorkitem.value');

        $workitems = [];
        if(!empty($results)){
            foreach ($results as $index => $workitem) {
                $workitem += [
                    'demand_task_id' => $model->id, 
                    'value' => $values[$workitem['id']],
                    'created_at' => strtotime($model->plan_check_harvest_time),
                    'updated_at' => $model->updated_at
                ];
                unset($workitem['id']);
                $workitems[] = $workitem;
            }
        }
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model != null && $workitems != null){
                /** 添加$values数组到表里 */
                Yii::$app->db->createCommand()->batchInsert(DemandWorkitem::tableName(),[
                    'workitem_type_id', 'workitem_id', 'is_new',  'value_type', 'index', 'cost', 'demand_task_id', 'value', 'created_at', 'updated_at'
                ], $workitems)->execute();
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
        
    /**
     * 更新需求工作项
     * @param DemandTask $model   
     * @param type $post   
     */
    private function UpdateDemandWorkitem($model, $post)
    {
        $values = ArrayHelper::getValue($post, 'DemandWorkitem.value');
      
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model != null && $values != null){
                foreach ($values as $key => $value) {
                    \Yii::$app->db->createCommand()->update(DemandWorkitem::tableName(), ['value' => $value], ['id' => $key])->execute();
                }
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
        
    }
    
    /**
     * 保存数据到需求比重表
     * @param DemandTask $model
     */
    private function saveDemandWeight($model)
    {
        $results = (new Query())
            ->select([
                'DemandWeightTemp.workitem_type_id','DemandWeightTemp.weight',
                'DemandWeightTemp.sl_weight','DemandWeightTemp.zl_weight',
            ])
            ->from(['DemandWeightTemp'=> DemandWeightTemplate::tableName()])
            ->all();
       
        $weights = [];
        if(!empty($results)){
            foreach ($results as $index => $weight) {
                $weight += [
                    'demand_task_id' => $model->id,
                    'created_at' => $model->created_at,
                    'updated_at' => $model->updated_at
                ];
                $weights[] = $weight;
            }
        }
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model != null && $weights != null){
                /** 添加$values数组到表里 */
                Yii::$app->db->createCommand()->batchInsert(DemandWeight::tableName(),[
                    'workitem_type_id', 'weight', 'sl_weight',  'zl_weight', 'demand_task_id', 'created_at', 'updated_at'
                ], $weights)->execute();
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    
    /**
     * 获取是否为审核人
     * @param integer $teamId           团队ID
     * @return boolean                  true为是
     */
    public function getIsAuditor($teamId)
    {
        $_tmTool = TeamMemberTool::getInstance();
        $teamMembers = $_tmTool->getTeamMembersByTeamId($teamId);
        foreach ($teamMembers as $member){
            if($member['u_id'] == Yii::$app->user->id && $member['is_leader'] == TeamMember::TEAMLEADER){
                return true;
            }
        }
        return false;
    }
}
