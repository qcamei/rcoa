<?php

namespace frontend\modules\multimedia\controllers;

use common\config\AppGlobalVariables;
use common\models\multimedia\MultimediaContentType;
use common\models\multimedia\MultimediaProducer;
use common\models\multimedia\MultimediaTask;
use common\models\team\TeamCategory;
use common\models\team\TeamMember;
use common\wskeee\job\JobManager;
use frontend\modules\multimedia\utils\MultimediaConvertRule;
use frontend\modules\multimedia\utils\MultimediaNoticeTool;
use frontend\modules\multimedia\utils\MultimediaTool;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\ItemType;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use wskeee\team\TeamMemberTool;
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
     * Task Lists all MultimediaTask models.
     * @return mixed
     */
    public function actionList($create_by = null, $producer = null, $assignPerson = null, $create_team = null,
        $make_team = null, $content_type = null, $item_type_id = null, $item_id = null, $item_child_id = null, $course_id = null,
        $status = 1, $time = null, $keyword = null, $mark = null, $page = null)
    {
        $page = $page == null ? 0 : $page-1; 
        /* @var $multimedia MultimediaTool */
        $multimedia = MultimediaTool::getInstance();
        $query = $multimedia->getMultimediaTask($create_by, $producer, $assignPerson, 
                    $create_team, $make_team, $content_type, $item_type_id, $item_id, $item_child_id, $course_id,
                    $status, $time, $keyword, $mark);
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->addSelect([
                'Multimedia_task.item_type_id', 'Multimedia_task.item_id', 'Multimedia_task.item_child_id', 'Multimedia_task.course_id',
                'Multimedia_task.name', 'Multimedia_task.progress', 'Multimedia_task.content_type', 'Multimedia_task.plan_end_time', 'Multimedia_task.level',
                'Multimedia_task.make_team', 'Multimedia_task.create_team', 'Multimedia_task.status',  
                'Multimedia_task.create_by', 'Assign_make_team.u_id AS make_team_u_id', 'Assign_create_team.u_id AS create_team_u_id',
                'Fm_item_type.name AS item_type_name','Fm_item.name AS item_name', 'Fm_item_child.name AS item_child_name',
                'Fm_course.name AS course_name'
                ])->limit(20)->offset($page*20)->all(),
        ]);
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'multimedia' => $multimedia,
            'team' => $this->getTeams(),
            'contentType' => $this->getContentType(),
            'itemType' => $this->getItemType(),
            'items' => $this->getItem(),
            'itemChild' => $item_id != null ? $this->getChildren($item_id) : [],
            'course' => $item_child_id != null ? $this->getChildren($item_child_id) : [],
            'createBy' => $this->getCreateBys(),
            'producers' => $this->getProducerList(),
            //搜索默认字段值
            'create_team' => $create_team,
            'make_team' => $make_team,
            'content_type' => $content_type,
            'item_type_id' => $item_type_id,
            'item_id' => $item_id,
            'item_child_id' => $item_child_id,
            'course_id' => $course_id,
            'create_by' => $create_by,
            'producer' => $producer,
            'status' => $status,
            'keyword' => $keyword,
            'time' => $time != null ? $time : null,
            'mark' => $mark != null ? $mark : 0,
        ]);
    }
    
    /**
     * Displays a single MultimediaTask model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        /* @var $multimedia MultimediaTool */
        $multimedia = MultimediaTool::getInstance();
        /* @var $multimediaNotice MultimediaNoticeTool */
        $multimediaNotice = MultimediaNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        if($model->getIsStatusCompleted() || $model->getIsStatusCancel() ){
            //取消用户与任务通知的关联
            $jobManager->cancelNotification(AppGlobalVariables::getSystemId(), $model->id, Yii::$app->user->id); 
        }else {
            //设置用户对通知已读
            $jobManager->setNotificationHasReady(AppGlobalVariables::getSystemId(), Yii::$app->user->id, $model->id);  
        }
        
        return $this->render('view', [
            'model' => $model,
            'multimedia' => $multimedia,
            'teams' => $this->getTeams($model->create_team),
            'workload' => $this->getWorkloadOne($model),
            'producerList' => $this->getProducerList(empty($model->make_team) ? $model->create_team : $model->make_team),
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
        $multimedia = MultimediaTool::getInstance();
        if(!Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_ASSIGN) 
          && !$multimedia->getIsAssignPerson(empty($model->make_team) ? $model->create_team : $model->make_team))
           throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->getIsStatusAssign())
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        
        $model->status = MultimediaTask::STATUS_TOSTART;
        $model->progress = MultimediaTask::$statusProgress[$model->status];
        $multimedia->saveAssignTask($model, $post);
        $this->redirect(['list', 'create_by' => Yii::$app->user->id, 'assignPerson' => Yii::$app->user->id]);
    }

    /**
     * Creates a new MultimediaTask model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_CREATE))
            throw new NotAcceptableHttpException('无权限操作！');
        $model = new MultimediaTask();
        $model->loadDefaultValues();
        /* @var $multimedia MultimediaTool */
        $multimedia = MultimediaTool::getInstance();
        $model->create_by = Yii::$app->user->id;
        $model->progress = $model->getStatusProgress();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $multimedia->saveCreateTask($model);
            return $this->redirect(['list', 'create_by' => $model->create_by, 'assignPerson' => Yii::$app->user->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'team' => $multimedia->getHotelTeam(Yii::$app->user->id),
                'itemType' => $this->getItemType(),
                'item' => $this->getItem(),
                'itemChild' => $model->item_id == null ? [] : $this->getChildren($model->item_id),  
                'course' => $model->item_child_id == null ? [] : $this->getChildren($model->item_child_id), 
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
        /* @var $multimedia MultimediaTool */
        $multimedia = MultimediaTool::getInstance();
        if(!Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_UPDATE) && $model->create_by != Yii::$app->user->id)
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->getIsStatusAssign())
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $multimedia->saveUpdateTask($model);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'team' => $multimedia->getHotelTeam(Yii::$app->user->id),
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
        $multimedia = MultimediaTool::getInstance();
        if(!$multimedia->getIsAssignPerson($model->create_team))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->getIsStatusAssign())
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        
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
        $multimedia = MultimediaTool::getInstance();
        if(!$multimedia->getIsAssignPerson($model->create_team))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->getIsStatusAssign())
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        
        $oldMakeTeam = $model->make_team;
        $model->brace_mark = MultimediaTask::CANCEL_BRACE_MARK;
        $model->make_team = null;
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
        $multimedia = MultimediaTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        if(!$multimedia->getIsProducer($model->id))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->getIsStatusTostart())
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        
        $model->status = MultimediaTask::STATUS_WORKING;
        $model->progress = $model->getStatusProgress();
        $multimedia->saveStartMakeTask($model);
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
        $multimedia = MultimediaTool::getInstance();
        if(!$multimedia->getIsProducer($model->id))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->getIsStatusWorking())
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        
        $model->status = MultimediaTask::STATUS_WAITCHECK;
        $model->progress = $model->getStatusProgress();
        $multimedia->saveSubmitMakeTask($model);
        return $this->redirect(['list', 'producer' => Yii::$app->user->id]);
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
        $multimedia = MultimediaTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        if(!Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_COMPLETE) && $model->create_by != Yii::$app->user->id)
            throw new NotAcceptableHttpException('无权限操作！');
        if(!($model->getIsStatusWaitCheck() || $model->getIsStatusChecking()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        
        $model->status = MultimediaTask::STATUS_COMPLETED;
        $model->progress = $model->getStatusProgress();
        $model->real_carry_out = date('Y-m-d H:i', time());
        if($model->load(Yii::$app->request->post()))
            $multimedia->saveCompleteTask($model);
        return $this->redirect(['list', 'create_by' => $model->create_by, 'assignPerson' => Yii::$app->user->id]);
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
        $multimedia = MultimediaTool::getInstance();
        if(Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_CREATE) && $model->create_by != Yii::$app->user->id)
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->getIsStatusCompleted())
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        
        $model->status = MultimediaTask::STATUS_CHECKING;
        $model->progress = $model->getStatusProgress();
        $multimedia->saveRecoveryTask($model);
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
        $multimedia = MultimediaTool::getInstance();
        if(!Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_CANCEL) && $model->create_by != Yii::$app->user->id)
            throw new NotAcceptableHttpException('无权限操作！');
        if($model->getIsStatusStartAfter())
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        
        $cancel = ArrayHelper::getValue(Yii::$app->request->post(), 'reason');
        $model->status = MultimediaTask::STATUS_CANCEL;
        $multimedia->saveCancelTask($model, $cancel);
        return $this->redirect(['list', 'create_by' => $model->create_by]);
    }

    /**
     * Deletes an existing MultimediaTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['list']);
    }*/

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
            throw new NotFoundHttpException(Yii::t('rcoa', 'The requested page does not exist.'));
        }
    }
    
    /**
     * 获取行业
     * @return type
     */
    protected function getItemType(){
        $itemType = ItemType::find()
                    ->all();
        
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
                       ->orderBy('index asc')
                       ->all();
        
        return ArrayHelper::map($contentType, 'id', 'name');
    }
    
    /**
     * 获取标准工作量
     * @param MultimediaTask $model     
     * @return array 
     */
    public function getWorkloadOne($model)
    {
        $proportion = MultimediaConvertRule::getInstance()
                      ->getRuleProportion($model->content_type, date('Y-m', $model->created_at));
        $video_length = empty($model->production_video_length) ? 
                        null : $model->production_video_length;
        $workload = $video_length * $proportion / 60;
        
        return [(int)$workload, $proportion];
    }    
    
    /**
     * 获取所有团队
     * @param type $teamId  团队ID
     * @return type
     */
    public function getTeams($teamId = null)
    {
        /* @var $tmTool TeamMemberTool */
        $tmTool = TeamMemberTool::getInstance();
        $results = $tmTool->getTeamsByCategoryId(TeamCategory::TYPE_CCOA_DEV_TEAM);
        $teams = [];
        foreach ($results as $team) {
            $teams[] = $team;
        }
        ArrayHelper::multisort($teams, 'index', SORT_ASC);    
        return ArrayHelper::map($teams, 'id', 'name');
    }

    /**
     * 获取所有创建者
     * @return type
     */
    public function getCreateBys()
    {
        /* @var $rbacManager RbacManager */
        $rbacManager = Yii::$app->authManager;
        $createBys = $rbacManager->getItemUsers(RbacName::ROLE_MULTIMEDIA_PROMULGATOR);
        
        return ArrayHelper::map($createBys, 'id', 'nickname');
    }

    /**
     * 获取制作团队下的所有制作人员
     * @param type $team        制作团队
     * @return type
     */
    public function getProducerList($team = null)
    {
        $producer = TeamMember::find()
                    ->where(['position_id' => 4])
                    ->andFilterWhere(['!=', 'is_delete', $team != null ? TeamMember::SURE_DELETE : null])
                    ->andFilterWhere(['team_id' => $team])
                    ->with('user')
                    ->all();
        
        if($team != null)
            return ArrayHelper::map($producer, 'id', 'user.nickname');
        else
            return ArrayHelper::map($producer, 'u_id', 'user.nickname');
    }
    
    /**
     * 获取已经指派的制作人
     * @param type $taskId      任务ID
     * @return type
     */
    public function getAlreadyProducer($taskId)
    {
        $producers = MultimediaProducer::find()
                    ->where(['task_id' => $taskId])
                    ->with('multimediaProducer.user')
                    ->all();
        
        return ArrayHelper::getColumn($producers, 'multimediaProducer.user.nickname');
    }
}
