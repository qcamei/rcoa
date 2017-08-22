<?php

namespace frontend\modules\worksystem\controllers;

use common\config\AppGlobalVariables;
use common\models\demand\DemandTask;
use common\models\team\TeamCategory;
use common\models\worksystem\WorksystemAnnex;
use common\models\worksystem\WorksystemTask;
use common\models\worksystem\WorksystemTaskType;
use common\wskeee\job\JobManager;
use frontend\modules\worksystem\utils\WorksystemAction;
use frontend\modules\worksystem\utils\WorksystemOperationHtml;
use frontend\modules\worksystem\utils\WorksystemTool;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\ItemType;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * TaskController implements the CRUD actions for WorksystemTask model.
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
        ];
    }

    /**
     * Lists all WorksystemTask models.
     * @param WorksystemTool $_wsTool  
     * @param integer $page                     页数
     * @return mixed
     */
    public function actionIndex($page = null)
    {
        $page = $page == null ? 0 : $page-1; 
        $params = Yii::$app->request->queryParams;
        $_wsTool = WorksystemTool::getInstance();
        $query = $_wsTool->getWorksystemTaskResult($params);
        
        $count = $query->count();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->addSelect([
                'Worksystem_task.item_type_id', 'Worksystem_task.item_id', 'Worksystem_task.item_child_id', 'Worksystem_task.course_id',
                'Worksystem_task.task_type_id', 'Worksystem_task.name', 
                'Worksystem_task.level', 'Worksystem_task.is_brace', 'Worksystem_task.is_epiboly', 'Worksystem_task.plan_end_time', 
                'Worksystem_task.external_team', 'Worksystem_task.create_team', 'Worksystem_task.status', 'Worksystem_task.progress',
                'Worksystem_task.create_by', 'External_team.user_id AS external_team_u_id', 'Create_team.user_id AS create_team_u_id',
                'Fw_item_type.name AS item_type_name','Fw_item.name AS item_name', 'Fw_item_child.name AS item_child_name',
                'Fw_item_course.name AS course_name'
            ])->limit(20)->offset($page*20)->all(),
        ]);
        
        $taskIds = ArrayHelper::getColumn($dataProvider->allModels, 'id');
        $taskStatus = ArrayHelper::map($dataProvider->allModels, 'id', 'status');
        $operation = $_wsTool->getIsBelongToOwnOperate($taskIds, $taskStatus);
        $producer = ArrayHelper::getValue($_wsTool->getWorksystemTaskProducer($taskIds), 'nickname');
                
        return $this->render('index', [
            'params' => $params,
            'dataProvider' => $dataProvider,
            'count' => $count,
            'operation' => $operation,
            'producer' => $producer,
            //条件
            'itemTypes' => $this->getItemTypes(),
            'items' => $this->getCollegesForSelects(),
            'itemChilds' => ArrayHelper::getValue($params, 'mark') ? 
                            $this->getChildrens(ArrayHelper::getValue($params, 'item_id')) : [],
            'courses' => ArrayHelper::getValue($params, 'mark')? 
                            $this->getChildrens(ArrayHelper::getValue($params, 'item_child_id')) : [],
            'taskTypes' => $this->getWorksystemTaskTypes(),
            'createTeams' => $this->getWorksystemTeams(TeamCategory::TYPE_CCOA_DEV_TEAM),
            'externalTeams' => ArrayHelper::merge($this->getWorksystemTeams(TeamCategory::TYPE_WORKSYSTEM_TEAM), $this->getEpibolyTeams()),
            'createBys' => $this->getCreateBys(),
            'producers' => $this->getProducerList(),
        ]);
        
    }

    /**
     * Displays a single WorksystemTask model.
     * @param WorksystemTool $_wsTool
     * @param WorksystemOperationHtml $_wsOp
     * @param JobManager $jobManager
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $this->layout = '@app/views/layouts/main';
        $model = $this->findModel($id);
        $_wsTool = WorksystemTool::getInstance();
        $_wsOp = WorksystemOperationHtml::getInstance();
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
            '_wsOp' => $_wsOp,
            'producer' => implode(',', ArrayHelper::getValue($_wsTool->getWorksystemTaskProducer($model->id), 'nickname')),
            'attributes' => $_wsTool->getWorksystemTaskAddAttributes($model->id),
            'annexs' => $this->getWorksystemAnnexs($model->id),
            'is_assigns' => $_wsTool->getIsAssignPeople($model->create_team),
            'is_producer' =>$_wsTool->getIsProducer($model->id),
        ]);
    }

    /**
     * Creates a new WorksystemTask model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param WorksystemAction $_wsAction
     * @return mixed
     */
    public function actionCreate()
    {
        $this->layout = '@app/views/layouts/main';
        
        $model = new WorksystemTask();
        $model->loadDefaultValues();
        $model->scenario = WorksystemTask::SCENARIO_CREATE;
        $_wsAction = WorksystemAction::getInstance();
        $post = Yii::$app->request->post();        
               
        if ($model->load($post)) {
            $_wsAction->CreateTask($model, $post);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'itemTypes' => $this->getItemTypes(),
                'items' => $this->getCollegesForSelects(),
                'itemChilds' => !empty($model->item_id) ? $this->getChildrens($model->item_id) : [],
                'courses' => !empty($model->item_child_id) ? $this->getChildrens($model->item_child_id) : [],
                'taskTypes' => $this->getWorksystemTaskTypes(),
                'teams' => $this->getUserTeam(),
            ]);
        }
    }

    /**
     * Updates an existing WorksystemTask model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param WorksystemAction $_wsAction
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $this->layout = '@app/views/layouts/main';
        $model = $this->findModel($id);        
        if($model->create_by == Yii::$app->user->id){
            if(!($model->getIsStatusDefault() || $model->getIsStatusAdjustmenting()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        }else {
            throw new NotAcceptableHttpException('无权限操作！');
        }
        $model->scenario = WorksystemTask::SCENARIO_UPDATE;
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();
        
        if ($model->load($post)) {
            $_wsAction->UpdateTask($model, $post);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'itemTypes' => $this->getItemTypes(),
                'items' => $this->getCollegesForSelects(),
                'itemChilds' => $this->getChildrens($model->item_id),
                'courses' => $this->getChildrens($model->item_child_id),
                'taskTypes' => $this->getWorksystemTaskTypes(),
                'teams' => $this->getUserTeam(),
                'annexs' => $this->getWorksystemAnnexs($model->id),
            ]);
        }
    }

    /**
     * Deletes an existing WorksystemTask model.
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
     * Cancel an existing WorksystemTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param WorksystemAction $_wsAction
     * @param integer $id
     * @return mixed
     */
    public function actionCancel($id)
    {
        $model = $this->findModel($id);
        if($model->create_by == Yii::$app->user->id){
            if(!($model->status < WorksystemTask::STATUS_WORKING))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        }else{
            throw new NotAcceptableHttpException('无权限操作！');
        }
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();
        
        if ($model->load($post)) {
            $_wsAction->CancelTask($model, $post);
            return $this->redirect(['index', 'create_by' => Yii::$app->user->id, 'status' => WorksystemTask::STATUS_DEFAULT, 'mark' => false,]);
        } else {
            return $this->renderAjax('_cancel', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * SubmitCheck an existing WorksystemTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param WorksystemAction $_wsAction
     * @param integer $task_id
     * @return mixed
     */
    public function actionSubmitCheck($task_id)
    {
        $model = $this->findModel($task_id);
        if($model->create_by == Yii::$app->user->id){
            if(!($model->getIsStatusDefault() || $model->getIsStatusAdjustmenting()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        }else{
            throw new NotAcceptableHttpException('无权限操作！');
        }
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();     
        
        
        if ($model->load($post)) {
            $_wsAction->SubmitCheckTask($model, $post);
            return $this->redirect(['index', 'create_by' => Yii::$app->user->id, 'status' => WorksystemTask::STATUS_DEFAULT, 'mark' => false,]);
        } else {
            return $this->renderAjax('_submit_check', [
                'model' => $model,
            ]);
        }        
    }
        
    /**
     * CreateCheck an existing WorksystemTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param WorksystemAction $_wsAction
     * @param WorksystemTool $_wsTool
     * @param integer $task_id
     * @return mixed
     */
    public function actionCreateCheck($task_id)
    {
        $model = $this->findModel($task_id);
        $_wsTool = WorksystemTool::getInstance();
        $is_assigns = $_wsTool->getIsAssignPeople($model->create_team);
        if($is_assigns){
            if(!($model->getIsStatusWaitCheck() || $model->getIsStatusChecking()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        }else{
            throw new NotAcceptableHttpException('无权限操作！');
        }
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();
        
        if ($model->load($post)) {
            $_wsAction->CreateCheckTask($model, $post);
            return $this->redirect(['index', 'assign_people' => Yii::$app->user->id, 'status' => WorksystemTask::STATUS_DEFAULT, 'mark' => false,]);
        } else {
            return $this->renderAjax('_create_check', [
                'model' => $model,
            ]);
        }        
    }
    
    /**
     * CreateAssign an existing WorksystemTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param WorksystemAction $_wsAction
     * @param WorksystemTool $_wsTool
     * @param integer $task_id
     * @return mixed
     */
    public function actionCreateAssign($task_id)
    {
        $model = $this->findModel($task_id);
        if(!($model->getIsStatusWaitCheck() || $model->getIsStatusChecking() || $model->getIsStatusWaitAssign()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();
        
        if ($model->load($post)) {
            $_wsAction->CreateAssignTask($model, $post);
            return $this->redirect(['index', 'assign_people' => Yii::$app->user->id, 'status' => WorksystemTask::STATUS_DEFAULT, 'mark' => false,]);
        } else {
            return $this->renderAjax('_create_assign', [
                'model' => $model,
                'teams' => $this->getUserTeam(true),
                'producerList' => $this->getAssignProducerList()
            ]);
        }        
    }
    
    /**
     * CreateBrace an existing WorksystemTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param WorksystemAction $_wsAction
     * @param WorksystemTool $_wsTool
     * @param integer $task_id
     * @return mixed
     */
    public function actionCreateBrace($task_id)
    {
        $model = $this->findModel($task_id);
        $_wsTool = WorksystemTool::getInstance();
        $is_assigns = $_wsTool->getIsAssignPeople($model->create_team);
        if($is_assigns && $model->getIsCancelBrace()){
            if(!($model->getIsStatusWaitCheck() || $model->getIsStatusChecking()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        }else{
            throw new NotAcceptableHttpException('无权限操作！');
        }
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();        
        
        if ($model->load($post)) {
            $_wsAction->CreateBraceTask($model, $post);
            return $this->redirect(['index', 'assign_people' => Yii::$app->user->id, 'status' => WorksystemTask::STATUS_DEFAULT, 'mark' => false,]);
        } else {
            return $this->renderAjax('_create_brace', [
                'model' => $model,
            ]);
        }        
    }
    
    /**
     * CancelBrace an existing WorksystemTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param WorksystemAction $_wsAction
     * @param WorksystemTool $_wsTool
     * @param integer $task_id
     * @return mixed
     */
    public function actionCancelBrace($task_id)
    {
        $model = $this->findModel($task_id);
        $_wsTool = WorksystemTool::getInstance();
        $is_assigns = $_wsTool->getIsAssignPeople($model->create_team);
        if($is_assigns && $model->getIsSeekBrace()){
            if(!($model->getIsStatusWaitAssign()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        }else{
            throw new NotAcceptableHttpException('无权限操作！');
        }
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();
        
        if ($model->load($post)) {
            $_wsAction->CancelBraceTask($model, $post);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->renderAjax('_cancel_brace', [
                'model' => $model,
            ]);
        }        
    }
    
    /**
     * CreateEpiboly an existing WorksystemTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param WorksystemAction $_wsAction
     * @param WorksystemTool $_wsTool
     * @param integer $task_id
     * @return mixed
     */
    public function actionCreateEpiboly($task_id)
    {
        $model = $this->findModel($task_id);
        $_wsTool = WorksystemTool::getInstance();
        $is_assigns = $_wsTool->getIsAssignPeople($model->create_team);
        if($is_assigns && $model->getIsCancelEpiboly()){
            if(!($model->getIsStatusWaitCheck() || $model->getIsStatusChecking()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        }else{
            throw new NotAcceptableHttpException('无权限操作！');
        }
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();
      
        if(!($model->getIsStatusWaitCheck() || $model->getIsStatusChecking()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        
        if ($model->load($post)) {
            $_wsAction->CreateEpibolyTask($model, $post);
            return $this->redirect(['index', 'assign_people' => Yii::$app->user->id, 'status' => WorksystemTask::STATUS_DEFAULT, 'mark' => false,]);
        } else {
            return $this->renderAjax('_create_epiboly', [
                'model' => $model,
            ]);
        }        
    }
    
    /**
     * CancelEpiboly an existing WorksystemTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param WorksystemAction $_wsAction
     * @param WorksystemTool $_wsTool
     * @param integer $task_id
     * @return mixed
     */
    public function actionCancelEpiboly($task_id)
    {
        $model = $this->findModel($task_id);
        $_wsTool = WorksystemTool::getInstance();
        $is_assigns = $_wsTool->getIsAssignPeople($model->create_team);
        if($is_assigns && $model->getIsSeekEpiboly()){
            if(!($model->getIsStatusWaitUndertake()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        }else{
            throw new NotAcceptableHttpException('无权限操作！');
        }
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();
        
        if ($model->load($post)) {
            $_wsAction->CancelEpibolyTask($model, $post);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->renderAjax('_cancel_epiboly', [
                'model' => $model,
            ]);
        }        
    }
    
    /**
     * StartMake an existing WorksystemTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param WorksystemAction $_wsAction
     * @param WorksystemTool $_wsTool
     * @param integer $task_id
     * @return mixed
     */
    public function actionStartMake($task_id)
    {
        $model = $this->findModel($task_id);
        $_wsTool = WorksystemTool::getInstance();
        $is_producer = $_wsTool->getIsProducer($model->id);
        if($is_producer){
            if(!($model->getIsStatusToStart()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        }else{
            throw new NotAcceptableHttpException('无权限操作！');
        }
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();
        
        if ($model->load($post)) {
            $_wsAction->StartMakeTask($model, $post);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->renderAjax('_start_make', [
                'model' => $model,
            ]);
        }        
    }
    
    /**
     * CreateUndertake an existing WorksystemTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param WorksystemAction $_wsAction
     * @param integer $task_id
     * @return mixed
     */
    public function actionCreateUndertake($task_id)
    {
        $model = $this->findModel($task_id);
        if(!($model->getIsStatusWaitUndertake()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();
        
        if ($model->load($post)) {
            $_wsAction->CreateUndertakeTask($model, $post);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->renderAjax('_create_undertake', [
                'model' => $model,
                'teams' => ArrayHelper::getValue($this->getUserTeamMembers(), 'team_id'),
                'producer' => ArrayHelper::getValue($this->getUserTeamMembers(), 'id'),
            ]);
        }        
    }
    
    /**
     * CancelUndertake an existing WorksystemTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param WorksystemAction $_wsAction
     * @param WorksystemTool $_wsTool
     * @param integer $task_id
     * @return mixed
     */
    public function actionCancelUndertake($task_id)
    {
        $model = $this->findModel($task_id);
        $_wsTool = WorksystemTool::getInstance();
        $is_producer = $_wsTool->getIsProducer($model->id);
        if($is_producer){
            if(!($model->getIsStatusToStart()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        }else{
            throw new NotAcceptableHttpException('无权限操作！');
        }
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();
        
        if ($model->load($post)) {
            $_wsAction->CancelUndertakeTask($model, $post);
            return $this->redirect(['index', 'producer' => Yii::$app->user->id, 'status' => WorksystemTask::STATUS_DEFAULT, 'mark' => false]);
        } else {
            return $this->renderAjax('_cancel_undertake', [
                'model' => $model,
            ]);
        }        
    }
    
    /**
     * CreateAcceptance an existing WorksystemTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param WorksystemAction $_wsAction
     * @param integer $task_id
     * @return mixed
     */
    public function actionCreateAcceptance($task_id)
    {
        $model = $this->findModel($task_id);
        if($model->create_by == Yii::$app->user->id){
            if(!($model->getIsStatusWaitAcceptance() || $model->getIsStatusAcceptanceing()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        }else{
            throw new NotAcceptableHttpException('无权限操作！');
        }
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();
        
        if ($model->load($post)) {
            $_wsAction->CreateAcceptanceTask($model, $post);
            return $this->redirect(['index', 'create_by' => Yii::$app->user->id, 'status' => WorksystemTask::STATUS_DEFAULT, 'mark' => false]);
        } else {
            return $this->renderAjax('_create_acceptance', [
                'model' => $model,
            ]);
        }        
    }
    
    /**
     * CompleteAcceptance an existing WorksystemTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param WorksystemAction $_wsAction
     * @param integer $task_id
     * @return mixed
     */
    public function actionCompleteAcceptance($task_id)
    {
        $model = $this->findModel($task_id);
        if($model->create_by == Yii::$app->user->id){
            if(!($model->getIsStatusWaitAcceptance() || $model->getIsStatusAcceptanceing()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        }else{
            throw new NotAcceptableHttpException('无权限操作！');
        }
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();
        
        if ($model->load($post)) {
            $_wsAction->CompleteAcceptanceTask($model, $post);
            return $this->redirect(['index', 'create_by' => Yii::$app->user->id, 'status' => WorksystemTask::STATUS_DEFAULT, 'mark' => false]);
        } else {
            return $this->renderAjax('_complete_acceptance', [
                'model' => $model,
            ]);
        }        
    }
    
    /**
     * 检查课程是否存在
     * @param integer $course_id                课程id
     * @return JSON json
     */
    public function actionCheckExist($course_id)
    {
        Yii::$app->getResponse()->format = 'json';
        $message = '';          //消息
        $type = 0;              //是否成功：0为否，1为是
        $items = [];            //数据
        $errors = [];           //错误
        
        $demandTasks = (new Query()) 
            ->select(['item_type_id', 'team_id',])
            ->from(DemandTask::tableName())
            ->where(['and', ['course_id'=> $course_id], ['status' => DemandTask::STATUS_DEVELOPING]])
            ->one();
        $itemTeamId = ArrayHelper::getValue($demandTasks, 'team_id');
        $teamIds = $this->getUserTeam(true);
        
        try
        {
            if($demandTasks == null){
                $type = 1;
                $message = '该课程在需求任务里不存在';
            }else if(!in_array($itemTeamId, $teamIds)){
                $type = 1;
                $message = '该课程不是你本团队承接的';
            }else{
                $items = $demandTasks;
            }
            
        } catch (Exception $ex) {
            $errors [] = $ex->getMessage();
        }
        return [
            'type'=> $type,
            'data' => $items,
            'message' => $message,
            'error' => $errors
        ];
    }
    
    /**
     * Finds the WorksystemTask model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorksystemTask the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorksystemTask::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 获取所有行业
     * @return type
     */
    public function getItemTypes()
    {
        $itemType = ItemType::find()->with('itemManages')->all();
        return ArrayHelper::map($itemType, 'id', 'name');
    }
    
    /**
     * 获取所有层次/类型
     * @return type
     */
    public function getCollegesForSelects()
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getColleges(), 'id', 'name');
    }
    
    /**
     * 获取所有专业/工种 or 课程
     * @param type $itemId              层次/类型ID
     * @return type
     */
    protected function getChildrens($itemId)
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getChildren($itemId), 'id', 'name');
    }
    
    /**
     * 获取所有工作系统任务类型
     * @return object
     */
    public function getWorksystemTaskTypes()
    {
        $taskTypes = WorksystemTaskType::find()
                    ->select(['id', 'name', 'icon'])
                    ->all();
        
        return $taskTypes;
    }
    
    /**
     * 获取所有工作系统团队
     * @param type $categoryId
     * @param TeamMemberTool $_tmTool
     * @return array
     */
    public function getWorksystemTeams($categoryId)
    {
        $_tmTool = TeamMemberTool::getInstance();
        $teams = $_tmTool->getTeamsByCategoryId($categoryId);
        
        return ArrayHelper::map($teams, 'id', 'name');
    }
    
    /**
     * 获取所有外包团队
     * @param TeamMemberTool $_tmTool
     * @return array
     */
    public function getEpibolyTeams()
    {
        $_tmTool = TeamMemberTool::getInstance();
        $teams = $_tmTool->getTeamsByCategoryId(TeamCategory::TYPE_EPIBOLY_TEAM);
        
        return ArrayHelper::map($teams, 'id', 'name');
    }
    
    /**
     * 获取所有创建者
     * @param RbacManager $rbacManager
     * @return array
     */
    public function getCreateBys()
    {
        $rbacManager = Yii::$app->authManager;
        $createBys = $rbacManager->getItemUsers(RbacName::ROLE_WORKSYSTEM_PUBLISHER);
        
        return ArrayHelper::map($createBys, 'id', 'nickname');
    }
    
    /**
     * 获取所有被指派的制作人员
     * @param TeamMemberTool $_tmTool
     * @return array
     */
    public function getAssignProducerList()
    {
        $_tmTool = TeamMemberTool::getInstance();
        
        $producers = $_tmTool->getAppointUserPositionTeamMembers(Yii::$app->user->id, TeamCategory::TYPE_WORKSYSTEM_TEAM);
        
        return ArrayHelper::map($producers, 'id', 'nickname');
    }
    
    /**
     * 获取所有制作人员
     * @return array
     */
    public function getProducerList()
    {
        //$_tmTool = TeamMemberTool::getInstance();
        //$producers = $_tmTool->getAppointPositionTeamMembers();
        
        $rbacManager = Yii::$app->authManager;
        $producers = $rbacManager->getItemUsers(RbacName::ROLE_WORKSYSTEM_PRODUCER);
        
        return ArrayHelper::map($producers, 'id', 'nickname');
    }
    
    /**
     * 获取用户所在团队
     * @param TeamMemberTool $_tmTool
     * @param boolean $is_validate              是否验证：false为否，true为是
     * @return array
     */
    public function getUserTeam($is_validate = false)
    {
        $_tmTool = TeamMemberTool::getInstance();
        $teams = $_tmTool->getUserTeam(Yii::$app->user->id, TeamCategory::TYPE_WORKSYSTEM_TEAM);
        
        if(count($teams) == 1 || $is_validate)
            return ArrayHelper::getColumn($teams, 'id');
        else
            return ArrayHelper::map($teams, 'id', 'name');
    }
    
    /**
     * 获取用户的team_member_id
     * @return array
     */
    public function getUserTeamMembers()
    {
        $_tmTool = TeamMemberTool::getInstance();
        $teamMembers = $_tmTool->getUserTeamMembers(Yii::$app->user->id, TeamCategory::TYPE_EPIBOLY_TEAM);
        
        return $maps = [
           'id' => ArrayHelper::getColumn($teamMembers, 'id'),
            'team_id' => ArrayHelper::getColumn($teamMembers, 'team_id')
        ];
                
    }
    
    /**
     * 获取所有工作系统附件
     * @param integer $taskId                   工作系统任务id
     * @return array
     */
    public function getWorksystemAnnexs($taskId)
    {
        $annexs = (new Query())
                ->from(WorksystemAnnex::tableName())
                ->where(['worksystem_task_id' => $taskId])
                ->all();
        
        return $annexs;
    }
}
