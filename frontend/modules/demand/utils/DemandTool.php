<?php
namespace frontend\modules\demand\utils;

use common\models\demand\DemandCheck;
use common\models\demand\DemandTask;
use common\models\team\TeamCategory;
use frontend\modules\demand\utils\DemandQuery;
use frontend\modules\demand\utils\DemandTool;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class DemandTool {
    
   private static $instance = null;
   
   /**
    * 数据表
    * @var Query 
    */
   public static $table = null;
       
    
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
     * 获取开发负责人所在团队成员表里的ID
     * @return integer|array    
     */
    public function getHotelTeamMemberId()
    {
        $teamMember = TeamMemberTool::getInstance()
                ->getUserLeaderTeamMembers(Yii::$app->user->id, TeamCategory::TYPE_CCOA_DEV_TEAM);
        $teamMemberId = ArrayHelper::getColumn($teamMember, 'id');
        if(!empty($teamMemberId) && count($teamMemberId) == 1)
            return $teamMemberId[0];
        else
            return ArrayHelper::map($teamMember, 'id', 'nickname');
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
    public function getDemandWorkitemTypeData($demand_task_id = null)
    {
        /* @var $dtQuery DemandQuery */
        $dtQuery = DemandQuery::getInstance();
        /* @var $results ActiveQuery */
        $results = $demand_task_id == null ? $dtQuery->_findDemandWorkitemTypeDataTable() : $dtQuery->findDemandWorkitemTypeDataTable($demand_task_id);
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
    public function getDemandWorkitemData($demand_task_id = null)
    {
        /* @var $dtQuery DemandQuery */
        $dtQuery = DemandQuery::getInstance();
        /* @var $results ActiveQuery */
        $results = $demand_task_id == null ? $dtQuery->_findDemandWorkitemDataTable() : $dtQuery->findDemandWorkitemDataTable($demand_task_id);
        $d_workitems = $results->all();
        
        $workitem = [];
        foreach ($d_workitems as $data) {
            if(!isset($workitem[$data['workitem_id']])){
                $workitem[$data['workitem_id']] = [
                    'id' => $data['workitem_id'],
                    'workitem_type' => $data['workitem_type'],
                    'name' => $data['name'],
                    'demand_time' => isset($data['demand_time']) ? $data['demand_time'] : null,
                    'des' => isset($data['des']) ? $data['des'] : null,
                    'childs' => [],
                ];
            }
            $workitem[$data['workitem_id']]['childs'][] = [
                'id' => $data['id'],
                'is_new' => $data['is_new'],
                'value_type' => $data['value_type'],
                'value' => isset($data['value']) ? $data['value'] : null,
                'unit' => $data['unit'],
                'cost' => $data['cost'],
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
                    'reality_cost' => $data['reality_cost'],
                    'external_reality_cost' => $data['external_reality_cost'],
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
