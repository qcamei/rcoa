<?php
namespace frontend\modules\demand\utils;

use common\models\demand\DemandAcceptance;
use common\models\demand\DemandAcceptanceData;
use common\models\demand\DemandTask;
use common\models\demand\DemandTaskAuditor;
use common\models\demand\DemandTaskProduct;
use common\models\demand\DemandWeight;
use common\models\demand\DemandWeightTemplate;
use common\models\demand\DemandWorkitemTemplate;
use common\models\product\Product;
use common\models\team\Team;
use common\models\workitem\WorkitemCost;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use yii\db\Query;

class DemandQuery {
   private static $instance = null;
   private $workitemResult;
   
    public function __construct(){
        $this->findDemandWorkitemTemplateTotal();
    }
   
    /**
     * 查询需求任务数据
     * @return $query
     */
    public function findDemandTaskTable()
    {
        $query = DemandTask::find()
                ->select(['Demand_task.id'])
                ->from(['Demand_task' => DemandTask::tableName()])
                ->leftJoin(['Team' => Team::tableName()], 'Team.id = Demand_task.team_id')
                ->leftJoin(['Demand_task_auditor' => DemandTaskAuditor::tableName()], 'Demand_task_auditor.team_id = Demand_task.create_team')
                ->leftJoin(['Fw_item_type' => ItemType::tableName()], 'Fw_item_type.id = Demand_task.item_type_id')
                ->leftJoin(['Fw_item' => Item::tableName()], 'Fw_item.id = Demand_task.item_id')
                ->leftJoin(['Fw_item_child' => Item::tableName()], 'Fw_item_child.id = Demand_task.item_child_id')
                ->leftJoin(['Fw_item_course' => Item::tableName()], 'Fw_item_course.id = Demand_task.course_id')
                ->groupBy(['Demand_task.id'])
                ->with('course', 'item', 'itemChild', 'itemType', 'team', 'createBy');
                 
        return $query;
    }
   
    /**
     * 查询课程产品额和总学时
     * @return $query
     */
    public function findProductTotal()
    {
        $query = (new Query())
                ->select(['Task_product.id'])
                ->from(['Task_product' => DemandTaskProduct::tableName()])
                ->leftJoin(['Product' => Product::tableName()], 'Product.id = Task_product.product_id');
        
        return $query;
    }
    
    /**
     * 查询需求任务工作项数据
     * @return $query
     */
    public function findDemandWorkitemTemplateTotal()
    {
        $query = (new Query())
                ->select([
                    'dw_temp.workitem_type_id',
                    'dw_temp.workitem_id',
                    'dw_temp.is_new',
                    'dw_temp.value_type',
                    'w_cost.target_month',
                    'if(dw_temp.is_new = TRUE, w_cost.cost_new, w_cost.cost_remould) AS cost'])
                ->from(['dw_temp'=> DemandWorkitemTemplate::tableName()])
                ->leftJoin(['w_cost'=> WorkitemCost::tableName()], 'w_cost.workitem_id=dw_temp.workitem_id')
                ->orderBy(['w_cost.target_month' => 'DESC', 'dw_temp.workitem_id' => 'DESC',   'dw_temp.is_new'=> 'DESC']);
        
        return $query;
    }
    
    /**
     * 查询需求任务比重数据
     * @return $query
     */
    public function findDemandWeightTemplateTotal()
    {
        $query = (new Query())
                ->select([
                    'd_Weight_template.workitem_type_id',
                    'd_Weight_template.weight',
                    'd_Weight_template.sl_weight',
                    'd_Weight_template.zl_weight',
                ])
                ->from(['d_Weight_template'=> DemandWeightTemplate::tableName()]);
        
        return $query;
    }
    
    /**
     * 查询该需求任务对应的每个工作项类型的奖金
     * @param type $demand_task_id          需求任务ID
     * @return $query
     */
    public function findDemandWorkitemTypeBonus($demand_task_id)
    {
        $query = (new Query())
                ->select(['Demand_task.id', 'Demand_task.cost *  Demand_task.bonus_proportion * (Acceptance_data.value * Demand_weight.zl_weight) AS bonus'])
                ->from(['Demand_task' => DemandTask::tableName()])
                ->leftJoin(['Demand_weight' => DemandWeight::tableName()], 'Demand_weight.demand_task_id = Demand_task.id')
                ->leftJoin(['Acceptance' => DemandAcceptance::tableName()], 'Acceptance.demand_task_id = Demand_task.id')
                ->leftJoin(['Acceptance_data' => DemandAcceptanceData::tableName()], 'Acceptance_data.demand_acceptance_id = Acceptance.id')
                ->where(['Acceptance.demand_task_id' => $demand_task_id, 'Acceptance.pass' => TRUE])
                ->groupBy('Acceptance_data.workitem_type_id');
        
        return $query;
    }

    /**
     * 获取单例
     * @return DemandQuery
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DemandQuery();
        }
        return self::$instance;
    }
}
