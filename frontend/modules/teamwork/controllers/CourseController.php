<?php

namespace frontend\modules\teamwork\controllers;

use common\models\expert\Expert;
use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\teamwork\CourseAnnex;
use common\models\teamwork\CourseManage;
use common\models\teamwork\CourseProducer;
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
    public function actionIndex($project_id = null, $status = null, $team_id = null, $item_type_id = null,
            $item_id =null, $item_child_id = null, $course_id = null, $keyword = null, $time = null, $mark = null)
    {
                       
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $twTool->getCourseProgressAll($project_id, $status, $team_id, $item_type_id, $item_id, $item_child_id, $course_id, $keyword, $time),
        ]);
        $courseId = ArrayHelper::getColumn($dataProvider->allModels, 'id');
        $week = $twTool->getWeek(date('Y-m-d', time()));
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'weeklyInfo' => $twTool->getWeeklyInfo($courseId, $week['start'], $week['end']),
            'itemType' => $this->getItemType(),
            'items' => $this->getCollegesForSelect(),
            'itemChild' => [],
            'course' => [],
            'team' => $this->getTeam(),
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
        $allModels = $this->findItemModel($project_id);
        foreach ($allModels as $value)
            $model = $this->findModel($value->id);
        
        return $this->render('list', [
            'allModels' => $allModels,
            'twTool' => $twTool,
            'model' => empty($allModels) ? new CourseManage() : $model,
            'lessionTime' => $twTool->getCourseLessionTimesSum(['project_id' => $project_id]),
            'project_id' => $project_id,
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
        $model = $twTool->getCourseProgressOne($id);
        $week = $twTool->getWeek(date('Y-m-d', time()));
        
        return $this->render('view', [
            'model' => $model,
            'twTool' => $twTool,
            'team' => $this->getTeam(),
            'coursePrincipal' => $this->getTeamMemberList(),
            'producer' => $this->getAssignProducers($id),
            'weeklyMonth' => $this->getWeeklyMonth($model), //周报月份列表
            'weeklyInfoResult' => $twTool->getWeeklyInfo($id, $week['start'], $week['end']),
            'annex' => $this->getCourseAnnex($model->id),
        ]);
    }

    /**
     * Creates a new CourseManage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($project_id)
    {
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $post = Yii::$app->request->post();
        if(!($twTool->getIsAuthority('is_leader', 'Y') || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
            throw new NotAcceptableHttpException('无权限操作！');
        $course_id = ArrayHelper::getValue($post, 'CourseManage.course_id');
        
        /* @var $model CourseManage */
        $model = new CourseManage();
        $model->loadDefaultValues();
        $model->project_id = $project_id;
        //$model->team_id = $twTool->getHotelTeam(\Yii::$app->user->id);
        $model->create_by = \Yii::$app->user->id;
       
        if ($model->load($post) && $model->validate()) {
            /*if($this->getIsSameValue($project_id, $course_id))
                throw new NotAcceptableHttpException('请勿重复提交相同的数据！');   */
            $twTool->CreateTask($model, $post);         //创建任务操作
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'twTool' => $twTool,
                'courses' => $this->getCourses($project_id, $model->project->item_child_id),
                'teachers' => $this->getExpert(),
                'team' => $twTool->getHotelTeam(\Yii::$app->user->id),
                'producerList' => $this->getTeamMemberList(),
                'weeklyEditors' => $this->getSameTeamMember(\Yii::$app->user->id),
                'producer' => $this->getSameTeamMember(\Yii::$app->user->id),
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
        if(!(($twTool->getIsAuthority('is_leader', 'Y') && $model->create_by == \Yii::$app->user->id)
            || $twTool->getIsAuthority('id', $model->course_principal)
            || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
            throw new NotAcceptableHttpException('无权限操作！');
        if($model->getIsCarryOut())
            throw new NotAcceptableHttpException('该课程'.$model->getStatusName().'！');
        
        $post = Yii::$app->request->post();
        $courses = $this->getCourses($model->project_id, $model->project->item_child_id);
        
        if ($model->load($post) && $model->validate()) {
            $twTool->UpdateTask($model, $post);         //更新任务操作
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'twTool' => $twTool,
                'courses' => ArrayHelper::merge([$model->course_id => $model->course->name], $courses),
                'teachers' => $this->getExpert(),
                'team' => $twTool->getHotelTeam(\Yii::$app->user->id),
                'weeklyEditors' => $this->getAssignWeeklyEditors($model->id),
                'producerList' => $this->getTeamMemberList(),
                'producer' => $this->getAssignProducers($model->id),
                'annex' => $this->getCourseAnnex($model->id),
            ]);
        }
        
    }
    
    /**
     * 更改团队/课程负责人
     * @param type $id
     * @return type
     * @throws NotFoundHttpException
     */
    public function actionChange($id) {
        $model = $this->findModel($id);
        $model->scenario = CourseManage::SCENARIO_CHANGE;
        if ($model->getIsCarryOut() && !Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)) 
            throw new NotFoundHttpException('无权限操作！');
        if($model->load(Yii::$app->request->post()) && $model->save())
            return $this->redirect(['view', 'id' => $model->id]);
        else 
            throw new NotFoundHttpException('操作失败！');
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
        $model->real_carry_out = '';
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
        $model = $twTool->getCourseProgressOne($id);
        $model->scenario = CourseManage::SCENARIO_CARRYOUT;
        if(!(($twTool->getIsAuthority('is_leader', 'Y') && $model->create_by == \Yii::$app->user->id)
            || $twTool->getIsAuthority('id', $model->course_principal)
            || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
            throw new NotFoundHttpException('无权限操作！');
        
        if($model != null && $model->progress != 1)
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
        if (($model = CourseManage::findAll(['project_id' => $project_id])) !== null) {
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
        $itemType = ItemType::find()->with('itemManages')->all();
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
     * 获取课程
     * @param type $model
     * @return type
     */
    public function getCourses($projectId, $itemChildId)
    {
        $existedCourses = CourseManage::find()->where(['project_id' => $projectId])
                ->with('project')->with('course')->all();
        $courses = Item::find()
                ->where(['parent_id' => $itemChildId])
                ->andFilterWhere(['NOT IN', 'id', ArrayHelper::getColumn($existedCourses, 'course_id')])
                ->with('projects')
                ->all();
        return ArrayHelper::map($courses, 'id', 'name');
    }
    
    /**
     * 获取专家库
     * @return type
     */
    public function getExpert(){
        $expert = Expert::find()->with('user')
                ->all();
        return ArrayHelper::map($expert, 'u_id','user.nickname');
    }
    
    /**
     * 获取所有团队
     * @return type
     */
    public function getTeam()
    {
        /* @var $team Team */
        $team = Team::find()
                ->with('teamMembers')
                ->with('us')
                ->with('courseManages')
                ->with('itemManages')
                ->all();
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
                    ->orderBy(['index' => 'asc', 'team_id' => 'asc'])
                    ->with('user')
                    ->with('team')
                    ->with('position')
                    ->all();
       
        $producers = [];
        foreach ($teamMember as $element) {
            $key = ArrayHelper::getValue($element, 'id');
            $value = ArrayHelper::getValue($element, 'user.nickname').' ('.ArrayHelper::getValue($element, 'position.name').')';
            $producers[ArrayHelper::getValue($element, 'team.name')][$key] = $value;
        }
        
        return $producers;
    }
    
    /**
     * 获取当前用户下的所有团队成员
     * @param type $u_id    用户ID
     * @return type
     */
    public function getSameTeamMember($u_id)
    {
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $sameTeamMembers = TeamMember::find()
                        ->where(['team_id' => $twTool->getHotelTeam($u_id)])
                        ->orderBy('index asc')
                        ->with('user')
                        ->with('team')
                        ->with('position')
                        ->all();
        $sameTeamMember = [];
        foreach ($sameTeamMembers as $element) {
            $key = ArrayHelper::getValue($element, 'id');
            $value = ArrayHelper::getValue($element, 'user.nickname').' ('.ArrayHelper::getValue($element, 'position.name').')';
            $sameTeamMember[$key] = $value;
        }
        return $sameTeamMember;
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
     * 获取周报编辑人
     * @param type $courseId
     */
    public function getAssignWeeklyEditors($courseId)
    {
        $assignWeeklyEditors = CourseProducer::find()
                            ->select(['Weeklyeditors.*','Member.`index`'])
                            ->from(['Weeklyeditors' => CourseProducer::tableName()])
                            ->leftJoin(['Member' => TeamMember::tableName()], 'Member.u_id = Weeklyeditors.producer')
                            ->where(['Weeklyeditors.course_id' => $courseId])
                            ->orderBy(['Member.`index`' => 'ASC', 'Member.team_id' => 'ASC'])
                            ->with('producerOne')
                            ->with('course')
                            ->with('producerOne.user')
                            ->all();
        $weeklyEditors = [];
        foreach ($assignWeeklyEditors as $element) {
            $key = ArrayHelper::getValue($element, 'producer');
            $value = ArrayHelper::getValue($element, 'producerOne.user.nickname').' ('.ArrayHelper::getValue($element, 'producerOne.position.name').')';
            $weeklyEditors[$key] = $value;
        }
        return $weeklyEditors;
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
                           ->orderBy('Member.`index` asc, Member.team_id asc')
                           ->with('producerOne')
                           ->with('producerOne.user')
                           ->with('producerOne.position')
                           ->with('course')
                           ->all();
        $producers = [];
        foreach ($assignProducers as $element) {
            $key = ArrayHelper::getValue($element, 'producer');
            $value = ArrayHelper::getValue($element, 'producerOne.is_leader') == 'Y' ? 
                    '<span class="team-leader developer">'.
                        ArrayHelper::getValue($element, 'producerOne.user.nickname').
                        '('.ArrayHelper::getValue($element, 'producerOne.position.name').')'.
                     '</span>' : 
                    '<span class="developer">'.
                        ArrayHelper::getValue($element, 'producerOne.user.nickname').
                        '('.ArrayHelper::getValue($element, 'producerOne.position.name').')'. 
                   '</span>';
            $producers[$key] = $value;
        }
        return $producers;
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
