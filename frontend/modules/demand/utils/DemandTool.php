<?php
namespace frontend\modules\demand\utils;

use common\config\AppGlobalVariables;
use common\models\demand\DemandAcceptance;
use common\models\demand\DemandCheck;
use common\models\demand\DemandDelivery;
use common\models\demand\DemandOperation;
use common\models\demand\DemandOperationUser;
use common\models\demand\DemandTask;
use common\models\demand\DemandTaskAnnex;
use common\models\demand\DemandTaskAuditor;
use common\models\demand\DemandWeight;
use common\models\demand\DemandWorkitem;
use common\models\team\TeamCategory;
use common\wskeee\job\JobManager;
use frontend\modules\teamwork\utils\TeamworkTool;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;


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
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            /* @var $model DemandTask*/
            if($model->save()){
                $this->saveDemandOperation($model->id, $model->status);
                $this->saveOperationUser($model->id, [$model->create_by]);
                $this->saveDemandTaskAnnex($model->id, (!empty($post['DemandTaskAnnex']) ? $post['DemandTaskAnnex'] : []));
                $this->saveDemandWorkitem($model);
                $this->saveDemandWeight($model);
                $demandNotice->saveJobManager($model);
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
     * 任务提交审核操作
     * @param DemandTask $model
     */
    public function TaskSubmitCheck($model)
    {
        /* @var $demandNotice DemandNoticeTool */
        $demandNotice = DemandNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        $user = ArrayHelper::getValue($demandNotice->getAuditor($model->create_team), 'u_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if ($model->save(false, ['status', 'progress'])){
                $this->saveDemandOperation($model->id, $model->status);
                $this->saveOperationUser($model->id, $user);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['status'=> DemandTask::$statusNmae[DemandTask::STATUS_CHECK]]);
                $demandNotice->sendAuditorNotification($model, $model->create_team, '任务待审核', 'demand/Create-html');
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
                $this->UpdateDemandWorkitem($post);
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
     * 通过审核任务操作
     * @param DemandTask $model
     */
    public function PassCheckTask($model)
    {
        /* @var $demandNotice DemandNoticeTool */
        $demandNotice = DemandNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /* @var $authManager RbacManager */
        $authManager = Yii::$app->authManager;
        $auditor = $demandNotice->getAuditor($model->create_team);
        $auditorId = implode(',', array_filter(ArrayHelper::getValue($auditor, 'u_id')));
        //查找所有承接人
        $undertakePerson = $authManager->getItemUsers(RbacName::ROLE_DEMAND_UNDERTAKE_PERSON);
        $undertakeId = array_filter(ArrayHelper::getColumn($undertakePerson, 'id'));
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if ($model->save(false, ['status', 'progress'])){
                $this->saveDemandOperation($model->id, $model->status);
                $this->saveOperationUser($model->id, $undertakeId);
                $demandNotice->setUndertakeNotification($model);
                $jobManager->cancelNotification(AppGlobalVariables::getSystemId(), $model->id, $auditorId);
                $demandNotice->sendCreateByNotification($model, '审核已通过', 'demand/PassCheck-html', $model->createBy->ee);
                $demandNotice->sendUndertakePersonNotification($model, '新任务发布', 'demand/Undertake-html');
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
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->task_id, ['status'=> DemandTask::$statusNmae[DemandTask::STATUS_ADJUSTMENTING]]);
                $demandNotice->sendCreateByNotification ($model, '审核不通过', 'demand/CreateCheck-html', $model->task->createBy->ee);
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
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->task_id, ['status'=> DemandTask::$statusNmae[DemandTask::STATUS_CHECKING]]);
                $demandNotice->sendAuditorNotification($model, $model->task->create_team, '任务待审核', 'demand/SubmitCheck-html');
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
            if ($model->save(false, ['team_id', 'undertake_person',  'develop_principals', 'status', 'progress'])){
                $this->saveDemandOperation($model->id, $model->status);
                $this->saveOperationUser($model->id, [$model->undertake_person]);
                $demandNotice->setUndertakeNotification($model);
                $demandNotice->sendCreateByNotification($model, '任务已承接 ', 'demand/AlreadyUndertake-html', $model->createBy->ee);
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
    public function CreateDeliveryTask($model, $is_empty)
    {
        /* @var $demandNotice DemandNoticeTool */
        $demandNotice = DemandNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if ($model !== null){
                $this->saveDemandOperation($model->demand_task_id, (!$is_empty ? DemandTask::STATUS_ACCEPTANCE : DemandTask::STATUS_ACCEPTANCEING));
                $this->saveOperationUser($model->demand_task_id, [$model->demandTask->create_by]);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->demand_task_id, [
                    'progress'=> !$is_empty ? DemandTask::$statusProgress[DemandTask::STATUS_ACCEPTANCE] : DemandTask::$statusProgress[DemandTask::STATUS_ACCEPTANCE], 
                    'status'=> !$is_empty ? DemandTask::$statusNmae[DemandTask::STATUS_ACCEPTANCE] : DemandTask::$statusNmae[DemandTask::STATUS_ACCEPTANCEING]
                ]);
                $demandNotice->sendCreateByNotification($model, '任务待验收', 'demand/CreateDelivery-html', $model->demandTask->createBy->ee);
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
     * 创建验收记录操作
     * @param DemandAcceptance $model
     */
    public function CreateAcceptanceTask($model)
    {
        /* @var $demandNotice DemandNoticeTool */
        $demandNotice = DemandNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model !== null && $model->pass == false){
                $this->saveDemandOperation($model->demand_task_id, DemandTask::STATUS_UPDATEING);
                $this->saveOperationUser($model->demand_task_id, [$model->demandTask->developPrincipals->u_id]);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->demand_task_id, ['status'=> DemandTask::$statusNmae[DemandTask::STATUS_UPDATEING]]);
                $demandNotice->sendDevelopPrincipalsNotification ($model, '验收不通过', 'demand/CreateAcceptance-html', $model->demandTask->developPrincipals->user->ee);
            }else if($model !== null && $model->pass == true){
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->demand_task_id, [
                    'progress'=> DemandTask::$statusProgress[DemandTask::STATUS_COMPLETED], 
                    'status'=> DemandTask::$statusNmae[DemandTask::STATUS_COMPLETED],
                ]);
                $jobManager->cancelNotification(AppGlobalVariables::getSystemId(), $model->demand_task_id, [$model->demandTask->create_by, $model->demandTask->undertake_person]);
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
                $jobManager->removeNotification(AppGlobalVariables::getSystemId(), $model->id, $model->undertake_person);
                $jobManager->addNotification (AppGlobalVariables::getSystemId(), $model->id, $model->undertake_person);
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            //var_dump($ex->getMessage());exit;
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 取消任务操作
     * @param DemandTask $model
     * @param integer $oldStatus                上一个状态
     * @param type $cancel                      临时变量
     */
    public function CancelTask($model, $oldStatus, $cancel)
    {
        /* @var $demandNotice DemandNoticeTool */
        $demandNotice = DemandNoticeTool::getInstance();
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model->save(false, ['status'])){
                $demandNotice->cancelJobManager($model, $model->create_team);
                if($oldStatus == DemandTask::STATUS_CHECK){
                    $demandNotice->sendAuditorNotification($model, $model->create_team,'任务取消', 'demand/Cancel-html', $cancel);
                }else
                    $demandNotice->sendUndertakePersonNotification($model, '任务取消', 'demand/Cancel-html', $cancel);
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
     * 查询所有需求任务结果
     * @param integer $id                           任务ID
     * @param integer $status                       状态
     * @param string $createBy                      创建者
     * @param string $producer                      承接人
     * @param string $assignPerson                  审核人
     * @param integer $itemTypeId                   行业ID
     * @param integer $itemId                       层次/类型ID
     * @param integer $itemChildId                  专业/工种ID
     * @param integer $courseId                     课程ID
     * @param integer $teamId                       团队ID
     * @param string $keyword                       关键字
     * @param string $time                          时间段
     * @return Query
     */
    public function getDemandTaskInfo($id = null, $status = 1, $createBy = null, $undertakePerson = null, $auditor = null,
        $itemTypeId = null, $itemId = null, $itemChildId = null, $courseId = null, $teamId = null, $keyword = null, $time = null, $mark = null)
    {
        /* @var $rbacManager RbacManager */  
        $rbacManager = \Yii::$app->authManager;
        /* @var $dtQuery DemandQuery */
        $dtQuery = DemandQuery::getInstance();
        /* @var $results ActiveQuery */
        $results = $dtQuery->findDemandTaskTable();
        /* @var $teamMember TeamMemberTool */
        $teamMember = TeamMemberTool::getInstance();
        $is_createBy = $teamMember->isContaineForCategory(\Yii::$app->user->id, TeamCategory::TYPE_PRODUCT_CENTER);
        $is_undertakePerson = $rbacManager->isRole(RbacName::ROLE_DEMAND_UNDERTAKE_PERSON, \Yii::$app->user->id);
        $is_auditor = $this->getIsAuditor('', \Yii::$app->user->id);
        if($is_createBy || $is_auditor || $is_undertakePerson){
            $results->andFilterWhere(['or',[$mark == null ? 'or' : 'and', 
                ['Demand_task.create_by' => $createBy], ['Demand_task.undertake_person' => $undertakePerson]],
                isset($auditor) ? new Expression("IF(Demand_task.`status` < 10 && Demand_task.`status` != 1, Demand_task_auditor.u_id = '$auditor', '')") : NUll
            ]);
        }
        
        if($is_undertakePerson && $undertakePerson != null && $status != null){
            $results->orWhere(new Expression("IF(Demand_task.`status` = 10, Demand_task.undertake_person IS NULL, '')"));
            $results->orderBy(new Expression("FIELD(Demand_task.`status`, 10, 1, 5, 6, 7, 11, 12, 13, 14)")); 
        } else {
            $results->orderBy('Demand_task.id DESC');
        } 
        
        $results->andFilterWhere([
            'Demand_task.id' => $id,
            'Demand_task.item_type_id' => $itemTypeId,
            'Demand_task.item_id' => $itemId,
            'Demand_task.item_child_id' => $itemChildId,
            'Demand_task.course_id' => $courseId,
            'Demand_task.team_id'=> $teamId,
        ]);
        $results->andFilterWhere(['IN', 'Demand_task.status', 
            ($status == DemandTask::STATUS_DEFAULT ? DemandTask::$defaultStatus : $status)
        ]);
        
        if($time != null){
            $time = explode(" - ",$time);
            if($status == DemandTask::STATUS_DEFAULT)
                $results->andFilterWhere(['<=', 'Demand_task.plan_check_harvest_time', strtotime($time[1])]);
            else if($status == DemandTask::STATUS_COMPLETED)
                $results->andFilterWhere(['between', 'Multimedia_task.reality_check_harvest_time', $time[0],$time[1]]);
            else if($status == DemandTask::STATUS_CANCEL)
                $results->andFilterWhere(['between', 'Multimedia_task.plan_check_harvest_time', strtotime($time[0]),strtotime($time[1])]);
            else
                $results->andFilterWhere(['or', 
                    ['between', 'Multimedia_task.plan_check_harvest_time', strtotime($time[0]),strtotime($time[1])], 
                    ['between', 'Multimedia_task.reality_check_harvest_time', $time[0],$time[1]]
                ]);
        }
        $results->andFilterWhere(['or',
            ['like', 'Fw_item_type.name', $keyword],
            ['like', 'Fw_item.name', $keyword],
            ['like', 'Fw_item_child.name', $keyword],
            ['like', 'Fw_item_course.name', $keyword],
        ]);
        
        return $results;
    }
    
    /**
     * 获取需求任务时长总和 和 总花费金额
     * @param integer $status               状态
     * @return array
     */
    public function getDemandCount($status)
    {
        return (new Query())
                ->select(['SUM(Demand_task.lesson_time) AS total_lesson_time'])
                ->from(['Demand_task' => DemandTask::tableName()])
                ->where(['Demand_task.`status`' => $status])
                ->one();              
    }
    
    /**
     * 获取每个团队的需求任务时长总和
     * @param integer $status               状态
     * @return array
     */
    public function getTeamDemandCount($status)
    {
        $results = (new Query())
                ->select(['Demand_task.create_team', 'SUM(Demand_task.lesson_time) AS total_lesson_time'])
                ->from(['Demand_task' => DemandTask::tableName()])
                ->where(['Demand_task.`status`' => $status])
                ->groupBy('Demand_task.create_team')
                ->all();
            
        return ArrayHelper::map($results, 'create_team', 'total_lesson_time');
    }
    
    /**
     * 获取需求的工作项类型数据结构
     * @param integer $demand_task_id           需求任务ID
     * @return array
     */
    public function getDemandWorkitemTypeData($demand_task_id)
    {
        /* @var $dtQuery DemandQuery */
        $dtQuery = DemandQuery::getInstance();
        /* @var $results ActiveQuery */
        $results = $dtQuery->findDemandWorkitemTypeDataTable($demand_task_id);
        $types = $results->all();
        
        $workitemType = [];
        foreach ($types as $data) {
            $workitemType[$data['workitem_type_id']] = [
                'id' => $data['workitem_type_id'],
                'name' => $data['name'],
                'icon' => $data['icon'],
            ];
        }
        
        return $workitemType;
    }

    /**
     * 获取需求的工作项数据结构
     * @param integer $demand_task_id       需求任务ID
     * @return array
     */
    public function getDemandWorkitemData($demand_task_id)
    {
        /* @var $dtQuery DemandQuery */
        $dtQuery = DemandQuery::getInstance();
        /* @var $results ActiveQuery */
        $results = $dtQuery->findDemandWorkitemDataTable($demand_task_id);
        $d_workitems = $results->all();
        
        $workitem = [];
        foreach ($d_workitems as $data) {
            if(!isset($workitem[$data['workitem_id']])){
                $workitem[$data['workitem_id']] = [
                    'id' => $data['workitem_id'],
                    'workitem_type' => $data['workitem_type'],
                    'name' => $data['name'],
                    'demand_time' => $data['demand_time'],
                    'des' => $data['des'],
                    'childs' => [],
                ];
            }
            $workitem[$data['workitem_id']]['childs'][] = [
                'id' => $data['id'],
                'is_new' => $data['is_new'],
                'value_type' => $data['value_type'],
                'value' => $data['value'],
                'unit' => $data['unit']
            ];
        }
        
        return $workitem;
    }
    
    /**
     * 获取需求的交付数据结构
     * @param integer $demand_task_id           需求任务ID
     * @param integer $delivery_id              交付ID
     * @return array
     */
    public function getDemandDeliveryData($demand_task_id, $delivery_id)
    {
        /* @var $dtQuery DemandQuery */
        $dtQuery = DemandQuery::getInstance();
        /* @var $results ActiveQuery */
        $results = $dtQuery->findDemandDeliveryDataTable($demand_task_id, $delivery_id);
        $deliveryDatas = $results->all();
        
        $delivery = [];
        foreach ($deliveryDatas as $data) {
            if(!isset($delivery[$data['workitem_id']])){
                $delivery[$data['workitem_id']] = [
                    'id' => $data['workitem_id'],
                    'delivery_time' => date('Y-m-d H:i', $data['delivery_time']),
                    'des' => $data['des'],
                    'childs' => [],
                ];
            }
            $delivery[$data['workitem_id']]['childs'][] = [
                'is_new' => $data['is_new'],
                'value_type' => $data['value_type'],
                'value' => $data['value'],
                'unit' => $data['unit']
            ];
        }
       
        return $delivery;
    }
    
    /**
     * 获取需求的验收记录的数据结构
     * @param integer $demand_task_id              需求任务ID
     * @param integer $delivery_id                 交付ID
     * @return array
     */
    public function getDemandAcceptanceData($demand_task_id, $delivery_id)
    {
        /* @var $dtQuery DemandQuery */
        $dtQuery = DemandQuery::getInstance();
        /* @var $results ActiveQuery */
        $results = $dtQuery->findDemandAcceptanceDataTable($demand_task_id, $delivery_id);
        $acceptanceDatas = $results->all();
        
        $acceptance = [];
        foreach ($acceptanceDatas as $data) {
            $acceptance[$data['workitem_type']] = [
                'pass' => $data['pass'],
                'value' => $data['value'],
                'acceptance_time' => date('Y-m-d H:i', $data['acceptance_time']),
                'des' => $data['des']
            ];
        }
        
        return $acceptance;
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
     * 保存数据到需求工作项表
     * @param DemandTask $model
     */
    public function saveDemandWorkitem($model)
    {
        /* @var $dtQuery DemandQuery */
        $dtQuery = DemandQuery::getInstance();
        /* @var $results ActiveQuery */
        $results = $dtQuery->findDemandWorkitemTemplateTable();
        $workitems = [];
        if(!empty($results->all())){
            foreach ($results->all() as $index => $workitem) {
                if(date('Y-m', $model->created_at) >= $workitem['target_month']){
                    unset($workitem['target_month']);
                    $workitem += [
                        'demand_task_id' => $model->id, 
                        'value' => null,
                        'created_at' => strtotime($model->plan_check_harvest_time),
                        'updated_at' => $model->updated_at
                    ];
                    $workitems[] = $workitem;
                }
            }
        }
        
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(DemandWorkitem::tableName(),[
            'workitem_type_id', 'workitem_id', 'is_new',  'value_type', 'cost', 'demand_task_id', 'value', 'created_at', 'updated_at'
        ], $workitems)->execute();
    }
        
    /**
     * 更新需求工作项
     * @param array $post   
     */
    public function UpdateDemandWorkitem($post)
    {
        $values = ArrayHelper::getValue($post, 'value');
        foreach ($values as $key => $value) {
            \Yii::$app->db->createCommand()->update(DemandWorkitem::tableName(), ['value' => $value], ['id' => $key])->execute();
        }
    }
    
    /**
     * 保存数据到需求比重表
     * @param DemandTask $model
     */
    public function saveDemandWeight($model)
    {
        /* @var $dtQuery DemandQuery */
        $dtQuery = DemandQuery::getInstance();
        /* @var $results ActiveQuery */
        $results = $dtQuery->findDemandWeightTemplateTotal();
        $weights = [];
        if(!empty($results->all())){
            foreach ($results->all() as $index => $weight) {
                $weight += [
                    'demand_task_id' => $model->id,
                    'created_at' => $model->created_at,
                    'updated_at' => $model->updated_at
                ];
                $weights[] = $weight;
            }
        }
        
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(DemandWeight::tableName(),[
            'workitem_type_id', 'weight', 'sl_weight',  'zl_weight', 'demand_task_id', 'created_at', 'updated_at'
        ], $weights)->execute();
    }

    /**
     * 获取开发负责人所在团队成员表里的ID
     * @return integer|array    
     */
    public function getHotelTeamMemberId()
    {
        $teamMember = TeamMemberTool::getInstance()->getUserLeaderTeamMembers(Yii::$app->user->id, TeamCategory::TYPE_CCOA_DEV_TEAM);
        $teamMemberId = ArrayHelper::getColumn($teamMember, 'id');
        if(!empty($teamMemberId) && count($teamMemberId) == 1)
            return $teamMemberId[0];
        else
            return ArrayHelper::map($teamMember, 'id', 'nickname');
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
    public function getIsAuditor($teamId = null, $u_id = null)
    {
        $u_id = $u_id == null ? Yii::$app->user->id : $u_id;
        $auditor = DemandTaskAuditor::find()
                ->filterWhere(['team_id' => $teamId])
                ->orFilterWhere(['u_id' => $u_id])
                ->one();
        if(!empty($auditor) && isset($auditor)){
            if($u_id == $auditor->u_id)
                return true;
        }
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
