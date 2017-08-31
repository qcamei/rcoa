<?php

namespace frontend\modules\worksystem\utils;

use common\models\demand\DemandTask;
use common\models\team\TeamMember;
use common\models\worksystem\WorksystemAssignTeam;
use common\models\worksystem\WorksystemTask;
use common\models\worksystem\WorksystemTaskProducer;
use common\models\worksystem\WorksystemTaskType;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;



class WorksystemQuery 
{
    private static $instance = null;
   
   
    /**
     * 查询工作系统任务数据
     * @return $query
     */
    public function findWorksystemTask()
    {
        $query = WorksystemTask::find()
            ->select(['Worksystem_task.id'])
            ->from(['Worksystem_task' => WorksystemTask::tableName()])
            ->leftJoin(['Create_team' => WorksystemAssignTeam::tableName()], 'Create_team.team_id = Worksystem_task.create_team')
            ->leftJoin(['External_team' => WorksystemAssignTeam::tableName()], 'External_team.team_id = Worksystem_task.external_team')
            ->leftJoin(['Producer' => WorksystemTaskProducer::tableName()], 'Producer.worksystem_task_id = Worksystem_task.id')
            ->leftJoin(['TeamMember' => TeamMember::tableName()], 'TeamMember.id = Producer.team_member_id')
            ->leftJoin(['Fw_item_type' => ItemType::tableName()], 'Fw_item_type.id = Worksystem_task.item_type_id')
            ->leftJoin(['Fw_item' => Item::tableName()], 'Fw_item.id = Worksystem_task.item_id')
            ->leftJoin(['Fw_item_child' => Item::tableName()], 'Fw_item_child.id = Worksystem_task.item_child_id')
            ->leftJoin(['Fw_item_course' => Item::tableName()], 'Fw_item_course.id = Worksystem_task.course_id')
            ->leftJoin(['Ws_task_type' => WorksystemTaskType::tableName()], 'Ws_task_type.id = Worksystem_task.task_type_id')
            ->orderBy('Worksystem_task.level desc, Worksystem_task.id desc')
            ->groupBy(['Worksystem_task.id'])
            ->with('itemType', 'item', 'itemChild', 'course', 'createBy', 'worksystemTaskType', 'createTeam', 'externalTeam', 'worksystemTaskProducers');
                 
        return $query;
    }
   
    /**
     * 查询需求任务数据
     * @param integer $courseId
     * @return type
     */
    public function findDemandTaskTable($courseId)
    {
        $query = DemandTask::find()
                ->filterWhere(['course_id' => $courseId])
                ->one();
        
        if($query != null)
            return $query;
        else 
            return new DemandTask ();
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
