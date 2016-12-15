<?php

namespace frontend\modules\teamwork\controllers;

use common\models\expert\Expert;
use common\models\Position;
use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\teamwork\CourseAnnex;
use common\models\teamwork\CourseManage;
use common\models\teamwork\CourseProducer;
use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\utils\TeamworkTool;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use wskeee\rbac\RbacName;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * CourseController implements the CRUD actions for CourseManage model.
 */
class CourseController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
             //access验证是否有登录
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    /**
     * Index all CourseManage models.
     * @return mixed
     */
    public function actionIndex($status = null, $team_id = null, $item_type_id = null,
            $item_id =null, $item_child_id = null, $course_id = null, $keyword = null, $time = null, $mark = null, $page = null)
    {
        $page = $page == null ? 0 : $page-1;        
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $query = $twTool->getCourseInfo($id = null, $status, $team_id, $item_type_id, $item_id, $item_child_id, $course_id, $keyword, $time);
        $count = $query->count();
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->addSelect([
                'Tw_course.demand_task_id', 'Demand_task.course_id', 
                'Tw_course.status', 'Tw_course.team_id', 'Demand_task.mode',
                'Demand_task.item_type_id', 'Demand_task.item_id', 'Demand_task.item_child_id',
                'Team.`name` AS team_name', 'Fw_item_type.`name` AS item_type_name',
                'Fw_item.`name` AS item_name','Fw_item_child.`name` AS item_child_name',
                'Fw_item_course.`name` AS item_course_name'
            ])->limit(20)->offset($page*20)->all(),
        ]);
       
        return $this->render('index', [
            'twTool' => $twTool,
            'dataProvider' => $dataProvider,
            'itemType' => $this->getItemType(),
            'items' => $this->getCollegesForSelect(),
            'itemChild' => empty($mark) ? [] : $this->getChildren($item_id),
            'course' => empty($mark) ? [] : $this->getChildren($item_child_id),
            'team' => $this->getTeam(),
            'count' => $count,
            //搜索默认字段值
            'itemTypeId' => $item_type_id,
            'itemId' => $item_id,
            'itemChildId' => $item_child_id,
            'courseId' => $course_id,
            'keyword' => $keyword,
            'status' => $status,
            'team_id' => $team_id,
            'time' => !empty($time) ? $time : null,
            'mark' => !empty($mark) ? $mark : 0,
        ]);
    }
    
    /**
     * Lists all CourseManage models.
     * @return mixed
     */
    public function actionList($project_id)
    {
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $model = $this->findItemModel($project_id);
       
        return $this->render('list', [
            'model' => $model,
            'twTool' => $twTool,
            'lessionTime' => $twTool->getCourseLessionTimesSum(['project_id' => $project_id]),
        ]);
    }

    /**
     * Displays a single CourseManage model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        /* @var $model CourseManage */
        $model = $twTool->getCourseInfo($id)->addSelect(['Tw_course.*'])->one();
        $weekly = $twTool->getWeeklyInfo($id, $twTool->getWeek(date('Y-m-d', time())));
        
        return $this->render('view', [
            'model' => $model,
            'twTool' => $twTool,
            'producers' => $this->getAssignProducers($model->id),
            'weeklyMonth' => $this->getWeeklyMonth($model), //周报月份列表
            'weeklyInfoResult' => !empty($weekly) ? true : false,
            'annex' => $this->getCourseAnnex($model->id),
        ]);
    }

    /**
     * Creates a new CourseManage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($demand_task_id)
    {
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $post = Yii::$app->request->post();
        if(!($twTool->getIsAuthority('is_leader', 'Y') || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
            throw new NotAcceptableHttpException('无权限操作！');
        
        /* @var $model CourseManage */
        $model = new CourseManage();
        $model->loadDefaultValues();
        $model->demand_task_id = $demand_task_id;
        $model->create_by = \Yii::$app->user->id;
       
        if ($model->load($post) && $model->validate()) {
            $twTool->CreateTask($model, $post);         //创建任务操作
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'twTool' => $twTool,
                'team' => $twTool->getHotelTeam(),
                'producerList' => $this->getTeamMemberList(),
                'weeklyEditors' => $this->getSameTeamMember(),
                'producer' => $this->getSameTeamMember(),
            ]);
        }
    }

    /**
     * Updates an existing CourseManage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $post = Yii::$app->request->post();
        if(!(($twTool->getIsAuthority('is_leader', 'Y') && $model->create_by == \Yii::$app->user->id)
            || $twTool->getIsAuthority('id', $model->course_principal)
            || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
            throw new NotAcceptableHttpException('无权限操作！');
        if($model->getIsCarryOut())
            throw new NotAcceptableHttpException('该课程'.$model->getStatusName().'！');
        
        if ($model->load($post) && $model->validate()) {
            $twTool->UpdateTask($model, $post);         //更新任务操作
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'twTool' => $twTool,
                'team' => $twTool->getHotelTeam(),
                'weeklyEditors' => ArrayHelper::map($this->getAssignProducers($model->id), 'producer', 'producerOne.user.nickname'),
                'producerList' => $this->getTeamMemberList(),
                'producer' => ArrayHelper::map($this->getAssignProducers($model->id), 'producer', 'producerOne.user.nickname'),
                'annex' => $this->getCourseAnnex($model->id),
            ]);
        }
        
    }
    
    /**
     * 更改团队/课程负责人
     * @param type $id
     * @return type
     */
    public function actionChange($id) {
        $model = $this->findModel($id);
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $model->scenario = CourseManage::SCENARIO_CHANGE;
        if ($model->getIsCarryOut() && !Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)) 
            throw new NotFoundHttpException('无权限操作！');
        
        if($model->load(Yii::$app->request->post())){
            $twTool->ChangeTask($model);
            return $this->redirect(['view', 'id' => $model->id]);
        }else{
            return $this->renderPartial('change', [
                'model' => $model,
                'team' => $this->getTeam(),
                'coursePrincipal' => $this->getTeamMemberList(),
            ]);
        }
    }

    /**
     * 更改状态为【待开始】
     * Normal an existing ItemManage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionWaitStart($id)
    {
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $model = $this->findModel($id);
        if(!(($twTool->getIsAuthority('is_leader', 'Y') && $model->create_by == \Yii::$app->user->id)
            || $twTool->getIsAuthority('id', $model->course_principal)
            || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
            throw new NotFoundHttpException('无权限操作！');
        if($model != null && !$model->getIsWaitStart())
            throw new NotFoundHttpException('该课程'.$model->getStatusName().'！');
        $model->scenario = CourseManage::SCENARIO_WAITSTART;
        $model->real_start_time = date('Y-m-d H:i', time());
        $model->status = CourseManage::STATUS_NORMAL;
        $model->save();
        $this->redirect(['view', 'id' => $model->id]);
    }
    
    /**
     * 更改状态为【在建中】
     * Normal an existing ItemManage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionNormal($id)
    {
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        if (!Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)) 
            throw new NotFoundHttpException('无权限操作！');
        
        $model = $this->findModel($id);
        if($model != null && !$model->getIsCarryOut())
            throw new NotFoundHttpException('该课程'.$model->getStatusName().'！');
        $model->real_carry_out = null;
        $model->status = CourseManage::STATUS_NORMAL;
        $model->save();
        $this->redirect(['view', 'id' => $model->id]);
    }
    
    /**
     * 更改状态为【完成】
     * CarryOut an existing ItemManage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCarryOut($id)
    {
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        /* @var $model CourseManage */
        $model = $this->findModel($id);
        CourseManage::$progress = ArrayHelper::map($twTool->getCourseProgress($model->id)->all(), 'id', 'progress');
        $model->scenario = CourseManage::SCENARIO_CARRYOUT;
        if(!(($twTool->getIsAuthority('is_leader', 'Y') && $model->create_by == \Yii::$app->user->id)
            || $twTool->getIsAuthority('id', $model->course_principal)
            || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
            throw new NotFoundHttpException('无权限操作！');
        
        if($model != null && CourseManage::$progress[$model->id] != 100)
            throw new NotFoundHttpException('当前进度必须为100%！');
        
        if($model->getIsCarryOut())
            throw new NotFoundHttpException('该课程'.$model->getStatusName().'！');
        
        $model->real_carry_out = date('Y-m-d H:i', time());
        $model->status = CourseManage::STATUS_CARRY_OUT;
        if ($model->validate() && $model->save()){
            $this->redirect(['view', 'id' => $model->id]);
        } else {
            $errors = [];
            foreach($model->getErrors() as $error){
                foreach($error as $name=>$value){
                    $errors[] = $value;
                }
            }
            throw new NotFoundHttpException (implode($errors));
        }
    }
    
    /**
     * Deletes an existing CourseManage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $project_id)
    {
        $model = $this->findModel(['id' => $id, 'project_id' => $project_id]);
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        
        if(!$model->getIsCarryOut() && (($twTool->getIsAuthority('is_leader', 'Y') && $model->create_by == \Yii::$app->user->id)
            || $twTool->getIsAuthority('id', $model->course_principal)|| Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
            $model->delete();
        else 
            throw new NotFoundHttpException('无权限操作！');
        
        $this->redirect(['list','project_id' => $project_id]);
    }

    /**
     * Finds the CourseManage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CourseManage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CourseManage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 该项目下的所有课程
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $project_id
     * @return CourseManage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findItemModel($project_id)
    {
        if (($model = ItemManage::findOne(['id' => $project_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 获取行业
     * @return type
     */
    public function getItemType()
    {
        $itemType = ItemType::find()->all();
        return ArrayHelper::map($itemType, 'id', 'name');
    }
    
    /**
     * 获取层次/类型
     * @return type
     */
    public function getCollegesForSelect()
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getColleges(), 'id', 'name');
    }
    
    /**
     * 获取专业/工种 or 课程
     * @param type $itemId
     * @return type
     */
    protected function getChildren($itemId)
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getChildren($itemId), 'id', 'name');
    }
    
    /**
     * 获取所有团队
     * @return Team $team
     */
    public function getTeam()
    {
        /* @var $team Team */
        $team = Team::find()->orderBy('index asc')->all();
        
        return ArrayHelper::map($team, 'id', 'name');
    }

    /**
     * 获取团队成员
     * @return type
     */
    public function getTeamMemberList()
    {
        /* @var $teamMember TeamMember */
        $teamMember = TeamMember::find()
                    ->from(['Member' => TeamMember::tableName()])
                    ->leftJoin(['Team' => Team::tableName()], 'Team.id = Member.team_id')
                    ->leftJoin(['Position' => Position::tableName()], 'Position.id = Member.position_id')
                    ->where(['!=', 'Member.is_delete', TeamMember::SURE_DELETE])
                    ->orderBy('Team.index asc, Position.level asc')
                    ->with('user')
                    ->with('team')
                    ->all();
        
        return ArrayHelper::map($teamMember, 'id', 'user.nickname', 'team.name');
    }
    
    /**
     * 获取当前用户下的所有团队成员
     * @param type $u_id    用户ID
     * @return type
     */
    public function getSameTeamMember()
    {
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $sameTeamMembers = TeamMember::find()
                        ->leftJoin(['Position' => Position::tableName()], 'Position.id = position_id')
                        ->where(['team_id' => $twTool->getHotelTeam()])
                        ->andWhere(['!=', 'is_delete', TeamMember::SURE_DELETE])
                        ->orderBy('Position.level asc')
                        ->with('user')
                        //->with('position')
                        ->all();
        
        return ArrayHelper::map($sameTeamMembers, 'id', 'user.nickname');
    }

    /**
     * 获取已分配的制作人
     * @param type $courseId   课程ID
     * @return type
     */
    public function getAssignProducers($courseId){
        
        $assignProducers = CourseProducer::find()
                           ->select(['Producer.*', 'Member.`index`'])
                           ->from(['Producer' => CourseProducer::tableName()])
                           ->leftJoin(['Member' => TeamMember::tableName()], 'Member.id = Producer.producer')
                           ->where(['Producer.course_id' => $courseId])
                           ->orderBy('Member.`index` asc, Member.is_leader desc')
                           ->with('producerOne')
                           ->with('producerOne.user')
                           ->all();
        
        return $assignProducers;
    }
    
    /**
     * 获取课程附件
     * @param type $course_id
     * @return type
     */
    public function getCourseAnnex($course_id)
    {
        $annex = CourseAnnex::find()
                ->where(['course_id' => $course_id])
                ->with('course')
                ->all();
        return $annex;
    }
    
    /**
     * 计算课程开发周报月份
     * @param type $model
     * @return array
     */
    public function getWeeklyMonth($model)
    {
        /* @var $model  CourseManage*/
        $monthStart = empty($model->real_start_time) ? strtotime(date('Y-m', time())) : 
                     strtotime(date('Y-m', strtotime($model->real_start_time)));       //课程实际开始时间
        $monthEnd = empty($model->real_carry_out) ? strtotime(date('Y-m', time())) :
                    strtotime(date('Y-m', strtotime($model->real_carry_out)));      //课程实际完成时间
        
        $monthArray = [];
        $monthArray[] = empty($model->real_start_time) ? date('Y-m', time()) : 
                        date('Y-m', strtotime($model->real_start_time)); // 当前月;
        while(($monthStart = strtotime('+1 month', $monthStart)) <= $monthEnd){
            $monthArray[] = date('Y-m',$monthStart); // 取得递增月;  
        }
        $weeklyMonth = [];
        foreach ($monthArray as $key => $value) {
            $key = $value;
            $weeklyMonth[$key] = $value;
        }
        
        return $weeklyMonth;
    }
    
    /**
     * 判断是否重复提交数据
     * @param type $project_id      项目ID
     * @param type $course_id       课程ID
     * @return boolean
     
    public function getIsSameValue($project_id, $course_id)
    {
        $courses = CourseManage::findAll(['project_id' => $project_id]);
        $course = [];
        foreach ($courses as $value) {
            $course[] = $value->course_id;
        }
       
        if(in_array($course_id, $course))
            return true;
        else 
            return false;
    }*/


    /**
     * 重组 $model->statusName 数组
     * @param type $model
     * @return type
     */
    /*public function AgainStatusName($model){
        $statusName = [];
        /* @var $model CourseManage 
        foreach ($model->project->statusName as $key => $value) {
            $statusName[] = $model->project->statusName[$model->status] == $value ? 
                    '<span style="color:red">'.$value.'</span>' : $value;
        }
        $array_pop = array_pop($statusName);
        unset($array_pop);
        return $statusName;
    }*/
    
}
