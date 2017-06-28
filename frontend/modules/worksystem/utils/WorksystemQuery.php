<?php

namespace frontend\modules\worksystem\utils;

use common\models\team\Team;
use common\models\worksystem\WorksystemTask;
use common\models\worksystem\WorksystemTaskProducer;
use common\models\worksystem\WorksystemTaskType;
use wskeee\framework\models\ItemType;
use yii\rbac\Item;


class WorksystemQuery 
{
   private static $instance = null;
   
   
    /**
     * 查询需求任务数据
     * @return $query
     */
    public function findWorksystemTaskTable()
    {
        $query = WorksystemTask::find()
            ->select(['Worksystem_task.id'])
            ->from(['Worksystem_task' => WorksystemTask::tableName()])
            ->leftJoin(['Create_team' => Team::tableName()], 'Create_team.id = Worksystem_task.create_team')
            ->leftJoin(['External_team' => Team::tableName()], 'External_team.id = Worksystem_task.external_team')
            ->leftJoin(['Producer' => WorksystemTaskProducer::tableName()], 'Producer.worksystem_task_id = Worksystem_task.id')
            ->leftJoin(['Fw_item_type' => ItemType::tableName()], 'Fw_item_type.id = Worksystem_task.item_type_id')
            ->leftJoin(['Fw_item' => Item::tableName()], 'Fw_item.id = Worksystem_task.item_id')
            ->leftJoin(['Fw_item_child' => Item::tableName()], 'Fw_item_child.id = Worksystem_task.item_child_id')
            ->leftJoin(['Fw_item_course' => Item::tableName()], 'Fw_item_course.id = Worksystem_task.course_id')
            ->leftJoin(['Ws_task_type' => WorksystemTaskType::tableName()], 'Ws_task_type.id = Worksystem_task.task_type_id')
            ->orderBy('Worksystem_task.level desc')
            ->groupBy(['Worksystem_task.id'])
            ->with('itemType', 'item', 'itemChild', 'course', 'createBy', 'worksystemTaskType', 'createTeam', 'externalTeam', 'worksystemTaskProducers');
                 
        return $query;
    }
   
   /**
     * 获取单例
     * @return WorksystemQuery
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new WorksystemQuery();
        }
        return self::$instance;
    }
}
