<?php
namespace frontend\modules\demand\utils;

use common\models\demand\DemandTask;
use common\models\team\Team;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;

class DemandQuery {
   private static $instance = null;
   
    /**
     * 查询需求任务数据
     * @return $query
     */
    public function getDemandTaskTable()
    {
        $query = DemandTask::find()
                ->select(['Demand_task.id'])
                ->from(['Demand_task' => DemandTask::tableName()])
                ->leftJoin(['Team' => Team::tableName()], 'Team.id = Demand_task.team_id')
                ->leftJoin(['Fw_item_type' => ItemType::tableName()], 'Fw_item_type.id = Demand_task.item_type_id')
                ->leftJoin(['Fw_item' => Item::tableName()], 'Fw_item.id = Demand_task.item_id')
                ->leftJoin(['Fw_item_child' => Item::tableName()], 'Fw_item_child.id = Demand_task.item_child_id')
                ->leftJoin(['Fw_item_course' => Item::tableName()], 'Fw_item_course.id = Demand_task.course_id')
                ->groupBy(['Demand_task.id'])
                ->with('course', 'item', 'itemChild', 'itemType', 'team');
                 
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
