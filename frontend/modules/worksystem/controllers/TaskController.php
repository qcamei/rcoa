<?php

namespace frontend\modules\worksystem\controllers;

use common\config\AppGlobalVariables;
use common\models\demand\DemandTask;
use common\models\team\TeamCategory;
use common\models\team\TeamMember;
use common\models\User;
use common\models\worksystem\WorksystemAnnex;
use common\models\worksystem\WorksystemTask;
use common\models\worksystem\WorksystemTaskProducer;
use common\models\worksystem\WorksystemTaskType;
use common\wskeee\job\JobManager;
use frontend\modules\worksystem\utils\WorksystemAction;
use frontend\modules\worksystem\utils\WorksystemOperationHtml;
use frontend\modules\worksystem\utils\WorksystemSearch;
use frontend\modules\worksystem\utils\WorksystemTool;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\ItemType;
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
    public function actionIndex()
    {
        $searchResult = new WorksystemSearch();
        $results = $searchResult->search(Yii::$app->request->queryParams);
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $results['result'],
        ]);
                
        return $this->render('index', [
            'param' => $results['param'],
            'dataProvider' => $dataProvider,
            'totalCount' => $results['totalCount'],
            'operation' => $results['isBelong'],
            //条件
            'itemTypes' => $this->getItemTypes(),
            'items' => $this->getCollegesForSelects(),
            'itemChilds' => ArrayHelper::getValue($results['param'], 'mark') ? 
                            $this->getChildrens(ArrayHelper::getValue($results['param'], 'item_id')) : [],
            'courses' => ArrayHelper::getValue($results['param'], 'mark')? 
                            $this->getChildrens(ArrayHelper::getValue($results['param'], 'item_child_id')) : [],
            'taskTypes' => $this->getWorksystemTaskTypes(),
            'createTeams' => $this->getWorksystemTeams(),
            'externalTeams' => ArrayHelper::merge($this->getWorksystemTeams(), $this->getEpibolyTeams()),
            'createBys' => ArrayHelper::getValue($this->getTaskCreatorProducer(), 'createBy'),
            'producers' => ArrayHelper::getValue($this->getTaskCreatorProducer(), 'producer'),
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
        $producers = WorksystemAction::getInstance()->getWorksystemTaskProducer($model->id);
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
            'producer' => implode(',', ArrayHelper::getValue($producers, 'nickname')),
            'attributes' => $_wsTool->getWorksystemTaskAddAttributes($model->id),
            'annexs' => $this->getWorksystemAnnexs($model->id),
            'isHaveAssign' => $_wsTool->getIsHaveAssign($model->create_team),
            'isHaveMake' =>$_wsTool->getIsHaveMake($model->id),
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
            return $this->redirect(['index']);
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
            return $this->redirect(['index']);
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
        $isHaveAssign = $_wsTool->getIsHaveAssign($model->create_team);
        if($isHaveAssign){
            if(!($model->getIsStatusWaitCheck() || $model->getIsStatusChecking()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        }else{
            throw new NotAcceptableHttpException('无权限操作！');
        }
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();
        
        if ($model->load($post)) {
            $_wsAction->CreateCheckTask($model, $post);
            return $this->redirect(['index']);
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
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('_create_assign', [
                'model' => $model,
                'teams' => $this->getUserTeam(),
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
        $isHaveAssign = $_wsTool->getIsHaveAssign($model->create_team);
        if($isHaveAssign && $model->getIsCancelBrace()){
            if(!($model->getIsStatusWaitCheck() || $model->getIsStatusChecking()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        }else{
            throw new NotAcceptableHttpException('无权限操作！');
        }
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();        
        
        if ($model->load($post)) {
            $_wsAction->CreateBraceTask($model, $post);
            return $this->redirect(['index']);
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
        $isHaveAssign = $_wsTool->getIsHaveAssign($model->create_team);
        if($isHaveAssign && $model->getIsSeekBrace()){
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
        $isHaveAssign = $_wsTool->getIsHaveAssign($model->create_team);
        if($isHaveAssign && $model->getIsCancelEpiboly()){
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
            return $this->redirect(['index']);
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
        $isHaveAssign = $_wsTool->getIsHaveAssign($model->create_team);
        if($isHaveAssign && $model->getIsSeekEpiboly()){
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
        $is_producer = $_wsTool->getIsHaveMake($model->id);
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
        $teamMemberMap = $this->getUserTeamMembers();
        
        if ($model->load($post)) {
            $_wsAction->CreateUndertakeTask($model, $post);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->renderAjax('_create_undertake', [
                'model' => $model,
                'teams' => $teamMemberMap[Yii::$app->user->id]['team_id'],
                'producer' => $teamMemberMap[Yii::$app->user->id]['id'],
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
        $is_producer = $_wsTool->getIsHaveMake($model->id);
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
            return $this->redirect(['index']);
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
            return $this->redirect(['index']);
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
            return $this->redirect(['index']);
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
        $type = 1;              //是否成功：0为否，1为是
        $isAdd = 0;             //是否添加：0为否，1为是
        $items = [];            //数据
        $errors = [];           //错误
        
        $demandTask = DemandTask::find()
                ->where(['and', ['course_id'=> $course_id], ['!=', 'status', DemandTask::STATUS_CANCEL]])
                ->orderBy('id DESC')
                ->one();
        $teamIds = array_keys($this->getUserTeam());
        
        try
        {
            if($demandTask == null){
                $type = 0;
                $isAdd = 1;
                $message = '该课程在需求任务里不存在。';
            }/*else if($demandTask->status != DemandTask::STATUS_DEVELOPING){
                $type = 0;
                $message = '该课程不是在开发中。';
            }*/else if(!in_array($demandTask->team_id, $teamIds)){
                $type = 0;
                $isAdd = 1;
                $message = '该课程不是本团队承接。';
            }else{
                $items = ['item_type_id' => $demandTask->item_type_id, 'team_id' => $demandTask->team_id];
            }
            
        } catch (Exception $ex) {
            $errors [] = $ex->getMessage();
        }
        return [
            'type'=> $type,
            'isAdd'=> $isAdd,
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
     * @return array
     */
    public function getWorksystemTeams()
    {
        $_tmTool = TeamMemberTool::getInstance();
        $teams = $_tmTool->getTeamsByCategoryId(TeamCategory::TYPE_WORKSYSTEM_TEAM);
        ArrayHelper::multisort($teams, 'index', SORT_ASC);  
        
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
     * 获取所有任务创建者和制作人
     * @return array
     */
    public function getTaskCreatorProducer()
    {
        $query = (new Query())
                ->select(['WorksystemTask.id', "CONCAT(WorksystemTask.create_by, '_', User.nickname) AS create_by"])
                ->from(['WorksystemTask' => WorksystemTask::tableName()])
                ->leftJoin(['User' => User::tableName()], 'User.id = WorksystemTask.create_by');
        $results = (new Query())
                ->select(['CreateBy.create_by', "CONCAT(TeamMember.u_id, '_', User.nickname) AS producer"])
                ->from(['CreateBy' => $query])
                ->leftJoin(['Producer' => WorksystemTaskProducer::tableName()], 'Producer.worksystem_task_id = CreateBy.id')
                ->leftJoin(['TeamMember' => TeamMember::tableName()], 'TeamMember.id = Producer.team_member_id')
                ->leftJoin(['User' => User::tableName()], 'User.id = TeamMember.u_id')
                ->all();
        
        $createBys = [];
        $producers = [];
        foreach ($results as $item) {
            $createBys[explode('_', $item['create_by'])[0]] = isset(explode('_', $item['create_by'])[1]) ? explode('_', $item['create_by'])[1] : '';
            $producers[explode('_', $item['producer'])[0]] = isset(explode('_', $item['producer'])[1]) ? explode('_', $item['producer'])[1] : '';
        }
        
        return [
            'createBy' => array_filter($createBys),
            'producer' => array_filter($producers)
        ];
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
        
        return $producers;
    }
    
    /**
     * 获取用户所在团队
     * @param TeamMemberTool $_tmTool
     * @return array
     */
    public function getUserTeam()
    {
        $_tmTool = TeamMemberTool::getInstance();
        $teams = $_tmTool->getUserTeam(Yii::$app->user->id, TeamCategory::TYPE_WORKSYSTEM_TEAM);
        
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
        $teamMemberMap = [];
        foreach($teamMembers as $item){
            $teamMemberMap[Yii::$app->user->id] = [
                'id' => $item['id'],
                'team_id' => $item['team_id'],
            ];
        }
        
        return $teamMemberMap;
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
