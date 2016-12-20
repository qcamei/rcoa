<?php

namespace frontend\modules\multimedia\utils;

use common\models\multimedia\MultimediaAssignTeam;
use common\models\multimedia\MultimediaProducer;
use common\models\multimedia\MultimediaTask;
use common\models\team\TeamMember;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;

class MultimediaQuery {
    
    private static $instance = null;
    
    /**
     * 查询多媒体任务
     * @return $query
     */
    public function getMultimediaTaskTable()
    {
        $query = MultimediaTask::find()
                ->select(['Multimedia_task.id'])
                ->from(['Multimedia_task' => MultimediaTask::tableName()])
                ->leftJoin(['Assign_make_team' => MultimediaAssignTeam::tableName()], 'Assign_make_team.team_id = Multimedia_task.make_team')
                ->leftJoin(['Assign_create_team' => MultimediaAssignTeam::tableName()], 'Assign_create_team.team_id = Multimedia_task.create_team')
                ->leftJoin(['Producer' => MultimediaProducer::tableName()], 'Producer.task_id = Multimedia_task.id')
                ->leftJoin(['TeamMember' => TeamMember::tableName()], 'TeamMember.id = Producer.producer')
                ->leftJoin(['Fm_item_type' => ItemType::tableName()], 'Fm_item_type.id = Multimedia_task.item_type_id')
                ->leftJoin(['Fm_item' => Item::tableName()],'Fm_item.id = Multimedia_task.item_id')
                ->leftJoin(['Fm_item_child' => Item::tableName()],'Fm_item_child.id = Multimedia_task.item_child_id')
                ->leftJoin(['Fm_course' => Item::tableName()],'Fm_course.id = Multimedia_task.course_id')
                ->orderBy('Multimedia_task.level desc, Multimedia_task.id asc')
                ->with('contentType', 'createTeam', 'itemChild', 'item', 'itemType', 'makeTeam', 'course', 'createBy', 'producers', 'teamMember');
          
        return $query;
    }

    /**
     * 获取单例
     * @return MultimediaQuery
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new MultimediaQuery();
        }
        return self::$instance;
    }
}
