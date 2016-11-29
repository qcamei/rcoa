<?php

namespace frontend\modules\teamwork\utils;

use common\models\team\Team;
use common\models\teamwork\CourseLink;
use common\models\teamwork\CourseManage;
use common\models\teamwork\CoursePhase;
use common\models\teamwork\ItemManage;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;

class TeamworkQuery {
    
    private static $instance = null;
    
    /**
     * 查询课程阶段
     * @return $query
     */
    public  function getCoursePhaseTable()
    {
        $query = CoursePhase::find()
                ->select(['Tw_course_phase.id', 'Tw_course_link.course_id'])
                ->from(['Tw_course_link' => CourseLink::tableName()])
                ->leftJoin(['Tw_course_phase' => CoursePhase::tableName()], 'Tw_course_phase.id = Tw_course_link.course_phase_id')
                ->where(['Tw_course_link.is_delete' => 'N', 'Tw_course_phase.is_delete' => 'N'])
                ->groupBy(['Tw_course_phase.id'])
                ->with('course', 'courseLinks');
        
        return $query;
    }

    /**
     * 查询课程数据
     * @return $query
     */
    public function getCourseManageTable()
    {
        $query = CourseManage::find()
                ->select(['Tw_course.id'])
                ->from(['Tw_course' => CourseManage::tableName()])
                ->leftJoin(['Tw_item' => ItemManage::tableName()], 'Tw_item.id = Tw_course.project_id')
                ->leftJoin(['Team' => Team::tableName()], 'Team.id = Tw_course.team_id')
                ->leftJoin(['Fw_item_type' => ItemType::tableName()], 'Fw_item_type.id = Tw_item.item_type_id')
                ->leftJoin(['Fw_item' => Item::tableName()], 'Fw_item.id = Tw_item.item_id')
                ->leftJoin(['Fw_item_child' => Item::tableName()], 'Fw_item_child.id = Tw_item.item_child_id')
                ->leftJoin(['Fw_item_course' => Item::tableName()], 'Fw_item_course.id = Tw_course.course_id')
                ->groupBy(['Tw_course.id'])
                ->with('course', 'project.item', 'project.itemChild', 'project.itemType', 'team');
                 
        return $query;
    }
    
    /**
     * 查询项目数据
     * @return type
    */ 
    public function getItemManageTable()
    {
        $query = ItemManage::find()
                ->select(['Tw_item.id'])
                ->from(['Tw_item' => ItemManage::tableName()])
                ->leftJoin(['Fw_item_type' => ItemType::tableName()], 'Fw_item_type.id = Tw_item.item_type_id')
                ->leftJoin(['Fw_item' => Item::tableName()], 'Fw_item.id = Tw_item.item_id')
                ->leftJoin(['Fw_item_child' => Item::tableName()], 'Fw_item_child.id = Tw_item.item_child_id')
                ->groupBy('Tw_item.id')
                ->with('courseManages', 'itemChild', 'item', 'itemType');
        
        return $query;
    }
    
    /**
     * 获取单例
     * @return TeamworkQuery
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new TeamworkQuery();
        }
        return self::$instance;
    }
}
