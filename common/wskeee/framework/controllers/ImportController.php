<?php

namespace wskeee\framework\controllers;

use common\models\demand\DemandTask;
use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\teamwork\CourseLink;
use common\models\teamwork\CourseManage;
use common\models\teamwork\CoursePhase;
use common\models\teamwork\CourseProducer;
use common\models\teamwork\Link;
use common\models\teamwork\Phase;
use common\models\User;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use wskeee\utils\DateUtil;
use wskeee\utils\ExcelUtil;
use Yii;
use yii\db\Exception;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\UploadedFile;

class ImportController extends Controller
{
    
    //禁用csrf验证
    public $enableCsrfValidation = false;
    
    /* 课程原数据 */
    private $courses = [];
    /* 用户与id映射 name => id */
    private $userMap = [];
    /* 用户团队成员 */
    private $teamMembers = [];
    /* 日志 */
    private $logs = [];
    public function behaviors()
    {
        return [
            //验证delete时为post传值
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'create' => ['post'],
                ],
            ],
        ];
    }
    /**
     * 上传文件自动导入
     */
    public function actionUpload(){
        $upload = UploadedFile::getInstanceByName('import-file');
        if($upload != null)
        {
            $string = $upload->name;
            $excelutil = new ExcelUtil();
            $excelutil->load($upload->tempName);
            $columns = $excelutil->getSheetDataForColumn()[0]['data'];
            
            for($i=0,$len=count($columns[0])-2;$i<$len;$i++){
                $vIndex = $i+2;
                $this->courses [] = [
                    'item_type_id'      =>  $columns[0][$vIndex],           //行业
                    'item_id'           =>  $columns[1][$vIndex],           //层次/类型
                    'item_child_id'     =>  $columns[2][$vIndex],           //专业/工种
                    'course_id'         =>  $columns[3][$vIndex],           //课程
                    'teacher'           =>  $columns[4][$vIndex],           //主讲讲师
                    'mode'              =>  $columns[5][$vIndex],           //建设模式
                    'credit'            =>  $columns[6][$vIndex],           //学分    
                    'lesson_time'       =>  $columns[7][$vIndex],           //学时
                    'video_length'      =>  $columns[8][$vIndex],           //视频时长
                    'question_mete'     =>  $columns[9][$vIndex],           //题量
                    'case_number'       =>  $columns[10][$vIndex],          //案例数
                    'activity_number'   =>  $columns[11][$vIndex],          //活动数
                    'team_id'           =>  $columns[12][$vIndex],          //团队
                    'dev_users'         =>  $columns[13][$vIndex],          //开发人员
                    'course_ops'        =>  $columns[14][$vIndex],          //运维人
                    'real_carry_out'    =>  $columns[15][$vIndex],          //实际完成时间
                    'path'              =>  $columns[16][$vIndex],          //路径
                    'des'               =>  $columns[17][$vIndex],          //描述
                    'demand_task_id'    =>  null,                           //课程需求id
                    'course_task_id'    =>  null,                           //课程开发id
                    'create_by'         =>  null,                           //课程创建人，默认使用 开发人员 的第一位
                    'create_by_teammeber'=> null,                           //课程创建人 成员id，默认使用 开发人员 的第一位
                ];
            }
            $code = 0;
            $msg = '';
            try{
                //检查用户数据
                $this->checkUser();
                //更新团队
                $this->updateTeam();
                 //创建基础数据 行业
                $this->createFmItemType();
                //插入基础数据 - 层次/类型
                $this->createFmItem('层次/类型', 'item_id', null, Item::LEVEL_COLLEGE);
                //插入基础数据 - 专业/工种
                $this->createFmItem('专业/工种', 'item_child_id', 'item_id', Item::LEVEL_PROJECT);
                //插入基础数据 - 课程
                $this->createFmItem('课程', 'course_id', 'item_child_id', Item::LEVEL_COURSE);
                //创建课程需求
                //$this->createDemandTask();
                //创建课程开发数据
                //$this->createCourseDev();
                //创建课程开发人员关联
                //$this->createTwProducer();
                //创建阶段数据
                //$this->createTwCoursePhase();
                //创建环节数据
                //$this->createTwCourseLink();
            } catch (\Exception $ex) {
                $code = 1;
                $msg = $ex->getMessage();
            }
            
            return $this->render('upload_result',['code'=>$code,'msg'=>$msg,'logs'=>$this->logs,'courses'=>$this->courses]);    
        }
        return $this->render('upload');
    }
    
    /**
     * 添加课程
     * @param array $courses
     */
    public function actionCreate(){
        Yii::$app->getResponse()->format = 'json';
        $this->courses = json_decode(Yii::$app->getRequest()->getRawBody(),true)['courses'];
        
        $code = 0;
        $msg = '';
        try{
            //创建课程需求
            $this->createDemandTask();
            //创建课程开发数据
            $this->createCourseDev();
            //创建课程开发人员关联
            $this->createTwProducer();
            //创建阶段数据
            $this->createTwCoursePhase();
            //创建环节数据
            $this->createTwCourseLink();
        } catch (\Exception $ex) {
            $code = 1;
            $msg = $ex->getMessage();
        }
        return ['code'=>$code,'msg'=>$msg,'logs'=>$this->logs];
    }
    
     /**
     * 检查用户数据是否健全
     */
    private function checkUser(){
        //教师
        $teachers = ArrayHelper::getColumn($this->courses, 'teacher');
        //开发人
        $dev_users = ArrayHelper::getColumn($this->courses, 'dev_users');
        //运营人
        $course_ops = ArrayHelper::getColumn($this->courses, 'course_ops');
        
        $allTeamMemberNames = array_unique(array_merge(explode(',',implode(',',$dev_users)),$course_ops));
        $allUsers = array_unique(array_merge($teachers, $allTeamMemberNames));
        $unFindUser = [];
        
        //=======================
        // 数据查询
        //=======================
        //查寻所有用户id
        $result = (new Query())
                    ->select(['id','nickname AS name'])
                    ->from(User::tableName())
                    ->where(['nickname'=>$allUsers])
                    ->all();
        //所有用户映射 name => id
        $this->userMap = $userMap = ArrayHelper::map($result, 'name', 'id');

        //查寻所有teamMember
        $teamMemberUserIds = [];
        foreach ($allTeamMemberNames as $index => $name) {
            if(!isset($userMap[$name]))
                $unFindUser[] = $name;
            else
                $teamMemberUserIds[] = $userMap[$name];
        }
        $this->teamMembers = $teamMembers = (new Query())
                    ->select(['TeamMember.id','TeamMember.team_id','TeamMember.u_id','User.nickname AS name'])
                    ->from(['TeamMember'=>  TeamMember::tableName()])
                    ->leftJoin(['User'=>  User::tableName()], 'User.id = TeamMember.u_id')
                    ->where(['u_id'=>$teamMemberUserIds])
                    ->all();
        //所有团队用户映射 name => id
        $teamMemberMap = ArrayHelper::map($teamMembers, 'name', 'id');
        
        //=======================
        // 数据替换
        //=======================
        foreach ($this->courses as &$course){
            //教师
            $teacherName = $course['teacher'];
            if(!isset($userMap[$teacherName]))
                $unFindUser[] = $teacherName;
            else
                $course['teacher'] = $userMap[$teacherName];
            //开发人员
            $_dev_users = explode(',', $course['dev_users']);
            $_dev_users_team_ids = [];
            foreach ($_dev_users as $name){
                if(!isset($teamMemberMap[$name]))
                    $unFindUser [] = $name;
                else
                    $_dev_users_team_ids [] = $teamMemberMap[$name];
            }
            $course['dev_users'] = implode(',', $_dev_users_team_ids);
            //创建create_by
            $course['create_by'] = $userMap[$_dev_users[0]];
            $course['create_by_teammeber'] = $_dev_users_team_ids[0];
            //运营人
            $opsName = $course['course_ops'];
            if(!isset($teamMemberMap[$opsName]))
                $unFindUser[] = $opsName;
            else
                $course['course_ops'] = $teamMemberMap[$opsName];
        }
        $unFindUser = array_unique($unFindUser);
        $unFindNum = count($unFindUser);
        $this->addLog('用户检查', sprintf('检查完成，共有 %d 个用户，其中 %d 个为未知用户：%s', count($allUsers), $unFindNum,  $unFindNum > 0 ? implode(',', $unFindUser) : '无'), $unFindNum==0);
        
        if($unFindNum>0){
            throw new Exception('发现未知用户！');
            return;
        }
    }
    
    /**
     * 更新团队
     */
    private function updateTeam(){
        $teamNames = array_unique(ArrayHelper::getColumn($this->courses, 'team_id'));
        $unFindTmeas = [];
        //查询团队
        $teams = (new Query())
                ->select(['id', 'name'])
                ->from(Team::tableName())
                ->where(['name' => $teamNames])
                ->all();
        $teamMap = ArrayHelper::map($teams, 'name', 'id');

        //更新
        foreach ($this->courses as &$course) {
            $teamName = $course['team_id'];
            if (!isset($teamMap[$teamName]))
                $unFindTmeas[] = $teamName;
            else
                $course['team_id'] = $teamMap[$teamName];
        }
        
        $unFindTmeas = array_unique($unFindTmeas);
        $unFindNum = count($unFindTmeas);
        $this->addLog('团队检查', sprintf('检查完成，共有 %d 个团队，其中 %d 个为未知团队：%s', count($teamNames),  $unFindNum,  $unFindNum > 0 ? implode(',', $unFindTmeas) : '无'), $unFindNum==0);
        
        if($unFindNum>0){
            throw new Exception('发现未知团队！');
            return;
        }
    }
    
    /**
     * 添加基础行业数据
     * @return array
     */
    private function createFmItemType(){
        $tableName = ItemType::tableName();
        $unFinds = [];
        //所有需要添加的行业
        $names = array_unique(ArrayHelper::getColumn($this->courses,'item_type_id'));

        //已存在数据
        $doneItems = (new Query())
                    ->select(['name'])
                    ->from($tableName)
                    ->where(['name'=>$names])
                    ->column(Yii::$app->db);
        
        $doneItems = array_diff($names, $doneItems);
        /* 组装数据 */
        $items = [];
        foreach ($doneItems as $name){
            $items [] = [$name];
        }
        
        //插入新数据(过滤已存在数据)，并且返回插入的数据条数
        $data = $this->batchInsert($tableName, ['name'], $items);
        
        $this->addLog('行业',sprintf('数据创建完成！本次需要插入 %d 条，新增 %d 条！',count($names), $data['num']),$data['result']);
        
        if(!$data['result'])
        {
            throw new \Exception($data['msg']);
            return;
        }
        
        /* 查询新添加行业的id */
        $results = (new Query())
                    ->from($tableName)
                    ->where(['name' => $names])
                    ->all();
        
        $results = ArrayHelper::map($results, 'name', 'id');
        
        /* 更新 */
        foreach ($this->courses AS &$course){
            if(!isset($results[$course['item_type_id']]))
                $unFinds [] = $course['item_type_id'];
            else
                $course['item_type_id'] = $results[$course['item_type_id']];
        } 
        $unFinds = array_unique($unFinds);
        if(count($unFinds)>0)
            $this->addLog('行业', sprintf('数据更新错误！共 %d 找不到：%s ', count($unFinds), implode(',', $unFinds)),false);
        
        if(count($unFinds)>0)
            throw new Exception('数据更新失败！');
    }
    
     /**
      * 创建基础 数据 
      * @param string $title                基础数据标题
      * @param string $item_key             基础数据键名
      * @param string $item_parent_key      父级键名
      * @param integer $level               等级
      * @return void
      * @throws \Exception
      */
    private function createFmItem($title,$item_key,$item_parent_key,$level){
        
        $tableName = Item::tableName();
        $unFinds = [];
        //是否存parent_id
        $noParent = $item_parent_key == null;
        //所有需要添加的
        $news = [];
        $parent_ids = $noParent ? [] : array_unique(ArrayHelper::getColumn($this->courses, $item_parent_key));
        $names = array_unique(ArrayHelper::getColumn($this->courses, $item_key));;
        $curTime = time();
        
        //已存在数据
        $doneItems = (new Query())
                    ->select(['CONCAT_WS("_",parent_id,`name`) AS p_n'])//parent_id _ name 为键
                    ->from($tableName)
                    ->where(['name'=>  $names,'level'=>$level])
                    ->andFilterWhere(['parent_id'=> $parent_ids])
                    ->all(Yii::$app->db);
        
        $doneItems = ArrayHelper::map($doneItems, 'p_n', 'p_n');
        
        
        /* 组装数据 */
        $items = [];
        foreach ($this->courses as $course){
            //检查是否已存在 pn = parent_id _ name，noParent 情况下只用name作键
            $pn = $noParent ? $course[$item_key] : $course[$item_parent_key] . '_' . $course[$item_key];
            if (!isset($doneItems[$pn]) && !isset($news[$pn]))
                $items [] = [$course[$item_key], $noParent ? null : $course[$item_parent_key], $level, $curTime, $curTime];
            $news [$pn] = true;
        }
        
        //插入新数据(过滤已存在数据)，并且返回插入的数据条数
        $data = $this->batchInsert($tableName, ['name','parent_id','level','created_at','updated_at'], $items);
        
        $this->addLog($title,sprintf('数据创建完成！本次需要插入 %d 条，新增 %d 条！',count($news), $data['num']),$data['result']);
        
        if(!$data['result'])
        {
            throw new \Exception($data['msg']);
            return;
        }
        /* 查询新添加的id */
        $doneItems = (new Query())
                    ->select(['id','CONCAT_WS("_",parent_id,`name`) AS p_n'])//parent_id _ name 为键
                    ->from($tableName)
                    ->where(['name'=>  $names,'level'=>$level])
                    ->andFilterWhere(['parent_id'=> $parent_ids])
                    ->all();
        
        $doneItems = ArrayHelper::map($doneItems, 'p_n', 'id');
        
        /* 更新 */
        foreach ($this->courses AS &$course){
            //检查是否已存在 pn = parent_id _ name，noParent 情况下只用name作键
            $pn = $noParent ? $course[$item_key] : $course[$item_parent_key] . '_' . $course[$item_key];
            if(!isset($doneItems[$pn]))
                $unFinds [] = $course[$item_key];
            else
                $course[$item_key] = $doneItems[$pn];
        }
        $unFinds = array_unique($unFinds);
        if(count($unFinds)>0)
            $this->addLog($title, sprintf('数据更新错误！共 %d 找不到：%s ',count($unFinds), implode(',', $unFinds)),false);
        
        if(count($unFinds)>0)
            throw new Exception('数据更新失败！');
    }
    
    /**
     * 创建需求任务
     */
    private function createDemandTask(){
        $tableName = DemandTask::tableName();
        //$teamMemberMap = ArrayHelper::map($this->teamMembers, 'id', 'u_id');
        $courseIds = array_unique(ArrayHelper::getColumn($this->courses, 'course_id'));
        
        $hasCreateds = [];
        
        //=========================
        //插入数据
        //=========================
        //查寻已建的课程
        $doneCourses = (new Query())
                        ->select(['DemandTask.id','DemandTask.course_id','Course.name'])
                        ->from(['DemandTask'=> $tableName])
                        ->leftJoin(['Course'=>  Item::tableName()], 'DemandTask.course_id = Course.id')
                        ->where(['course_id'=>$courseIds])
                        ->all();
        
        $doneCourseMap = ArrayHelper::map($doneCourses, 'course_id', 'name');
        
        //组合数据
        $demandTaskRows = [];
        foreach ($this->courses as $course){
            $course_id = $course['course_id'];
            if(!isset($doneCourseMap[$course_id]))
            {
                $demandTaskRows [] = [
                    null,                                                       //id，自增    
                    $course['item_type_id'],                                    //行业
                    $course['item_id'],                                         //层次/类型
                    $course['item_child_id'],                                   //专业/工种
                    $course['course_id'],                                       //课程
                    $course['teacher'],                                         //教师
                    $course['lesson_time'],                                     //课时
                    $course['credit'],                                          //学分
                    '无',                                                       //课程简介
                    trim($course['mode']) == '新建' ? 0 : 1,                    //模式
                    $course['team_id'],                                         //开发团队ID
                    $course['create_by'],                                       //承接人，选择开发人员第一个,
                    '2012-12-12 12:12',                                         //计划验收时间
                    '2012-12-12 12:12',                                         //实际验收时间
                    15,                                                         //状态
                    100,                                                        //进度
                    'ef3c21bbe97e1e9e95f2a4c46ec198fa',                         //创建者
                    null,                                                       //创建团队
                    strtotime('2012-12-12 12:12'),                              //创建时间
                    strtotime('2012-12-12 12:12'),                              //更新时间
                    '无 (任务为自动创建)',                                       //描述
                ];
            }else
                $hasCreateds [] = $doneCourseMap[$course_id];
        }
        
        //插入新数据(过滤已存在数据)，并且返回插入的数据条数
        $data = $this->batchInsert($tableName, [], $demandTaskRows);
        
        $this->addLog('课程需求',sprintf('数据创建完成！本次需要插入 %d 条，新增 %d 条！以下课程已经存在：%s',count($courseIds), $data['num'], (count($hasCreateds)>0 ? implode(',', $hasCreateds) : '无')),$data['result']);
        
        if(!$data['result'])
        {
            throw new \Exception($data['msg']);
            return;
        }
        //=========================
        //更新数据
        //=========================
        //查询所有课程需求
        $demandTasks = (new Query())
                ->select(['id','course_id'])
                ->from($tableName)
                ->where(['course_id'=>$courseIds])
                ->all();
        
        $demandTaskMap = ArrayHelper::map($demandTasks, 'course_id', 'id');
       
        foreach ($this->courses as &$course){
            $course['demand_task_id'] = $demandTaskMap[$course['course_id']];
        }
    }
    
    /**
     * 创建课程开发数据
     */
    private function createCourseDev(){
        $tableName = CourseManage::tableName();
        //$teamMemberMap = ArrayHelper::map($this->teamMembers, 'id', 'u_id');
        $courseIds = ArrayHelper::getColumn($this->courses, 'course_id');
        $hasCreateds = [];
       
        //=========================
        //插入数据
        //=========================
        //查寻已建的课程
        $doneCourses = (new Query())
                        ->select(['TWCourse.id','Course.id AS course_id','Course.name'])
                        ->from(['TWCourse'=> $tableName])
                        ->leftJoin(['DemandTask'=> DemandTask::tableName()],'DemandTask.id=TWCourse.demand_task_id')
                        ->leftJoin(['Course'=>  Item::tableName()], 'DemandTask.course_id = Course.id')
                        ->where(['course_id'=>$courseIds])
                        ->all();
        
        $doneCourseMap = ArrayHelper::map($doneCourses, 'course_id', 'name');
        //组合数据
        $devCourseRows = [];
        foreach ($this->courses as $course) {
            $course_id = $course['course_id'];
            if (!isset($doneCourseMap[$course_id])) {
                $devCourseRows [] = [
                    $course['demand_task_id'],                                              //课程需求id
                    DateUtil::timeToInt($course['video_length']),                           //视频时长
                    is_numeric($course['question_mete']) ? $course['question_mete'] : 0,    //题目数
                    is_numeric($course['case_number']) ? $course['case_number'] : 0,        //案例数
                    is_numeric($course['activity_number']) ? $course['activity_number'] : 0,//活动数
                    $course['team_id'],                                                     //创建团队
                    $course['course_ops'],                                                  //运营人
                    $course['create_by'],                                                   //创建者，选择开发人员第一个,,
                    strtotime('2012-12-12 12:12'),                                          //创建时间        
                    strtotime('2012-12-12 12:12'),                                          //更新时间
                    '2012-12-12 12:12',                                                     //计划开始时间
                    '2012-12-12 12:12',                                                     //计划结束时间
                    '2012-12-12 12:12',                                                     //实际开始时间
                    '2012-12-12 12:12',                                                     //实际结束时间
                    15,                                                                     //状态
                    $course['des'],                                                         //描述
                    $course['path'],                                                        //成品路径
                ];
            } else
                $hasCreateds [] = $doneCourseMap[$course_id];
        }
        //插入新数据(过滤已存在数据)，并且返回插入的数据条数
        $data = $this->batchInsert($tableName, [
            'demand_task_id',
            'video_length',
            'question_mete',
            'case_number',
            'activity_number',
            'team_id',
            'course_ops',
            'create_by',
            'created_at',
            'updated_at',
            'plan_start_time',
            'plan_end_time',
            'real_start_time',
            'real_carry_out',
            'status',
            'des',
            'path',
        ], $devCourseRows);
        
        $this->addLog('课程开发',sprintf('数据创建完成！本次需要插入 %d 条，新增 %d 条！以下课程已经存在：%s',count($courseIds), $data['num'], (count($hasCreateds)>0 ? implode(',', $hasCreateds) : '无')),$data['result']);
        
        if(!$data['result'])
        {
            throw new \Exception($data['msg']);
            return;
        }
        
        //=========================
        //更新数据
        //=========================
        $demandTaskIds = ArrayHelper::getColumn($this->courses, 'demand_task_id');
        //查询所有课程需求
        $courseTasks = (new Query())
                ->select(['id','demand_task_id'])
                ->from($tableName)
                ->where(['demand_task_id'=>$demandTaskIds])
                ->all();
        
        $courseTasks = ArrayHelper::map($courseTasks, 'demand_task_id', 'id');
        
        foreach ($this->courses as &$course){
            $course['course_task_id'] = $courseTasks[$course['demand_task_id']];
        }
    }
    
    /**
     * 创建团队工作课程制作人
     */
    private function createTwProducer()
    {
        $tableName = CourseProducer::tableName();
        $courseToDevUserMap = ArrayHelper::map($this->courses, 'course_task_id', 'dev_users');
        $courseTaskIds = array_unique(ArrayHelper::getColumn($this->courses, 'course_task_id'));
        $hasCreateds = [];
        //=========================
        //插入数据
        //=========================
        //查询已添加的
        $doneCourseTaskIds = (new Query())
                        ->select(['course_id as course_task_id'])
                        ->from($tableName)
                        ->where(['course_id'=>$courseTaskIds])
                        ->groupBy(['course_id'])
                        ->all();
        
        $doneCourseTaskMap = ArrayHelper::map($doneCourseTaskIds, 'course_task_id', 'course_task_id');
        
        //组装数据
        $rows = [];
        foreach ($courseToDevUserMap as $course_task_id => $dev_users) {
            if (!isset($doneCourseTaskMap[$course_task_id])) {
                foreach (array_unique(explode(',', $dev_users)) as $userMemeberId) {
                    $rows [] = [$course_task_id, $userMemeberId];
                }
            } else
                $hasCreateds [] = $course_task_id;
        }
        
        //插入新数据(过滤已存在数据)，并且返回插入的数据条数
        $data = $this->batchInsert($tableName, [], $rows);
        $hasCreateds = array_unique($hasCreateds);
        $this->addLog('添加开发人员',sprintf('数据关联完成！以下课程( %d 门 )开发人员已经存在：%s',count($hasCreateds), (count($hasCreateds)>0 ? implode(',', $hasCreateds) : '无')),$data['result']);
        
        if(!$data['result'])
        {
            throw new \Exception($data['msg']);
            return;
        }
    }
    /**
     * 创建阶段数据
     */
    private function createTwCoursePhase()
    {
        $tableName = CoursePhase::tableName();
        $courseTaskIds = array_unique(ArrayHelper::getColumn($this->courses, 'course_task_id'));
        $hasCreateds = [];
        //设置使用的模板
        $template_type_id = 1;
        //查询阶段模板数据
        $phase_templates = (new Query())
                ->select(['id','name','weights'])
                ->from(Phase::tableName())
                ->where(['template_type_id'=>$template_type_id])
                ->all();
        //=========================
        //插入数据
        //=========================
        //查询已添加的
        $doneCourseTaskIds = (new Query())
                        ->select(['course_id as course_task_id'])
                        ->from($tableName)
                        ->where(['course_id'=>$courseTaskIds])
                        ->groupBy(['course_id'])
                        ->all();
        
        $doneCourseTaskMap = ArrayHelper::map($doneCourseTaskIds, 'course_task_id', 'course_task_id');
        //组装数据
        $rows = [];
        $cur_time = time();
        foreach ($this->courses as $course){
            if(!isset($doneCourseTaskMap[$course['course_task_id']]))
            {
                foreach ($phase_templates as $phase){
                    $rows[] = [
                        null,$course['course_task_id'],$phase['name'],$phase['weights'],$course['create_by'],$cur_time,$cur_time,-1,'N'
                    ];
                }
            }else
                $hasCreateds [] = $course['course_task_id'];
        }
        
        //插入新数据(过滤已存在数据)，并且返回插入的数据条数
        $data = $this->batchInsert($tableName, [], $rows);
        $hasCreateds = array_unique($hasCreateds);
        $this->addLog('添加课程阶段',sprintf('数据关联完成！以下课程( %d 门 )的阶段已经存在：%s',count($hasCreateds), (count($hasCreateds)>0 ? implode(',', $hasCreateds) : '无')),$data['result']);
        
        if(!$data['result'])
        {
            throw new \Exception($data['msg']);
            return;
        }
    }
    
    /**
     * 创建环节数据
     */
    private function createTwCourseLink()
    {
        $tableName = CourseLink::tableName();
        $courseTaskIds = array_unique(ArrayHelper::getColumn($this->courses, 'course_task_id'));
        $hasCreateds = [];
        //设置使用的模板
        $template_type_id = 1;
        //查询阶段模板数据
        $link_templates = (new Query())
                ->select(['Link.name AS link_name','Link.type','Link.total','Link.completed','Link.unit','Link.index','Phase.name AS phase_name'])
                ->from(['Link' => Link::tableName()])
                ->leftJoin(['Phase' => Phase::tableName()], 'Phase.id = Link.phase_id')
                ->where(['Link.template_type_id'=>$template_type_id,'Link.is_delete'=>'N'])
                ->all();
         //=========================
        //插入数据
        //=========================
        //查询已添加的阶段
        $doneCoursePhases = (new Query())
                        ->select(['id','course_id as course_task_id','name'])
                        ->from(CoursePhase::tableName())
                        ->where(['course_id'=>$courseTaskIds])
                        ->all();
        //course_task_id_name = >id
        $doneCoursePhaseMap = ArrayHelper::map($doneCoursePhases, function($item){
            return $item['course_task_id'].'_'.$item['name'];
        }, 'id');
        
        //查询已添加的
        $doneCourseTaskIds = (new Query())
                        ->select(['course_id as course_task_id'])
                        ->from($tableName)
                        ->where(['course_id'=>$courseTaskIds])
                        ->all();
        
        $doneCourseTaskMap = ArrayHelper::map($doneCourseTaskIds, 'course_task_id', 'course_task_id');
        //组装数据
        $rows = [];
        $cur_time = time();
        foreach ($this->courses as $course){
            $course_task_id = $course['course_task_id'];
            if(!isset($doneCourseTaskMap[$course_task_id]))
            {
                foreach ($link_templates as $link){
                    $course_phase_id = $doneCoursePhaseMap[$course_task_id.'_'.$link['phase_name']];
                    $rows[] = [
                        null,$course_task_id,$course_phase_id,$link['link_name'],$link['type'],$link['total'],$link['total'],$link['unit'],$course['create_by'],$cur_time,$cur_time,-1,'N'
                    ];
                }
            }else
                $hasCreateds [] = $course_task_id;
        }
        
        //插入新数据(过滤已存在数据)，并且返回插入的数据条数
        $data = $this->batchInsert($tableName, [], $rows);
        $hasCreateds = array_unique($hasCreateds);
        $this->addLog('添加课程环节',sprintf('数据关联完成！以下课程( %d 门 )的环节已经存在：%s',count($hasCreateds), (count($hasCreateds)>0 ? implode(',', $hasCreateds) : '无')),$data['result']);
        
        if(!$data['result'])
        {
            throw new \Exception($data['msg']);
            return;
        }
    }
    
    /**
     * 插入数据
     * @param type $table       表名
     * @param type $columns     所要插入的列名
     * @param type $rows        数据
     * @return array            成功插入的条数 [num,result]
     */
    private function batchInsert($table, $columns, $rows){
         /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        $number = 0;
        $result = 1;
        $msg = '';
        try
        {  
            $number = Yii::$app->db->createCommand()->batchInsert($table, $columns, $rows)->execute();
            $trans->commit();  //提交事务
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            $number = -1;
            $result = 0;
            $msg = $ex->getMessage();
        }
        return ['num'=>$number,'result'=>$result,'msg'=>$msg];
    }
    
    /**
     * 添加日志记录
     * @param string $title         日志标题
     * @param string $data          数据
     * @param array $params         参数
     * @param int $result           结果 1成功，0失败
     */
    private function addLog($title,$data,$result=1){
        $this->logs[] = ['result'=>$result,'title'=>$title,'data'=>$data];
    }
}
