<?php

namespace frontend\modules\teamwork;

use common\models\team\TeamMember;
use common\models\teamwork\CourseLink;
use common\models\teamwork\CourseManage;
use common\models\teamwork\CoursePhase;
use common\models\teamwork\CourseSummary;
use common\models\teamwork\ItemManage;
use common\models\teamwork\Link;
use common\models\teamwork\Phase;
use Yii;
use yii\db\Query;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class TeamworkTool{
    /**
     * 获取一周时间
     * @param type $course_id
     * @param type $date
     */
    public function getWeek($course_id, $date)
    {
        //$date = date('Y-m-d');  //当前日期
        $first = 1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        $w = date('w',strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        $now_start = date('Y-m-d',strtotime("$date -".($w ? $w - $first : 6).' days')); //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $now_end = date('Y-m-d',strtotime("$now_start +6 days"));  //本周结束日期
        //$last_start=date('Y-m-d',strtotime("$now_start - 7 days"));  //上周开始日期
        //$last_end=date('Y-m-d',strtotime("$now_start - 1 days"));  //上周结束日期
        $result = CourseSummary::find()->where(['course_id' => $course_id])
                ->andWhere('create_time >="'. $now_start.'"')
                ->andWhere('create_time <="'. $now_end.'"')
                ->one();
        return $result;
    }
    
    /**
     * 获取创建者所在团队
     * @param type $create_by   创建者
     * @return type
     */
    public function getHotelTeam($create_by)
    {
        $create_by = TeamMember::findOne(['u_id' => $create_by]);
         
        return $create_by->team_id;
    }

    /**
     * 获取当前用户是否为【队长】
     * @return boolean  true为是
     */
    public function getIsLeader()
    {
        //查出成员表里面所有队长
        $isLeader = TeamMember::findAll(['u_id' => \Yii::$app->user->id]);
        
        if(!empty($isLeader) || isset($isLeader)){
            foreach ($isLeader as $value){
                if($value->is_leader == 'Y')
                    return true;
            }
        }
        return false;
    }
    
    /**
     * 获取课程时长总和
     * @return type
     */
    public function getCourseLessionTimesSum($condition)
    {
        $lessionTimes = CourseManage::find()
                        ->where($condition)
                        ->with('project')
                        ->all();
        $lessionTime = [];
        foreach ($lessionTimes as $value)
            /* @var $value  CourseManage */
            $lessionTime[] = $value->lession_time;
       
        return array_sum($lessionTime);
    }
        
    /**
     * 复制Phase表数据到CoursePhase表
     * @param type $course_id       课程ID
     * @param type $templateType    模版类别
     */
    public function addCoursePhase($course_id, $templateType)
    {
        $phase = Phase::find()
                ->andFilterWhere(['template_type_id' => $templateType])
                ->with('links')
                ->with('templateType')
                ->with('createBy')
                ->all();
       
        $values = [];
        /** 重组提交的数据为$values数组 */
        foreach($phase as $value)
        {
            $values[] = [
                'course_id' => $course_id,
                'name' => $value->name,
                'weights' => $value->weights,
                'create_by' => \Yii::$app->user->id,
            ];
        }
        
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(CoursePhase::tableName(), 
        [
            'course_id',
            'name',
            'weights',
            'create_by',
        ], $values)->execute();
    }
    
    /**
     * 复制Link表数据到CourseLink表
     * @param type $course_id   课程ID
     */
    public function addCourseLink($course_id, $templateType)
    {
        $link = (new Query())
                ->select(['Course_phase.id AS course_phase_id', 'Link.*'])
                ->from(['Link' => Link::tableName()])
                ->leftJoin(['Phase' => Phase::tableName()], 'Phase.id = Link.phase_id')
                ->leftJoin(['Course_phase' => CoursePhase::tableName()], 'Course_phase.name = Phase.name')
                ->where(['course_id' => $course_id])
                ->andFilterWhere(['template_type_id' => $templateType])
               
                ->all();
        
        $values = [];
        /** 重组提交的数据为$values数组 */
        foreach($link as $value)
        {
            $values[] = [
                'course_id' => $course_id,
                'course_phase_id' => $value['course_phase_id'],
                'name' => $value['name'],
                'type' => $value['type'],
                'total' => $value['total'],
                'completed' => $value['completed'],
                'unit' => $value['unit'],
                'create_by' => \Yii::$app->user->id,
            ];
        }
        
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(CourseLink::tableName(), 
        [
            'course_id',
            'course_phase_id',
            'name',
            'type',
            'total',
            'completed',
            'unit',
            'create_by',
        ], $values)->execute();
    }
    
    /**
     * 获取课程阶段进度
     * @param type $courseId   课程ID
     * @return type
     */
    public function getCoursePhaseProgressAll($courseId)
    {
        $results = CoursePhase::find()
                ->select(['Course_phase.id', 'Course_phase.course_id', 'Course_phase.name', 'Course_link.course_id',
                    'Course_phase.weights',
                    '(SUM(Course_link.completed)/SUM(Course_link.total)) AS progress '])
                ->from(['Course_link'=> CourseLink::tableName()])
                ->leftJoin(['Course_phase'=> CoursePhase::tableName()],'Course_phase.id = Course_link.course_phase_id')
                ->where([
                    'Course_link.course_id' => $courseId, 
                    'Course_phase.is_delete' => 'N', 
                    'Course_link.is_delete' => 'N'])
                ->groupBy('Course_link.course_phase_id')
                ->with('course')
                ->with('courseLinks')
                ->all();
        return $results;
    }
    
    /**
     * 获取所有课程进度
     * @param type $projectId   项目ID
     * @param type $status      状态
     * @param type $teamId      团队ID
     * @return type 返回结果对象
     */
    public function getCourseProgressAll($projectId = null, $status = null, $teamId = null)
    {
        $results = CourseManage::find()
                    ->select(['Course.*',  
                        '(SUM(Course_link.completed) / SUM(Course_link.total)) AS progress '])  
                    ->from(['Course'=>CourseManage::tableName()])
                    ->leftJoin(['Course_link'=>  CourseLink::tableName()], 'Course_link.course_id = Course.id')
                    ->leftJoin(['Course_phase' => CoursePhase::tableName()], 'Course_phase.course_id = Course.id')
                    ->where(['Course_phase.is_delete' => 'N', 'Course_link.is_delete' => 'N'])
                    ->andFilterWhere(['Course.project_id'=> $projectId])
                    ->andFilterWhere(['Course.`status`'=> $status])
                    ->andFilterWhere(['Course.team_id'=> $teamId])
                    ->groupBy('Course.id')
                    ->with('course')
                    ->with('courseLinks')
                    ->with('coursePhases')
                    ->with('producers')
                    ->with('speakerTeacher')
                    ->with('project')
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
                        '(SUM(Course_link.completed) / SUM(Course_link.total)) AS progress '])  
                    ->from(['Course'=>CourseManage::tableName()])
                    ->leftJoin(['Course_link'=>  CourseLink::tableName()], 'Course_link.course_id = Course.id')
                    ->leftJoin(['Course_phase' => CoursePhase::tableName()], 'Course_phase.course_id = Course.id')
                    ->where(['Course.id' => $id,
                        'Course_phase.is_delete' => 'N', 
                        'Course_link.is_delete' => 'N'])
                    ->one();
        return $results;
    }
    
    /**
     * 获取所有项目进度
     * @return type
     */
    public function getItemProgressAll(){
        
        $sql = "SELECT Item_course.*,(SUM(Course_link.completed)/SUM(Course_link.total)) AS progress FROM     
                    (SELECT Item.id, Course.id AS course_id,Item.item_type_id,Item.item_id, Item.item_child_id,Course.team_id,Course.`status` 
                        FROM ccoa_teamwork_item_manage AS Item  
                        LEFT JOIN ccoa_teamwork_course_manage AS Course ON Course.project_id = Item.id) AS Item_course
                LEFT JOIN ccoa_teamwork_course_link AS Course_link ON Item_course.course_id = Course_link.course_id 
                WHERE Course_link.is_delete = 'N'
                GROUP BY Item_course.id ";
        $itemProgress = ItemManage::findBySql($sql)
                        ->with('courseManages')
                        ->with('createBy')
                        ->with('itemChild')
                        ->with('item')
                        ->with('itemType')
                        ->with('teamMember')
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
        $sql = "SELECT Course_List.*, (SUM(Course_List.Course_progress)/COUNT(Course_List.Course_progress)) AS progress FROM 
                    (SELECT Phase_PRO.*,(SUM(Phase_PRO.phase_progress)/COUNT(Phase_PRO.phase_progress)) AS Course_progress FROM  
                        (SELECT Item.*,SUM(total) AS total,SUM(completed) AS completed,(SUM(completed)/SUM(total)) AS phase_progress  
                            FROM ccoa_teamwork_course_link AS Link  
                            LEFT JOIN ccoa_teamwork_course_phase AS Phase ON Phase.id = Link.course_phase_id  
                      
                            LEFT JOIN ccoa_teamwork_course_manage AS Course ON Link.course_id = Course.id
                            LEFT JOIN ccoa_teamwork_item_manage AS Item ON Item.id = Course.project_id
                            WHERE Link.is_delete = 'N' 
                            GROUP BY Item.id) AS Phase_PRO 
                    GROUP BY Phase_PRO.id) AS Course_List
                WHERE Course_List.id = $id
                GROUP BY Course_List.id";
        
        $itemProgress = ItemManage::findBySql($sql)->one();
        
        return $itemProgress;
    }
}