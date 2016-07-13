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
     * @param type $course_id  课程ID
     */
    public function addCoursePhase($course_id)
    {
        $phase = Phase::find()
                ->with('links')
                ->with('createBy')
                ->all();
        
        $values = [];
        /** 重组提交的数据为$values数组 */
        foreach($phase as $value)
        {
            $values[] = [
                'course_id' => $course_id,
                'phase_id' => $value->id,
            ];
        }
        
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(CoursePhase::tableName(), 
        [
            'course_id',
            'phase_id',
        ], $values)->execute();
    }
    
    /**
     * 复制Link表数据到CourseLink表
     * @param type $course_id   课程ID
     */
    public function addCourseLink($course_id)
    {
        $link = Link::find()
                ->with('createBy')
                ->with('phase')
                ->with('courseLinks')
                ->all();
        
        $values = [];
        /** 重组提交的数据为$values数组 */
        foreach($link as $value)
        {
            $values[] = [
                'course_id' => $course_id,
                'course_phase_id' => $value->phase_id,
                'link_id' => $value->id,
            ];
        }
        
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(CourseLink::tableName(), 
        [
            'course_id',
            'course_phase_id',
            'link_id'
        ], $values)->execute();
    }
    
    /**
     * 获取课程阶段进度
     * @param type $course_id   课程ID
     * @return type
     */
    public function getCoursePhaseProgressAll($course_id)
    {
        $sql = "SELECT Link.course_id, Link.course_phase_id as phase_id,Phase_Temp.`name`,SUM(total) AS total,SUM(completed) AS completed,(SUM(completed)/SUM(total)) AS progress  
                FROM ccoa_teamwork_course_link AS Link  
                LEFT JOIN ccoa_teamwork_course_phase AS Phase ON Phase.id = Link.course_phase_id  
                LEFT JOIN ccoa_teamwork_phase_template AS Phase_Temp ON Phase.phase_id = Phase_Temp.id  
                WHERE Link.course_id = $course_id AND Link.is_delete = 'N'
                GROUP BY Link.course_phase_id";
        
        $coursePhaseProgress = CoursePhase::findBySql($sql)
                ->with('course')
                ->with('phase')
                //->with('courseLinks')
                ->all();
        return $coursePhaseProgress;
    }
    
    /**
     * 获取所有课程进度
     * @param type $project_id  项目ID
     * @return type $project_id,非null返回项目对应下的所有课程进度, 为null返回所有课程进度
     */
    public function getCourseProgressAll($project_id = null)
    {
        $project_id = $project_id == null ?  '' : "AND Course.project_id = $project_id"; 
        
        $sql = "SELECT id,Phase_PRO.project_id,Phase_PRO.course_id,(SUM(Phase_PRO.progress)/COUNT(Phase_PRO.progress)) AS progress FROM  
                    (SELECT Course.project_id, Course.course_id, Link.course_id AS id,SUM(total) AS total,SUM(completed) AS completed,(SUM(completed)/SUM(total)) AS progress  
                    FROM ccoa_teamwork_course_link AS Link  
                    LEFT JOIN ccoa_teamwork_course_phase AS Phase ON Phase.id = Link.course_phase_id  
                    LEFT JOIN ccoa_teamwork_phase_template AS Phase_Temp ON Phase.phase_id = Phase_Temp.id
                    LEFT JOIN ccoa_teamwork_course_manage AS Course ON Link.course_id = Course.id
                    WHERE Link.is_delete = 'N' AND Course.`status` = 1 $project_id
                    GROUP BY Link.course_id) AS Phase_PRO 
                GROUP BY id";
        
        $courseProgress = CourseManage::findBySql($sql)
                        ->with('course')
                        ->with('courseLinks')
                        ->with('coursePhases')
                        ->with('producers')
                        ->with('speakerTeacher')
                        ->with('project')
                        ->all();
        return $courseProgress;
    }
    
    /**
     * 获取单条课程进度
     * @param type $id
     */
    public function getCourseProgressOne($id)
    {
        $sql = "SELECT Course.*,SUM(total) AS total,SUM(completed) AS completed,(SUM(completed)/SUM(total)) AS progress  
		FROM ccoa_teamwork_course_link AS Link  
		LEFT JOIN ccoa_teamwork_course_phase AS Phase ON Phase.id = Link.course_phase_id  
		LEFT JOIN ccoa_teamwork_phase_template AS Phase_Temp ON Phase.phase_id = Phase_Temp.id
		LEFT JOIN ccoa_teamwork_course_manage AS Course ON Link.course_id = Course.id
		WHERE Link.is_delete = 'N' AND Course.id = $id
		GROUP BY id";
        
        $courseProgress = CourseManage::findBySql($sql)->one();
       
        return $courseProgress;
    }
    
    /**
     * 获取所有项目进度
     * @param type $status     状态
     * @param type $team_id    团队ID
     * @return type
     */
    public function getItemProgressAll($status = null, $team_id = null){
        $status = $status == null ? '1' : "(Course.`status` = $status)";
        $team_id = $team_id == null ? '' : "AND (Course.team_id = $team_id)";
        $sql = "SELECT Item_course.*,(SUM(Course_link.completed)/SUM(Course_link.total)) AS progress FROM     
                    (SELECT Item.id, Course.id AS course_id,Item.item_type_id,Item.item_id, Item.item_child_id,Course.team_id,Course.`status` 
                        FROM ccoa_teamwork_item_manage AS Item  
                        LEFT JOIN ccoa_teamwork_course_manage AS Course ON Course.project_id = Item.id
                        WHERE $status $team_id ) AS Item_course
                LEFT JOIN ccoa_teamwork_course_link AS Course_link ON Item_course.course_id = Course_link.course_id  
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
                            LEFT JOIN ccoa_teamwork_phase_template AS Phase_Temp ON Phase.phase_id = Phase_Temp.id
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