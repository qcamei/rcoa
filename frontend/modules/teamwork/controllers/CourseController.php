<?php

namespace frontend\modules\teamwork\controllers;

use common\models\expert\Expert;
use common\models\team\TeamMember;
use common\models\teamwork\CourseAnnex;
use common\models\teamwork\CourseManage;
use common\models\teamwork\CourseProducer;
use common\models\teamwork\CourseSummary;
use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\TeamworkTool;
use wskeee\framework\models\Item;
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
    public function actionIndex($project_id = null, $status = null, $team_id = null)
    {
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $dataProvider = new ArrayDataProvider([
            'allModels' => $twTool->getCourseProgressAll($project_id, $status, $team_id),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all CourseManage models.
     * @return mixed
     */
    public function actionList($project_id)
    {
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
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
        $twTool = Yii::$app->get('twTool');
        /* @var $model CourseManage */
        $model = $twTool->getCourseProgressOne($id);
        $post = Yii::$app->request->post();
        $producer = $this->getAssignProducers($id);
        $create_time = $this->getSummaryCreateTime(['course_id' => $id]);
        $result = empty($post) ? $twTool->getWeek($id, date('Y-m-d', time())) : 
                    $twTool->getWeek($id, $post['create_time']);
                    
        return $this->render('view', [
            'model' => $model,
            'twTool' => $twTool,
            'producer' => $producer,
            'create_time' => $create_time,
            'create_time_key' => empty($post) ? null : array_keys($create_time),
            'createTime' => empty($result) ? null : $result->create_time,
            'createdAt' => empty($result)? '无' : date('Y-m-d H:i', $result->created_at),
            'content' => empty($result)? '无' :$result->content,
            'annex' => $this->getCourseAnnex($model->id),
        ]);
    }

    /**
     * Creates a new CourseManage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        if(!$twTool->getIsLeader())
            throw new NotAcceptableHttpException('无权限操作！');
        
        $params = Yii::$app->request->queryParams;
        $post = Yii::$app->request->post();
        $model = new CourseManage();
        $model->loadDefaultValues();
        $model->project_id = $params['project_id'];
        $model->team_id = $twTool->getHotelTeam(\Yii::$app->user->id);
        $model->create_by = \Yii::$app->user->id;
        $courses = $this->getCourses($model->project->item_child_id);
        $existedCourses = $this->getExistedCourses($model->project_id);
        $model->scenario = CourseManage::SCENARIO_DEFAULT;
        if ($model->load($post)) {
            $model->video_length = strtotime($post['CourseManage']['video_length']);
            $twTool->CreateTask($model, $post);         //创建任务操作
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'twTool' => $twTool,
                'courses' => array_diff($courses, $existedCourses),
                'teachers' => $this->getExpert(),
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
        $twTool = Yii::$app->get('twTool');
        if(!$twTool->getIsLeader() || $model->create_by != \Yii::$app->user->id)
           throw new NotAcceptableHttpException('无权限操作！');
        
        $post = Yii::$app->request->post();
        $courses = $this->getCourses($model->project->item_child_id);
        $existedCourses = $this->getExistedCourses(['project_id' => $model->project_id]);
        $existedCoursesOne = $this->getExistedCourses(['id' => $id]);    //获取已经存在的单条课程
        if(!$model->getIsNormal())
            throw new NotAcceptableHttpException('该课程'.$model->getStatusName().'！');
        
        if ($model->load($post) && $model->validate()) {
            $model->video_length = $post['CourseManage']['video_length'];
            $twTool->UpdateTask($model, $post);         //更新任务操作
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'twTool' => $twTool,
                'courses' => ArrayHelper::merge($existedCoursesOne, array_diff($courses, $existedCourses)),
                'teachers' => $this->getExpert(),
                'weeklyEditors' => $this->getAssignWeeklyEditors($model->id),
                'producerList' => $this->getTeamMemberList(),
                'producer' => $this->getAssignProducers($model->id),
                'annex' => $this->getCourseAnnex($model->id),
            ]);
        }
        
    }
    
    /**
     * 更改状态为【在建】
     * Normal an existing ItemManage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionNormal($id)
    {
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        if (!Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)) 
            throw new NotFoundHttpException('无权限操作！');
        
         $model = $this->findModel($id);
        if($model != null && !$model->getIsCarryOut())
            throw new NotFoundHttpException('该课程'.$model->getStatusName().'！');
        
        $model->status = ItemManage::STATUS_NORMAL;
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
        $twTool = Yii::$app->get('twTool');
        /* @var $model CourseManage */
        $model = $twTool->getCourseProgressOne($id);
        $model->scenario = CourseManage::SCENARIO_CARRYOUT;
        if(!$twTool->getIsLeader() || $model->create_by != \Yii::$app->user->id)
            throw new NotFoundHttpException('无权限操作！');
        
        if($model != null && $model->progress != 1)
            throw new NotFoundHttpException('当前进度必须为100%！');
        
        if(!$model->getIsNormal())
            throw new NotFoundHttpException('该课程'.$model->getStatusName().'！');
        
        $model->real_carry_out = date('Y-m-d H:i', time());
        $model->status = ItemManage::STATUS_CARRY_OUT;
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
        $twTool = Yii::$app->get('twTool');
        if($model->getIsNormal() && $twTool->getIsLeader() && $model->create_by == \Yii::$app->user->id)
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
     * 获取课程
     * @param type $model
     * @return type
     */
    public function getCourses($model)
    {
        $courses = Item::findAll(['parent_id' => $model]);
        return ArrayHelper::map($courses, 'id', 'name');
    }
    
    /**
     * 获取已存在的所有课程
     * @param type $condition
     * @return type
     */
    public function getExistedCourses($condition)
    {
        $courses = CourseManage::find()->where($condition)
                ->with('project')->with('course')->all();
        return ArrayHelper::map($courses, 'course_id', 'course.name');
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
     * 获取团队成员
     * @return type
     */
    public function getTeamMemberList()
    {
        /* @var $teamMember TeamMember */
        $teamMember = TeamMember::find()
                    ->orderBy(['index' => 'asc', 'team_id' => 'asc'])
                    ->with('u')
                    ->with('team')
                    ->all();
       
        $producers = [];
        foreach ($teamMember as $element) {
            $key = ArrayHelper::getValue($element, 'u_id');
            $value = ArrayHelper::getValue($element, 'u.nickname').' ('.ArrayHelper::getValue($element, 'position').')';
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
        $teamMember = TeamMember::find()->where(['u_id' => $u_id])->one();
        $sameTeamMembers = TeamMember::find()
                        ->where(['team_id' => $teamMember->team_id])
                        ->orderBy('index asc')
                        ->with('u')
                        ->with('team')
                        ->all();
        $sameTeamMember = [];
        foreach ($sameTeamMembers as $element) {
            $key = ArrayHelper::getValue($element, 'u_id');
            $value = ArrayHelper::getValue($element, 'u.nickname').' ('.ArrayHelper::getValue($element, 'position').')';
            $sameTeamMember[$key] = $value;
        }
        return $sameTeamMember;
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
                            ->all();
        $weeklyEditors = [];
        foreach ($assignWeeklyEditors as $element) {
            $key = ArrayHelper::getValue($element, 'producer');
            $value = ArrayHelper::getValue($element, 'producerOne.u.nickname').' ('.ArrayHelper::getValue($element, 'producerOne.position').')';
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
                           ->select(['Producer.*','Member.`index`'])
                           ->from(['Producer' => CourseProducer::tableName()])
                           ->leftJoin(['Member' => TeamMember::tableName()], 'Member.u_id = Producer.producer')
                           ->where(['Producer.course_id' => $courseId])
                           ->orderBy(['Member.`index`' => 'ASC', 'Member.team_id' => 'ASC'])
                           ->with('producerOne')
                           ->with('course')
                           ->all();
        $producers = [];
        foreach ($assignProducers as $element) {
            $key = ArrayHelper::getValue($element, 'producer');
            $value = ArrayHelper::getValue($element, 'producerOne.is_leader') == 'Y' ? 
                    '<span class="team-leader developer">'.
                        ArrayHelper::getValue($element, 'producerOne.u.nickname').
                        '('.ArrayHelper::getValue($element, 'producerOne.position').')'.
                     '</span>' : 
                    '<span class="developer">'.
                        ArrayHelper::getValue($element, 'producerOne.u.nickname').
                        '('.ArrayHelper::getValue($element, 'producerOne.position').')'. 
                   '</span>';
            //$producers[ArrayHelper::getValue($element, 'producerOne.team.name')][$key] = $value;
            $producers[$key] = $value;
        }
        return $producers;
    }
    
    /**
     * 获取总结创建时间
     * @param type $condition   条件
     * @return type
     */
    public function getSummaryCreateTime($condition)
    {
        $createTime = CourseSummary::find()
                      ->where($condition)
                      ->orderBy('create_time asc')
                      ->all();
        return ArrayHelper::map($createTime, 'create_time', 'create_time');
    }
    
    
    public function getCourseAnnex($course_id)
    {
        $annex = CourseAnnex::find()
                ->where(['course_id' => $course_id])
                ->with('course')
                ->all();
        return $annex;
    }

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
