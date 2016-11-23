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
    
    public  function getCourseLinkTable()
    {
        $query = CourseLink::find()
                 ->select(['Tw_course_phase.name'])
                 ->from(['Tw_course_link' => CourseLink::tableName()])
                 ->leftJoin(['Tw_course_phase' => CoursePhase::tableName()], 'Tw_course_phase.id = Tw_course_link.course_phase_id')
                 ->where(['Tw_course_link.is_delete' => 'N'])
                 ->groupBy(['Tw_course_phase.id']);
        
        return $query;
    }

    /**
     * 查询课程数据
     * @return $query
     */
    public function getCourseManageTable()
    {
        $query = CourseManage::find()
                   ->select(['Tw_course.id', 'Tw_course.project_id', 'Tw_course.course_id', 
                       'Tw_course.status', 'Tw_course.team_id', 'Tw_course.mode',
                       'Tw_item.item_type_id', 'Tw_item.item_id', 'Tw_item.item_child_id',
                       'Team.`name` AS team_name', 'Fw_item_type.`name` AS item_type_name',
                       'Fw_item.`name` AS item_name','Fw_item_child.`name` AS item_child_name',
                       'Fw_item_course.`name` AS item_course_name'
                   ])
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
     * 获取课程阶段进度
     * @param type $courseId   课程ID
     * @return type
     */
    public function getCoursePhaseProgressAll($courseId)
    {
        $sql = "(SELECT Course_link.course_phase_id, SUM(Course_link.completed) / SUM(Course_link.total) AS progress 
	FROM ccoa_teamwork_course_link AS Course_link
	WHERE Course_link.course_id = $courseId AND Course_link.is_delete = 'N'
	GROUP BY Course_link.id) AS Course_link_progress";
        $results = CoursePhase::find()
                ->select(['Course_phase.*',
                    'SUM(Course_link_progress.progress) / COUNT(Course_link_progress.course_phase_id) AS progress'])
                ->from($sql)
                ->leftJoin(['Course_phase'=> CoursePhase::tableName()], 'Course_phase.id = Course_link_progress.course_phase_id')
                ->groupBy('Course_phase.id')
                ->with('course')
                ->with('courseLinks')
                ->all();
        
        return $results;
    }
    
    /**
     * 获取所有课程进度
     * @param type $projectId       项目管理 ID
     * @param type $status          状态
     * @param type $teamId          团队ID
     * @param type $itemTypeId      行业ID
     * @param type $itemId          层次/类型ID
     * @param type $itemChildId     专业/工种ID
     * @param type $courseId        课程ID
     * @return type                 返回结果对象
     */
    public function getCourseProgressAll($projectId = null, $status = null, $teamId = null, 
            $itemTypeId = null, $itemId = null, $itemChildId = null, $courseId = null, $keyword = null, $time = null)
    {
        if($time != null)
            $time = explode(" - ",$time);
        
        $results = CourseManage::find()
                ->select(['Course.id', 'Course.project_id', 'Item.item_type_id','Item.item_id', 'Item.item_child_id', 
                    'Team.name AS team_name', 'Fw_item_type.name AS item_type_name',
                    'Fw_item.name AS item_name',
                    'SUM(Course_phase_progress.progress * Course_phase_progress.weights) AS progress'])
                ->from($this->courseProgress)
                ->leftJoin(['Course'=>CourseManage::tableName()], 'Course.id = Course_phase_progress.course_id')
                ->leftJoin(['Item' => ItemManage::tableName()], 'Item.id = Course.project_id')
                ->leftJoin(['Team' => Team::tableName()], 'Team.id = Course.team_id')
                ->leftJoin(['Fw_item_type' => ItemType::tableName()], 'Fw_item_type.id = Item.item_type_id')
                ->leftJoin(['Fw_item' => Item::tableName()], 
                    '(Fw_item.id = Course.course_id OR Fw_item.id = Item.item_child_id OR Fw_item.id = Item.item_id)')
                ->andfilterWhere(['like', 'Fw_item_type.name', $keyword])
                ->orFilterWhere(['like', 'Fw_item.name', $keyword])
                ->orFilterWhere(['like', 'Team.name', $keyword])
                ->andFilterWhere([
                    'Item.item_type_id' => $itemTypeId,
                    'Item.item_id' => $itemId,
                    'Item.item_child_id' => $itemChildId,
                    'Course.project_id'=> $projectId,
                    'Course.course_id' => $courseId,
                    'Course.`status`'=> $status,
                    'Course.team_id'=> $teamId,
                ])
                ->andFilterWhere(
                    $time != null ? ($status == CourseManage::STATUS_WAIT_START ? ['<=','Course.created_at', strtotime($time[1]) ]: 
                        ($status == CourseManage::STATUS_NORMAL ? ['<=','Course.real_start_time',$time[1]] : 
                            ($status == CourseManage::STATUS_CARRY_OUT ? ['between','Course.real_carry_out',$time[0],$time[1]] : 
                            ['or', ['and',"Course.status=".CourseManage::STATUS_WAIT_START,  ['<=','Course.created_at', strtotime($time[1])]], 
                                ['and',"Course.status=".CourseManage::STATUS_NORMAL, ['<=','Course.real_start_time', $time[1]]],
                                ['and',"Course.status=".CourseManage::STATUS_CARRY_OUT,   ['between','Course.real_carry_out',$time[0],$time[1]]]
                            ]))) : []
                )
                ->groupBy(['Course.id', 'Fw_item.id'])
                ->with('course')
                ->with('project.item')
                ->with('project.itemChild')
                ->with('project.itemType')
                ->with('team')
                ->all();
               
        return $results;
    }
    
    /**
     * 获取单条课程进度
     * @param type $id
     */
    public function getCourseProgressOne($id)
    {
        $results = CourseManage::find()
                    ->select(['Course.*', 
                        'SUM(Course_phase_progress.progress * Course_phase_progress.weights) AS progress'])
                    ->from($this->courseProgress)
                    ->leftJoin(['Course'=>CourseManage::tableName()], 'Course.id = Course_phase_progress.course_id')
                    ->andFilterWhere(['Course.id'=> $id])
                    ->one();
        return $results;
    }
    
    /**
     * 获取所有项目进度
     * @param type $keyword 搜索关键字
     * @return type
     */
    public function getItemProgressAll($keyword = null){
        $itemProgress = ItemManage::find()
                        ->select([
                            'Item.*', 
                            'Fw_item_type.name AS item_type_name',
                            'Fw_item.name AS item_name',
                            '(SUM(Course_progress.progress) / COUNT(Course_progress.id)) AS progress'])
                        ->from($this->itemProgress)
                        ->rightJoin(['Item' => ItemManage::tableName()], 'Item.id = Course_progress.project_id')
                        ->leftJoin(['Fw_item_type' => ItemType::tableName()], 'Fw_item_type.id = Item.item_type_id')
                        ->leftJoin(['Fw_item' => Item::tableName()], '(Fw_item.id = Item.item_id OR Fw_item.id = Item.item_child_id)')
                        ->orFilterWhere(['like', 'Fw_item_type.name', $keyword])
                        ->orFilterWhere(['like', 'Fw_item.name', $keyword])
                        ->groupBy('Item.id')
                        ->with('courseManages')
                        ->with('itemChild')
                        ->with('item')
                        ->with('itemType')
                        ->all();
        
        return $itemProgress;
    }
    
    /**
     * 获取单个项目进度
     * @param type $id
     * @return type
     */
    public function getItemProgressOne($id)
    {        
        $itemProgress = ItemManage::find()
                        ->select(['Item.*', '(SUM(Course_progress.progress) / COUNT(Course_progress.id)) AS progress'])
                        ->from($this->itemProgress)
                        ->rightJoin(['Item' => ItemManage::tableName()], 'Item.id = Course_progress.project_id')
                        ->where(['Item.id' => $id])
                        ->one();
        
        return $itemProgress;
    }
    
    /**
     * 课程阶段进度
     * @var type 
     */
    private $courseProgress = "(SELECT Course_phase.id, Course_phase.course_id, Course_phase.weights, 
        SUM(Course_link_progress.progress) / COUNT(Course_link_progress.course_phase_id) AS progress FROM 
            (SELECT Course_link.course_phase_id, SUM(Course_link.completed) / SUM(Course_link.total) AS progress 
            FROM ccoa_teamwork_course_link AS Course_link
            WHERE Course_link.is_delete = 'N'
            GROUP BY Course_link.id) AS Course_link_progress
	LEFT JOIN ccoa_teamwork_course_phase AS Course_phase ON Course_phase.id = Course_link_progress.course_phase_id
	GROUP BY Course_phase.id) AS Course_phase_progress";
    
    /**
     * 课程进度
     * @var type 
     */
    private $itemProgress = "(SELECT Course.id,Course.project_id, 
        SUM(Course_phase_progress.weights * Course_phase_progress.progress) AS progress FROM 
            (SELECT Course_phase.id, Course_phase.course_id, Course_phase.weights, 
                SUM(Course_link_progress.progress) / COUNT(Course_link_progress.course_phase_id) AS progress FROM 
                    (SELECT Course_link.course_phase_id, Course_link.is_delete,SUM(Course_link.completed) / SUM(Course_link.total) AS progress 
                    FROM ccoa_teamwork_course_link AS Course_link
                    WHERE Course_link.is_delete = 'N'
                    GROUP BY Course_link.id) AS Course_link_progress
            LEFT JOIN ccoa_teamwork_course_phase AS Course_phase ON Course_phase.id = Course_link_progress.course_phase_id 
            GROUP BY Course_phase.id) AS Course_phase_progress
	LEFT JOIN ccoa_teamwork_course AS Course ON Course.id = Course_phase_progress.course_id
	GROUP BY Course.id) AS Course_progress";
    
    /*private function getCourseProgress()
    {
        $query = (new Query())
                 ->select(['Course.id', 'Course.project_id', 
                    'SUM(Course_phase_progress.weights * Course_phase_progress.progress) AS progress',
                ])
                ->from();
                          
    }*/
    
    
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
