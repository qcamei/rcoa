<?php

namespace frontend\modules\teamwork\utils;

use common\models\expert\Expert;
use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\teamwork\CourseLink;
use common\models\teamwork\CourseManage;
use common\models\teamwork\CoursePhase;
use common\models\teamwork\CourseProducer;
use common\models\teamwork\ItemManage;
use common\models\teamwork\Link;
use common\models\teamwork\Phase;
use common\models\User;
use wskeee\framework\models\Item;
use Yii;
use yii\db\Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class TeamworkBatchAdd {
    
    private static $instance = null;
    
    /**
     * 添加基础数据到Teamwork_item表里面
     * @param type $itemTypes
     * @param type $itemChilds
     */
    public function addTwItem($itemTypes, $itemChilds)
    {
        $doneTwItems = ItemManage::find()
                       ->select(['Tw_item.item_child_id', 'Fm_item_child.name as itemChildName'])
                       ->from(['Tw_item' => ItemManage::tableName()])
                       ->leftJoin(['Fm_item_child' => Item::tableName()], 'Fm_item_child.id = Tw_item.item_child_id')
                       ->asArray()
                       ->all();
        
        //已经存在的数据
        $doneItemChildNames = ArrayHelper::map($doneTwItems, 'item_child_id', 'itemChildName');
        
        /** 组装数组 */
        $rows = [];
        foreach ($itemChilds as $parentId => $itemChild) {
            foreach (array_flip($itemChild) as $itemChildId => $itemChildName) {
                if(!isset($doneItemChildNames[$itemChildId]))
                    $rows[] = [
                        'item_type_id' => (int)$itemTypes[$parentId],
                        'item_id' => $parentId,
                        'item_child_id' => (int)$itemChildId,
                        'created_at' => time(),
                        'updated_at' => time(),
                        'forecast_time' => date('Y-m-d H:i', strtotime('+3 day', time()))
                    ];
            }
        }
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            $number = Yii::$app->db->createCommand()->batchInsert(ItemManage::tableName(), [
                'item_type_id', 'item_id', 'item_child_id', 'created_at', 'updated_at', 'forecast_time'], $rows)->execute();
            if(count($rows) > 0 && $number > 0) {
                $trans->commit();  //提交事务
                Yii::$app->getSession()->setFlash('success','操作成功！');
            }
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
        
        return ArrayHelper::getColumn($rows, 'item_child_id');
    }
    
    /**
     * 添加基础数据到Teamwork_course表里面
     * @param array $courses                 基础课程
     * @param array $courseInfoArray         上传上来的数据
     */
    public function addTwCourse($courses, $courseInfoArray)
    {
        $doneTwCourses = CourseManage::find()
                       ->select(['Tw_course.course_id', 'Tw_course.project_id', 'Fm_item.name'])
                       ->from(['Tw_course' => CourseManage::tableName()])
                       ->leftJoin(['Fm_item' => Item::tableName()], 'Fm_item.id = Tw_course.course_id')
                       ->asArray()
                       ->all();
        
        //已经存在的课程数据
        $doneTwCourses = ArrayHelper::map($doneTwCourses, 'name', 'course_id');
        $mode = array_flip(CourseManage::$modeName);
        
        $rows = [];
        foreach ($courses as $projectId => $course) {
            foreach ($course as $name => $id) {
                if(!isset($doneTwCourses[$name]) && isset($courseInfoArray[$name])){
                    $rows[] = [
                        'project_id' => $projectId,
                        'course_id' => (int)$id,
                        'teacher' => $this->getSpeakerTeacher($courseInfoArray[$name][0]),
                        //'mode' => $mode[$courseInfoArray[$name][1]],
                        'credit' => is_numeric($courseInfoArray[$name][2]) ? $courseInfoArray[$name][2] : null,
                        'lession_time' => is_numeric($courseInfoArray[$name][3]) ? $courseInfoArray[$name][3] : null,
                        'video_length' => $this->videoLenFormat($courseInfoArray[$name][4]),
                        'question_mete' => is_numeric($courseInfoArray[$name][5]) ? $courseInfoArray[$name][5] : null,
                        'case_number' => is_numeric($courseInfoArray[$name][6]) ? $courseInfoArray[$name][6] : null,
                        'activity_number' => is_numeric($courseInfoArray[$name][7]) ? $courseInfoArray[$name][7] : null,
                        'team_id' => $this->getTeam($courseInfoArray[$name][8]),
                        'course_ops' => $this->getCourseOps($courseInfoArray[$name][9]),
                        'created_at' => time(),
                        'updated_at' => time(),
                    ];
                }
            }
        }
       
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            $number = Yii::$app->db->createCommand()->batchInsert(CourseManage::tableName(), [
                'project_id', 'course_id', 'teacher', 'credit', 'lession_time', 'video_length', 'question_mete', 
                'case_number', 'activity_number', 'team_id', 'course_ops', 'created_at', 'updated_at'], $rows)->execute();
            if(count($rows) > 0 && $number > 0) {
                $trans->commit();  //提交事务
                Yii::$app->getSession()->setFlash('success','操作成功！');
            }
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
        
        return ArrayHelper::getColumn($rows, 'course_id');
    }
    
    /**
     * 添加课程制作人到teamwork_course_producer表里面
     * @param type $cIdProducers                        课程制作人
     */
    public function addTwProducer($cIdProducers)
    {
       $doneTeamMember = TeamMember::find()
                         ->select(['Member.id', 'User.nickname'])
                         ->from(['Member' => TeamMember::tableName()])
                         ->leftJoin(['User' => User::tableName()], 'User.id = Member.u_id')
                         ->asArray()
                         ->all();
               
        //已经存在的课程数据
        $doneTeamMember = ArrayHelper::map($doneTeamMember, 'nickname', 'id');
        
        $rows = [];
        /** 重组提交的数据为$rows数组 */
        foreach ($cIdProducers as $cId => $producers) {
            foreach (array_flip($producers) as $nickname => $element) {
                if(isset($doneTeamMember[$nickname]))
                    $rows[] = [
                        'course_id' => $cId,
                        'producer' => $doneTeamMember[$nickname]
                    ];
            }
        }
        
        /** 添加$rows数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(CourseProducer::tableName(), [
            'course_id','producer'], $rows)->execute();

    }
    
    /**
     * 复制Phase表数据到CoursePhase表
     * @param array $courseIds          课程ID
     * @param integer $templateType     模版类别
     */
    public function addCoursePhase($courseIds, $templateType = null)
    {
        $phase = Phase::find()
                ->andFilterWhere(['template_type_id' => $templateType])
                //->with('templateType')
                ->all();
        
        /** 重组提交的数据为$values数组 */
        $rows = [];
        foreach ($courseIds as $courseId) {
            foreach($phase as $value)
                $rows[] = [
                    'course_id' => $courseId,
                    'name' => $value->name,
                    'weights' => $value->weights,
                ];
        }
        
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(CoursePhase::tableName(), [
            'course_id', 'name', 'weights'], $rows)->execute();
    }
    
    /**
     * 复制Link表数据到CourseLink表
     * @param array $courseIds              课程ID
     * @param integer $templateType         模版类别
     */
    public function addCourseLink($courseIds, $templateType = null)
    {
        $link = (new Query())
                ->select(['Course_phase.course_id', 'Course_phase.id AS course_phase_id', 'Link.name', 'Link.type',
                    'Link.total', 'Link.completed', 'Link.unit'])
                ->from(['Link' => Link::tableName()])
                ->leftJoin(['Phase' => Phase::tableName()], 'Phase.id = Link.phase_id')
                ->leftJoin(['Course_phase' => CoursePhase::tableName()], 'Course_phase.`name` = Phase.`name`')
                ->where(['course_id' => $courseIds])
                ->andFilterWhere(['Link.template_type_id' => $templateType])
                ->all();
        
        /** 重组提交的数据为$values数组 */
        $rows = [];
        foreach($link as $value)
            $rows[] = [
                'course_id' => $value['course_id'],
                'course_phase_id' => $value['course_phase_id'],
                'name' => $value['name'],
                'type' => $value['type'],
                'total' => $value['total'],
                'completed' => $value['completed'],
                'unit' => $value['unit'],
            ];
        
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(CourseLink::tableName(), [
            'course_id', 'course_phase_id', 'name', 'type', 'total', 'completed','unit'], $rows)->execute();
    }


    /**
     * 获取主讲讲师 u_id
     * @param string $teacher         主讲讲师
     * @return string
     */
    public function getSpeakerTeacher($teacher)
    {
        $result = Expert::find()
                ->select(['u_id'])
                ->from(['Expert' => Expert::tableName()])
                ->leftJoin(['User' => User::tableName()], 'User.id = Expert.u_id')
                ->where(['User.nickname' => $teacher])
                ->one();
        
        return $result->u_id;
    }

    /**
     * 获取团队id
     * @param  string $teamName     团队名称
     * @return integer
     */
    public function getTeam($teamName)
    {
        $result = Team::find()
                ->select(['id'])
                ->where(['name' => $teamName])
                ->one();
        
        return $result->id;
    }
    
    /**
     * 获取课程运维人id
     * @param string $courseOps       运维人
     * @return integer
     */
    public function getCourseOps($courseOps)
    {
        $user = User::find()
                ->select(['id'])
                ->where(['nickname' => $courseOps])
                ->all();
        $result = TeamMember::find()
                  ->select(['id'])
                  ->where(['u_id' => ArrayHelper::getColumn($user, 'id')])
                  ->one();
        
        return $result->id;
    }

    /**
     * 视频格式转换
     * @param string $format        格式
     * @return int
     */
    public function videoLenFormat($format)
    {
        if(!is_numeric($format)){
            if(strpos($format ,":"))  
                $times =  explode(":", $format);  
            else if(strpos($format ,'：'))
                $times =  explode("：", $format);   
            $h = (int)$times[0] ;  
            $m = (int)$times[1];  
            $s = count($times) == 3 ? (int)$times[2] : 0;  
            $format = $h * 3600 + $m * 60 + $s;
            if($format > 0)    
                return $format;
            else 
                return 1;
        }
        
        return 1;
    }

    

    /**
     * 获取单例
     * @return TeamworkBatchAdd
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new TeamworkBatchAdd();
        }
        return self::$instance;
    }
}
