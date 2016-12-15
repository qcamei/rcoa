<?php

namespace frontend\modules\demand\controllers;

use common\config\AppGlobalVariables;
use common\models\demand\DemandTask;
use common\models\demand\DemandTaskAnnex;
use common\models\expert\Expert;
use common\models\teamwork\CourseManage;
use common\wskeee\job\JobManager;
use frontend\modules\demand\utils\DemandTool;
use frontend\modules\teamwork\utils\TeamworkTool;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use wskeee\rbac\RbacName;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * TaskController implements the CRUD actions for DemandTask model.
 */
class TaskController extends Controller
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
     * Lists all DemandTask models.
     * @return mixed
     */
    public function actionIndex($status = 1, $page = null)
    {
        $page = $page == null ? 0 : $page-1; 
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        /* @var $dtTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $query = $dtTool->getDemandTaskInfo($id = null, $status);
        $count = $query->count();
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->addSelect([
                'Demand_task.*'
            ])->limit(20)->offset($page*20)->all(),
        ]);
        $taskIds = ArrayHelper::getColumn($dataProvider->allModels, 'id');
        $taskStatus = ArrayHelper::map($dataProvider->allModels, 'id', 'status');
        
        return $this->render('index', [
            'twTool' => $twTool,
            'dataProvider' => $dataProvider,
            'progress' => $this->getTwCourseProgress($taskIds),
            'operation' => $dtTool->getIsBelongToOwnOperate($taskIds, $taskStatus),
            'count' => $count,
        ]);
    }

    /**
     * Displays a single DemandTask model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->layout = '@app/views/layouts/main';
        $model = $this->findModel($id);
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        /* @var $dtTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        if($model->getIsStatusCompleted() || $model->getIsStatusCancel() ){
            //取消用户与任务通知的关联
            $jobManager->cancelNotification(AppGlobalVariables::getSystemId(), $model->id, \Yii::$app->user->id); 
        }else {
            //设置用户对通知已读
            $jobManager->setNotificationHasReady(AppGlobalVariables::getSystemId(), \Yii::$app->user->id, $model->id);  
        }
        
        return $this->render('view', [
            'model' => $model,
            'dtTool' => $dtTool,
            'twTool' => $twTool,
            'progress' => $this->getTwCourseProgress($model->id),
            'annex' => $this->getAnnex($id),
        ]);
    }

    /**
     * Creates a new DemandTask model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->layout = '@app/views/layouts/main';
        if(!\Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_CREATE))
            throw new NotAcceptableHttpException('无权限操作！');
        $model = new DemandTask();
        $model->loadDefaultValues();
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        /* @var $dtTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $post = Yii::$app->request->post();
        $model->create_by = \Yii::$app->user->id;

        if ($model->load($post)) {
            $dtTool->CreateTask($model, $post);
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'itemTypes' => $this->getItemType(),
                'items' => $this->getCollegesForSelect(),
                'itemChilds' => [],
                'courses' => [],
                'teachers' => $this->getExpert(),
                'team' => $twTool->getHotelTeam(),
            ]);
        }
    }

    /**
     * Updates an existing DemandTask model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->layout = '@app/views/layouts/main';
        if(!\Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_UPDATE))
            throw new NotAcceptableHttpException('无权限操作！');
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if(!$model->getIsStatusAdjusimenting())
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        /* @var $dtTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $courses = $this->getCourses($model->item_child_id);
        
        if ($model->load($post)) {
            $dtTool->UpdateTask($model, $post);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'itemTypes' => $this->getItemType(),
                'items' => $this->getCollegesForSelect(),
                'itemChilds' => $this->getChildren($model->item_id),
                'courses' => ArrayHelper::merge([$model->course_id => $model->course->name], $courses),
                'teachers' => $this->getExpert(),
                'team' => $twTool->getHotelTeam(),
                'annex' => $this->getAnnex($model->id),
            ]);
        }
    }

    /**
     * 通过审核操作
     * @param integer $id
     * @return type
     * @throws NotAcceptableHttpException
     */
    public function actionPassCheck($id)
    {
        $model = $this->findModel($id);
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        if(!$dtTool->getIsAuditor($model->create_team))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!($model->getIsStatusCheck() || $model->getIsStatusChecking()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        
        $model->status = DemandTask::STATUS_UNDERTAKE;
        $model->progress = $model->getStatusProgress();
        $dtTool->PassCheckTask($model);
        return $this->redirect(['index']);
    }
    
    /**
     * 通过审核操作
     * @param integer $id
     * @return type
     * @throws NotAcceptableHttpException
     */
    public function actionUndertake($id)
    {
        $model = $this->findModel($id);
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        /* @var $dtTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        if(!(\Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_UNDERTAKE) && $dtTool->getIsUndertakePerson()))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->getIsStatusUndertake())
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        
        if ($model->load(Yii::$app->request->post())) {
            $dtTool->UndertakeTask($model);
            return $this->redirect(['index']);
        } else {
            return $this->renderPartial('undertake', [
                'model' => $model,
                'team' => $twTool->getHotelTeam(),
                'undertake' => $dtTool->getHotelTeamMemberId(),
            ]);
        }
    }
    
    /**
     * 完成任务操作
     * @param integer $id
     * @return type
     * @throws NotAcceptableHttpException
     */
    public function actionComplete($id)
    {
        $model = $this->findModel($id);
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        if(!\Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_COMPLETE) && $model->create_by != \Yii::$app->user->id)
            throw new NotAcceptableHttpException('无权限操作！');
        if(!($model->getIsStatusAcceptance() || $model->getIsStatusAcceptanceing()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
                
        if ($model->load(Yii::$app->request->post())){
            $dtTool->CompleteTask($model);
            return $this->redirect(['index']);
        } else {
            return $this->renderPartial('complete', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * 恢复任务制作操作
     * @param integer $id
     * @return type
     * @throws NotAcceptableHttpException
     */
    public function actionRecovery($id)
    {
        $model = $this->findModel($id);
        if(!\Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_RESTORE) && $model->create_by != \Yii::$app->user->id)
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->getIsStatusCompleted())
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        $model->status = DemandTask::STATUS_ACCEPTANCE;
        $model->progress = $model->getStatusProgress();
        $model->reality_check_harvest_time = null;
        $dtTool->RecoveryTask($model);
        return $this->redirect(['view', 'id' => $model->id]);
    }
    
    /**
     * 取消任务
     * @param type $id
     * @return type
     * @throws NotAcceptableHttpException
     */
    public function actionCancel($id)
    {
        $model = $this->findModel($id);
        if(!\Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_CANCEL) && $model->create_by != \Yii::$app->user->id)
            throw new NotAcceptableHttpException('无权限操作！');
        if(!($model->getIsStatusCheck() || $model->getIsStatusAcceptance()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        $post = Yii::$app->request->post();
        $cancel = ArrayHelper::getValue($post, 'reason');
        $model->status = DemandTask::STATUS_CANCEL;
        
        if ($model->load($post)){
            $dtTool->CancelTask($model, $cancel);
            return $this->redirect(['index']);
        } else {
            return $this->renderPartial('cancel', [
                'model' => $model,
            ]);
        }
        
        
    }
    
    
    
    
    /**
     * Deletes an existing DemandTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    /**
     * 获取课程
     * @param type $id              专业/工种ID
     * @param type $mark            标识
     * @return type JSON
     */
    public function actionSearchSelect($id, $mark = null)
    {
        Yii::$app->getResponse()->format = 'json';
        $courseId = $mark == null ? DemandTask::find()  
                        ->select('course_id')  
                        ->where(['item_child_id'=> $id]) : null;         
        $errors = [];
        $items = [];
        try
        {
            $items = Item::find()  
                ->where(['parent_id'=> $id])
                ->andFilterWhere(['NOT IN','id', $courseId])
                ->all(); 
        } catch (Exception $ex) {
            $errors [] = $ex->getMessage();
        }
        return [
            'type'=>'S',
            'data' => $items,
            'error' => $errors
        ];
    }
    
    /**
     * Finds the DemandTask model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandTask the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DemandTask::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('rcoa', 'The requested page does not exist.'));
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
     * 获取专业/工种
     * @param type $itemId              层次/类型ID
     * @return type
     */
    protected function getChildren($itemId)
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getChildren($itemId), 'id', 'name');
    }
    
    /**
     * 获取过滤的课程
     * @param type $model
     * @return type
     */
    public function getCourses($itemChildId)
    {
        $existedCourses = DemandTask::find()->where(['item_child_id' => $itemChildId])->all();
        $courses = Item::find()
                ->where(['parent_id' => $itemChildId])
                ->andFilterWhere(['NOT IN', 'id', ArrayHelper::getColumn($existedCourses, 'course_id')])
                ->all();
        
        return ArrayHelper::map($courses, 'id', 'name');
    }
    
    /**
     * 获取专家库
     * @return type
     */
    public function getExpert(){
        $expert = Expert::find()->with('user')->all();
        return ArrayHelper::map($expert, 'u_id','user.nickname');
    }
    
    /**
     * 获取团队工作课程任务进度
     * @param integer|array $taskId             任务ID
     * @return  array
     */
    public function getTwCourseProgress($taskId)
    {
        /* @var $dtTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $course = (new Query())
                ->select(['id'])
                ->from(CourseManage::tableName())
                ->where(['demand_task_id' => $taskId])
                ->all();
        
        $progress = (new Query())
               ->select(['Demand_task.id', 'Tw_course_progress.progress'])
               ->from(['Tw_course_progress' => $twTool->getCourseProgress(ArrayHelper::getColumn($course, 'id'))])
               ->rightJoin(['Demand_task' => DemandTask::tableName()], 'Demand_task.id = Tw_course_progress.demand_task_id')
               ->all();
       
        return ArrayHelper::map($progress, 'id', 'progress');
    }
    
    /**
     * 获取附件
     * @param integer $taskId
     * @return object
     */
    public function getAnnex($taskId)
    {
        return DemandTaskAnnex::find()
               ->where(['task_id' => $taskId])
               ->with('task')
               ->all();
    }
}
