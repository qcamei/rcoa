<?php

namespace frontend\modules\multimedia\controllers;

use common\models\multimedia\MultimediaContentType;
use common\models\multimedia\MultimediaProducer;
use common\models\multimedia\MultimediaTask;
use common\models\multimedia\MultimediaTypeProportion;
use common\models\multimedia\searchs\MultimediaTaskSearch;
use common\models\team\Team;
use common\models\team\TeamMember;
use common\wskeee\job\JobManager;
use frontend\modules\multimedia\MultimediaNoticeTool;
use frontend\modules\multimedia\MultimediaTool;
use wskeee\framework\FrameworkManager;
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
 * DefaultController implements the CRUD actions for MultimediaTask model.
 */
class DefaultController extends Controller
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
     * Lists all MultimediaTask models.
     * @return mixed
     */
    public function actionIndex()
    {
        /* @var $multimedia MultimediaTool */
        $multimedia = \Yii::$app->get('multimedia');
        $searchModel = new MultimediaTaskSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'multimedia' => $multimedia,
        ]);
    }
    
    /**
     * Personal Lists all MultimediaTask models.
     * @return mixed
     */
    public function actionPersonal($create_by = null, $producer = null, $assignPerson = null, $status = null)
    {
        /* @var $multimedia MultimediaTool */
        $multimedia = \Yii::$app->get('multimedia');
        $status = $status == null ? MultimediaTask::$defaultStatus : $status;
        $dataProvider = new ArrayDataProvider([
            'allModels' => $multimedia->getMultimediaTask($create_by, $producer, $assignPerson, 
                    $makeTeam = null, $createTeam = null, $status),
        ]);
        
        return $this->render('personal', [
            'dataProvider' => $dataProvider,
            'multimedia' => $multimedia,
        ]);
    }
    
    /**
     * Team Lists all MultimediaTask models.
     * @return mixed
     */
    public function actionTeam($make_team = null, $create_team = null, $status = null)
    {
        /* @var $multimedia MultimediaTool */
        $multimedia = \Yii::$app->get('multimedia');
        $status = $status == null ? MultimediaTask::$defaultStatus : $status;
        $dataProvider = new ArrayDataProvider([
            'allModels' => $multimedia->getMultimediaTask($createBy = null, 
                    $producer = null, $assignPerson = null, $make_team, $create_team, $status), 
        ]);

        return $this->render('team', [
            'dataProvider' => $dataProvider,
            'multimedia' => $multimedia,
        ]);
    }

    /**
     * Displays a single MultimediaTask model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        /* @var $multimedia MultimediaTool */
        $multimedia = \Yii::$app->get('multimedia');
        /* @var $multimediaNotice MultimediaNoticeTool */
        $multimediaNotice = \Yii::$app->get('multimediaNotice');
        $model = $this->findModel($id);
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        if(!$model->getIsStatusCompleted() || !$model->getIsStatusCancel() ){
            //设置用户对通知已读
            $jobManager->setNotificationHasReady(10, Yii::$app->user->id, $model->id);  
        }else {
            //取消用户与任务通知的关联
            $jobManager->cancelNotification(10, $model->id, Yii::$app->user->id); 
        }
        
        return $this->render('view', [
            'model' => $model,
            'multimedia' => $multimedia,
            'teams' => $this->getTeams(),
            'workload' => $this->getWorkloadOne($model, $model->content_type),
            'producerList' => $this->getProducerList($model->make_team),
            'producer' => $this->getAlreadyProducer($id),
        ]);
    }
    
    /**
     * 指派制作人员
     * @param int $id           
     */
    public function actionAssign($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        /* @var $multimedia MultimediaTool */
        $multimedia = \Yii::$app->get('multimedia');
        $model->status = MultimediaTask::STATUS_TOSTART;
        $model->progress = MultimediaTask::$statusProgress[$model->status];
        
        if(\Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_ASSIGN) && $multimedia->getIsAssignPerson($model->make_team)){
            $multimedia->saveAssignTask($model, $post);
            $this->redirect(['personal', 'assignPerson' => Yii::$app->user->id]);
        } else {
            throw new NotAcceptableHttpException('无权限操作！');
        }
    }

    /**
     * Creates a new MultimediaTask model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!\Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_CREATE))
            throw new NotAcceptableHttpException('无权限操作！');
        $model = new MultimediaTask();
        $model->loadDefaultValues();
        /* @var $multimedia MultimediaTool */
        $multimedia = \Yii::$app->get('multimedia');
        $model->create_by = \Yii::$app->user->id;
        $model->create_team = $multimedia->getHotelTeam($model->create_by);
        $model->make_team = $model->create_team;
        $model->progress = $model->getStatusProgress();
        
        if ($model->load(Yii::$app->request->post())) {
            $multimedia->saveCreateTask($model);
            return $this->redirect(['personal', 'create_by' => $model->create_by]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'itemType' => $this->getItemType(),
                'item' => $this->getItem(),
                'itemChild' => [],
                'course' => [],
                'contentType' => $this->getContentType(),
            ]);
        }
    }

    /**
     * Updates an existing MultimediaTask model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(!\Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_UPDATE) && $model->create_by != \Yii::$app->user->id)
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'itemType' => $this->getItemType(),
                'item' => $this->getItem(),
                'itemChild' => $this->getChildren($model->item_id),
                'course' => $this->getChildren($model->item_child_id),
                'contentType' => $this->getContentType(),
            ]);
        }
    }
    
    /**
     * 寻求支撑
     * @param type $id
     * @return type
     * @throws NotAcceptableHttpException
     */
    public function actionSeekBrace($id)
    {
        $model = $this->findModel($id);
        /* @var $multimedia MultimediaTool */
        $multimedia = \Yii::$app->get('multimedia');
        if(!$multimedia->getIsAssignPerson($model->create_team))
            throw new NotAcceptableHttpException('无权限操作！');
        
        $model->brace_mark = MultimediaTask::SEEK_BRACE_MARK;
        if ($model->load(Yii::$app->request->post()))
            $multimedia->saveSeekBraceTask ($model);
        return $this->redirect(['view', 'id' => $model->id]);
    }
    
    /**
     * 取消支撑
     * @param type $id
     * @return type
     * @throws NotAcceptableHttpException
     */
    public function actionCancelBrace($id)
    {
        $model = $this->findModel($id);
        /* @var $multimedia MultimediaTool */
        $multimedia = \Yii::$app->get('multimedia');
        if(!$multimedia->getIsAssignPerson($model->create_team))
            throw new NotAcceptableHttpException('无权限操作！');
        
        $oldMakeTeam = $model->make_team;
        $model->brace_mark = MultimediaTask::CANCEL_BRACE_MARK;
        $model->make_team = $model->create_team;
        $multimedia->saveCancelBraceTask($model, $oldMakeTeam);
        return $this->redirect(['view', 'id' => $model->id]);
    }
    
    /**
     * 开始制作
     * @param type $id
     * @return type
     * @throws NotAcceptableHttpException
     */
    public function actionStart($id)
    {
        $model = $this->findModel($id);
        /* @var $multimedia MultimediaTool */
        $multimedia = \Yii::$app->get('multimedia');
        if(!$multimedia->getIsProducer($model->id))
            throw new NotAcceptableHttpException('无权限操作！');
        
        $model->status = MultimediaTask::STATUS_WORKING;
        $model->progress = $model->getStatusProgress();
        $model->save(false, ['status', 'progress']);
        return $this->redirect(['view', 'id' => $model->id]);
    }
    
    /**
     * 完成制作, 提交制作任务
     * @param type $id
     * @return type
     * @throws NotAcceptableHttpException
     */
    public function actionSubmit($id)
    {
        $model = $this->findModel($id);
        /* @var $multimedia MultimediaTool */
        $multimedia = \Yii::$app->get('multimedia');
        if(!$multimedia->getIsProducer($model->id))
            throw new NotAcceptableHttpException('无权限操作！');
        
        $model->status = MultimediaTask::STATUS_WAITCHECK;
        $model->progress = $model->getStatusProgress();
        $model->save(false, ['status', 'progress']);
        return $this->redirect(['view', 'id' => $model->id]);
    }
    
    /**
     * 完成任务操作
     * @param type $id
     * @return type
     * @throws NotAcceptableHttpException
     */
    public function actionComplete($id)
    {
        $model = $this->findModel($id);
        $model->scenario = MultimediaTask::SCENARIO_COMPLETE;
        /* @var $multimedia MultimediaTool */
        $multimedia = \Yii::$app->get('multimedia');
        if(!\Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_COMPLETE) && $model->create_by != \Yii::$app->user->id)
            throw new NotAcceptableHttpException('无权限操作！');
        
        $model->status = MultimediaTask::STATUS_COMPLETED;
        $model->progress = $model->getStatusProgress();
        if ($model->load(Yii::$app->request->post()) && $model->save())        
            return $this->redirect(['view', 'id' => $model->id]);
    }
    
    /**
     * 恢复任务制作操作
     * @param type $id
     * @return type
     * @throws NotAcceptableHttpException
     */
    public function actionRecovery($id)
    {
        $model = $this->findModel($id);
        /* @var $multimedia MultimediaTool */
        $multimedia = \Yii::$app->get('multimedia');
        if($model->create_by != \Yii::$app->user->id)
            throw new NotAcceptableHttpException('无权限操作！');
        
        $model->status = MultimediaTask::STATUS_WAITCHECK;
        //$model->progress = MultimediaTask::$statusProgress[$model->status];
        $model->save(false, ['status']);
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
        /* @var $multimedia MultimediaTool */
        $multimedia = \Yii::$app->get('multimedia');
        if(!\Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_CANCEL) && $model->create_by != \Yii::$app->user->id)
            throw new NotAcceptableHttpException('无权限操作！');
        
        $model->status = MultimediaTask::STATUS_CANCEL;
        $model->save(false, ['status']);
        return $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing MultimediaTask model.
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
     * Finds the MultimediaTask model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MultimediaTask the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MultimediaTask::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('rcoa', 'The requested page does not exist.'));
        }
    }
    
    /**
     * 获取行业
     * @return type
     */
    protected function getItemType(){
        $itemType = ItemType::find()->all();
        return ArrayHelper::map($itemType, 'id','name');
    }
    
    /**
     * 获取层次/类型
     * @return type
     */
    protected function getItem()
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
     * 获取内容类型
     * @return type
     */
    public function getContentType()
    {
        $contentType = MultimediaContentType::find()
                       ->with('multimediaTasks')
                       ->with('proportions')
                       ->all();
        return ArrayHelper::map($contentType, 'id', 'name');
    }
    
    /**
     * 获取所有任务的标准工作量
     * @return type
     
    public function getTaskWorkloadAll() 
    {
        $result = (new Query())
                  ->select(['id', 'material_video_length', 'progress'])
                  ->from(MultimediaTask::tableName())
                  ->where(['NOT IN', 'status', [ MultimediaTask::STATUS_COMPLETED, MultimediaTask::STATUS_CANCEL]])
                  ->andWhere(['IN', 'content_type', ArrayHelper::getColumn($this->getProportion(), 'content_type')])
                  ->all();
        
        $workload = [];
        //var_dump(ArrayHelper::getColumn($this->getProportion(), 'proportion'));exit;
        /*foreach ($result as $value) {
            $workload[$value['id']] = [
                'total' => (int)($value['material_video_length'] * ArrayHelper::getColumn($this->getProportion(), 'proportion')),
                //'surplus' => (int)(($value['video_length'] * $value['proportion']) * ($value['progress'] / 100)),
            ];
        }
        //var_dump($result);
        //return $workload;
    }*/
    
    /**
     * 获取所有内容类型比例
     * @return type
     
    public function getProportion()
    {
        $result = (new Query())
                     ->select(['content_type', 'proportion'])
                     ->from(MultimediaTypeProportion::tableName())
                     ->orderBy('target_month desc')
                     ->all();
        return $result;   
    }*/
    
    /**
     * 获取标准工作量
     * @param type $model     
     * @param type $contentType     任务内容类型
     * @return array 
     */
    public function getWorkloadOne($model = null, $contentType = null)
    {
        /* @var $model MultimediaTask */
        $proportionAll = MultimediaTypeProportion::find()
                      ->filterWhere(['content_type' => $contentType])
                      ->andFilterWhere(['<=', 'target_month', date('Y-m', $model->created_at)])
                      ->all();
        $proportion = end($proportionAll);
        $video_length = empty($model->production_video_length) ? $model->material_video_length : $model->production_video_length;
        $workload = $video_length * $proportion['proportion'];
        return [$workload, $proportion];
    }    
    
    /**
     * 获取所有团队
     * @return type
     */
    public function getTeams(){
        $team = Team::find()
                ->where(['type' => 1])
                ->all();
        return ArrayHelper::map($team, 'id', 'name');
    }

    /**
     * 获取制作团队下的所有制作人员
     * @param type $makeTeam        制作团队
     * @return type
     */
    public function getProducerList($makeTeam)
    {
        $producer = TeamMember::find()
                    ->where(['team_id' => $makeTeam])
                    ->andWhere(['position_id' => 3])
                    ->with('u')
                    ->all();
        return ArrayHelper::map($producer, 'u_id', 'u.nickname');
    }
    
    /**
     * 获取已经指派的制作人
     * @param type $taskId      任务ID
     * @return type
     */
    public function getAlreadyProducer($taskId)
    {
        $producer = MultimediaProducer::find()
                           ->where(['task_id' => $taskId])
                           ->with('producer')
                           ->all();
        return ArrayHelper::map($producer, 'u_id', 'producer.u.nickname');
    }
}
