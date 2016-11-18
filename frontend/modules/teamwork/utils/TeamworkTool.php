<?php

namespace frontend\modules\teamwork\utils;

use common\models\team\Team;
use common\models\teamwork\CourseAnnex;
use common\models\teamwork\CourseLink;
use common\models\teamwork\CourseManage;
use common\models\teamwork\CoursePhase;
use common\models\teamwork\CourseProducer;
use common\models\teamwork\CourseSummary;
use common\models\teamwork\ItemManage;
use common\models\teamwork\Link;
use common\models\teamwork\Phase;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

class TeamworkTool{
    
    private static $instance = null;
    
    /**
     * 模版类型
     * @var integer 
     */
    public $templateType = 1;
    
    /**
     * 创建任务操作
     * @param type $model
     * @param type $post
     * @return type
     */
    public function CreateTask($model, $post)
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            /* @var $model CourseManage*/
            if($model->save()){
                $this->saveCourseProducer($model->id, (!empty($post['producer']) ? $post['producer'] : []));
                $this->addCoursePhase($model->id, $this->templateType);
                $this->addCourseLink($model->id, $this->templateType);
                $this->saveCourseAnnex($model->id, (!empty($post['CourseAnnex']) ? $post['CourseAnnex'] : []));
            }
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            $model->getErrors();
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 更新任务操作
     * @param type $model
     * @param type $post
     * @return type
     */
    public function UpdateTask($model, $post)
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            /* @var $model CourseManage */
            if($model->save()){
                CourseProducer::deleteAll(['course_id' => $model->id]);
                CourseAnnex::deleteAll(['course_id' => $model->id]);
                $this->saveCourseProducer($model->id, (!empty($post['producer']) ? $post['producer'] : []));
                $this->saveCourseAnnex($model->id, (!empty($post['CourseAnnex']) ? $post['CourseAnnex'] : []));
            }
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
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
                ->select(['Course.*', 'Item.item_type_id','Item.item_id', 'Item.item_child_id', 
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
                //->with('courseLinks')
                //->with('coursePhases')
                //->with('producers')
                //->with('speakerTeacher')
                //->with('project')
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
                        //->with('createBy')
                        ->with('itemChild')
                        ->with('item')
                        ->with('itemType')
                        //->with('teamMember')
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
     * 获取一周时间
     * @param type $date            日期
     */
    public function getWeek($date)
    {
        //$date = date('Y-m-d');  //当前日期
        $first = 1; //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
        $w = date('w',strtotime($date));  //获取当前周的第几天 周日是 0 周一到周六是 1 - 6
        //获取本周开始日期，如果$w是0，则表示周日，减去 6 天
        $now_start = date('Y-m-d',strtotime("$date -".($w ? $w - $first : 6).' days')); 
        $now_end = date('Y-m-d',strtotime("$now_start +6 days"));  //本周结束日期
        //$last_start=date('Y-m-d',strtotime("$now_start - 7 days"));  //上周开始日期
        //$last_end=date('Y-m-d',strtotime("$now_start - 1 days"));  //上周结束日期
        
        $week = [
            'start' => $now_start,
            'end' => $now_end
        ];
        return  $week;
    }
    
    /**
     * 计算一个月有多少周
     * @param type $date        实际开始日期
     * @param type $month       开发周期月份
     * @return array
     */
    public function getWeekInfo($date, $month)
    {
        $weekinfo = [];
        $fristMonday = strtotime("first monday of $month");
        $lastMonday = strtotime("last monday of $month");
        $curent = $fristMonday < strtotime($date) ? strtotime($date) : $fristMonday;
        
        while($curent <= $lastMonday){
             $weekinfo[] = [
                'start' => date('Y-m-d',$curent),                             //获取星期一是几号
                'end' => date('Y-m-d',strtotime('+6 day',$curent))           //获取星期天是几号
            ];   
            $curent = strtotime('+7 day',$curent);
        }
        return $weekinfo;
    }
    
    /**
     * 计算每个月的最后一个星期
     * @param type $month   月份
     * @return array
     */
    public function getMonthLastWeek($month)
    {
        $end_date = date('d',strtotime($month.' +1 month -1 day'));   //计算一个月有多少天 
        $first = 1;     //周日是 0 周一到周六是 1 - 6
        $w = date('w',strtotime($month.'-'.$end_date));      //获取每月最后一天是星期几
        //计算每月最后一个星期的星期一
        $lastWeekStart = date('Y-m-d', strtotime($month.'-'.$end_date.' -'.($w ? $w - $first : 6).' days'));
        $lastWeekEnd = date('Y-m-d', strtotime("$lastWeekStart + 6 days"));     //计算每月的最后一个星期的星期天
        
        $lastWeek = [
            'start' => $lastWeekStart,
            'end' =>  $lastWeekEnd
        ];
        return  $lastWeek;
    }

    /**
     * 获取周报信息
     * @param integer | array $courseId         课程ID
     * @param array $week                       一周
     * @param boolean $isAll                    ture为返回全部 
     * @return type
     */
    public function getWeeklyInfo($courseId, $week, $isAll = true) 
    {
        $result = CourseSummary::find()
                  ->where(['course_id' => $courseId])
                  ->andFilterWhere(['>=', 'create_time', ArrayHelper::getValue($week, 'start')])
                  ->andFilterWhere(['<=', 'create_time', ArrayHelper::getValue($week, 'end')]);
        if($isAll)
            return $result->all();
        else
            return $result->one();
        
    }
    
    /**
     * 获取创建者所在团队
     * @param type $uId         用户ID
     * @return integer|array    
     */
    public function getHotelTeam($uId)
    {
        $teamMember = TeamMemberTool::getInstance()->getUserTeamMembers(Yii::$app->user->id);
        $team = ArrayHelper::getColumn($teamMember, 'team_id');
        if(!empty($team) && count($team) == 1)
            return $team[0];
        else
            return ArrayHelper::map($teamMember, 'team.id', 'team.name');
    } 

    /**
     * 获取课程时长总和 AND 个数
     * @param type $condition
     * @return type
     */
    public function getCourseLessionTimesSum($condition)
    {
        $lessionTimes = CourseManage::find()
                        ->where($condition)
                        ->all();
        return ArrayHelper::getColumn($lessionTimes, 'lession_time');
        
    }
    
    /**
     * 保存制作人到表里面
     * @param type $course_id  任务id
     * @param type $post 
     */
    public function saveCourseProducer($course_id, $post)
    {
        $values = [];
        if(!empty($post)){
            /** 重组提交的数据为$values数组 */
            foreach($post as $value)
            {
                $values[] = [
                    'course_id' => $course_id,
                    'producer' => $value,
                ];
            }

            /** 添加$values数组到表里 */
            Yii::$app->db->createCommand()->batchInsert(CourseProducer::tableName(), 
            [
                'course_id',
                'producer',
            ], $values)->execute();
        }  else {
            throw new NotFoundHttpException('开发人员不能为空！');
        }
    }
        
    /**
     * 复制Phase表数据到CoursePhase表
     * @param integer $course_id       课程ID
     * @param integer $templateType    模版类别
     */
    public function addCoursePhase($course_id, $templateType)
    {
        $phase = Phase::find()
                ->andFilterWhere(['template_type_id' => $templateType])
                ->with('links')
                ->with('templateType')
                ->with('createBy')
                ->all();
        
        /** 重组提交的数据为$values数组 */
        $values = [];
        foreach($phase as $value){
            $values[] = [
                'course_id' => $course_id,
                'name' => $value->name,
                'weights' => $value->weights,
                'create_by' => Yii::$app->user->id,
            ];
        }
        
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(CoursePhase::tableName(), [
            'course_id', 'name', 'weights', 'create_by'], $values)->execute();
    }
    
    /**
     * 复制Link表数据到CourseLink表
     * @param integer $course_id            课程ID
     * @param integer $templateType         模版类别
     */
    public function addCourseLink($course_id, $templateType)
    {
        $link = (new Query())
                ->select(['Course_phase.course_id', 'Course_phase.id AS course_phase_id', 'Link.name', 'Link.type',
                    'Link.total', 'Link.completed', 'Link.unit'])
                ->from(['Link' => Link::tableName()])
                ->leftJoin(['Phase' => Phase::tableName()], 'Phase.id = Link.phase_id')
                ->leftJoin(['Course_phase' => CoursePhase::tableName()], 'Course_phase.`name` = Phase.`name`')
                ->where(['course_id' => $course_id])
                ->andFilterWhere(['Link.template_type_id' => $templateType])
                ->all();
        
        /** 重组提交的数据为$values数组 */
        $values = [];
        foreach($link as $value){
            $values[] = [
                'course_id' => $value['course_id'],
                'course_phase_id' => $value['course_phase_id'],
                'name' => $value['name'],
                'type' => $value['type'],
                'total' => $value['total'],
                'completed' => $value['completed'],
                'unit' => $value['unit'],
                'create_by' => Yii::$app->user->id,
            ];
        }
        
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(CourseLink::tableName(), [
            'course_id', 'course_phase_id', 'name', 'type', 'total', 'completed',
            'unit', 'create_by'], $values)->execute();
    }
    
    /**
     * 保存课程附件到表里
     * @param type $course_id
     * @param type $post
     */
    public function saveCourseAnnex($course_id, $post)
    {
        /** 重组提交的数据为$values数组 */
        $values = [];
        if(!empty($post)){
            if(!($this->isSameValue($post['name']) || $this->isSameValue($post['path']))){
                foreach ($post['name'] as $key => $value) {
                   $values[] = [
                       'course_id' => $course_id,
                       'name' => $value,
                       'path' => $post['path'][$key],
                   ];
                }

                /** 添加$values数组到表里 */
                Yii::$app->db->createCommand()->batchInsert(CourseAnnex::tableName(), [
                    'course_id', 'name', 'path'], $values)->execute();
            }else{
                throw new NotAcceptableHttpException('请不要重复上传相同附件！');
            }
        }
    }
    
    /**
     * 获取当前用户是否有权限
     * @param string $keyName       数组键值名称
     * @param mixed $value          判断条件的值
     * @return boolean              true 为是
     */ 
    public function getIsAuthority($keyName, $value)
    {
        //查出成员表里面所有队长
        $teamMember = TeamMemberTool::getInstance()->getUserTeamMembers(Yii::$app->user->id);
        if(!empty($teamMember) || isset($teamMember)){
            $authority = ArrayHelper::getColumn($teamMember, $keyName);
            if(in_array($value, $authority))
               return true;
        }
        return false;
    }
    
    /**
     * 获取当前用户是否隶属于该课程下的制作人员
     * @param type $course_id   课程id
     * @return boolean          true 为是
     */
    public function getIsUserBelongProducer($course_id)
    {
        $producer = CourseProducer::findAll(['course_id' => $course_id]);
        $producerId = ArrayHelper::getColumn($producer, 'producer');
        $teamMember = TeamMemberTool::getInstance()->getTeammemberById($producerId);
        $uId = ArrayHelper::getColumn($teamMember, 'u_id');
        if(!empty($currentUser) || isset($currentUser) || !empty($course_id)){
            if(in_array(\Yii::$app->user->id, $uId))
                return true;
        }
        return false;
    }
    
    /**
     * 检查一个数组是否有重复值
     * @param type $array
     * @return boolean  ture为是
     */
    public function isSameValue($array)
    {
        if(count($array) != count(array_unique($array)))
            return true;
        else 
            return false;
    }
    
    /**
     * 获取单例
     * @return TeamworkTool
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new TeamworkTool();
        }
        return self::$instance;
    }
}