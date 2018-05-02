<?php

namespace frontend\modules\demand\controllers;

use common\config\AppGlobalVariables;
use common\models\demand\DemandTask;
use common\models\demand\DemandTaskAnnex;
use common\models\expert\Expert;
use common\models\team\TeamCategory;
use common\models\User;
use common\wskeee\job\JobManager;
use frontend\modules\demand\utils\DemandAction;
use frontend\modules\demand\utils\DemandQuery;
use frontend\modules\demand\utils\DemandSearch;
use frontend\modules\demand\utils\DemandTool;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\Query;
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
        ];
    }

    /**
     * Lists all DemandTask models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchResult = new DemandSearch();
        $results = $searchResult->search(Yii::$app->request->queryParams);
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $results['result'],
        ]);
        
        $creatorUndertaker = $this->getTaskCreatorUndertaker();
        
        return $this->render('index', [
            'param' => $results['param'],
            'dataProvider' => $dataProvider,
            'totalCount' => $results['totalCount'],
            'operation' => $results['isBelong'],
            //条件
            'itemType' => $this->getItemType(),
            'items' => $this->getCollegesForSelect(),
            'itemChild' => ArrayHelper::getValue($results['param'], 'mark') ? 
                           $this->getChildren(ArrayHelper::getValue($results['param'], 'item_id')) : [],
            'course' => ArrayHelper::getValue($results['param'], 'mark') ? 
                        $this->getChildren(ArrayHelper::getValue($results['param'], 'item_child_id')) : [],
            'developTeams' => $this->getDevelopTeams(),
            'createBys' => $creatorUndertaker['createBy'],
            'undertakers' => $creatorUndertaker['undertaker'],
        ]);
    }

    /**
     * Displays a single DemandTask model.
     * @param integer $id
     * @param integer $develop                  是否现在就开始创建课程开发数据标识  1为是0为否（默认为0）
     * @return mixed
     */
    public function actionView($id, $develop = 0)
    {
        $this->layout = '@app/views/layouts/main';
        $model = $this->findModel($id);
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
            'develop' => $develop,
            'demandAction' => DemandAction::getInstance(),
            'rbacManager' => Yii::$app->authManager,
            'annex' => $this->getDemandTaskAnnexs($model->id),
            'workitmType' => DemandTool::getInstance()->getDemandWorkitemTypeData($model->id),
            'workitem' => DemandTool::getInstance()->getDemandWorkitemData($model->id),
        ]);
    }

    /**
     * Creates a new DemandTask model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id = null)
    {
        $this->layout = '@app/views/layouts/main';
        $model = new DemandTask();
        $model->loadDefaultValues();
        
        if ($model->load(Yii::$app->request->post())) {
            DemandAction::getInstance()->DemandCreateTask($model, Yii::$app->request->post());
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'itemTypes' => $this->getItemType(),
                'items' => $this->getCollegesForSelect(),
                'itemChilds' => !empty($model->item_id) ? $this->getChildren($model->item_id) : [],
                'courses' => !empty($model->item_child_id) ? $this->getChildren($model->item_child_id) : [],
                'teachers' => $this->getExpert(),
                'teams' => $this->getUserTeam(TeamCategory::TYPE_PRODUCT_CENTER),
                'workitmType' => DemandTool::getInstance()->getDemandWorkitemTypeData(),
                'workitem' => DemandTool::getInstance()->getDemandWorkitemData(),
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
        $model = $this->findModel($id);
        if($model->create_by == \Yii::$app->user->id){
            if(!($model->getIsStatusDefault() || $model->getIsStatusAdjusimenting()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        }else {
            throw new NotAcceptableHttpException('无权限操作！');
        }
       
        //获取过滤的课程
        $courses = ArrayHelper::merge([$model->course_id => $model->course->name], ArrayHelper::map($this->getCourses($model->item_child_id), 'id', 'name'));
        
        if ($model->load(Yii::$app->request->post())) {
             DemandAction::getInstance()->DemandUpdateTask($model, Yii::$app->request->post());
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'itemTypes' => $this->getItemType(),
                'items' => $this->getCollegesForSelect(),
                'itemChilds' => $this->getChildren($model->item_id),
                'courses' => $courses,
                'teachers' => $this->getExpert(),
                'teams' => $this->getUserTeam(TeamCategory::TYPE_PRODUCT_CENTER),
                'annexs' => $this->getDemandTaskAnnexs($model->id),
                'workitmType' => DemandTool::getInstance()->getDemandWorkitemTypeData($model->id),
                'workitem' => DemandTool::getInstance()->getDemandWorkitemData($model->id),
            ]);
        }
    }

    /**
     * 承接任务操作
     * @param integer $id
     * @return type
     * @throws NotAcceptableHttpException
     */
    public function actionUndertake($id)
    {
        $model = $this->findModel($id);
        /* @var $rbacManager RbacManager */  
        $rbacManager = Yii::$app->authManager;
        $isUndertaker = $rbacManager->isRole(RbacName::ROLE_COMMON_COURSE_DEV_MANAGER, Yii::$app->user->id);
        
        if(!($isUndertaker && $model->getIsStatusUndertake()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        
        if ($model->load(Yii::$app->request->post())) {
            DemandAction::getInstance()->DemandUndertakeTask($model);
            return $this->redirect(['view', 'id' => $model->id, 'develop' => 1]);
        } else {
            return $this->renderAjax('_undertake', [
                'model' => $model,
                'teams' => $this->getUserTeam(TeamCategory::TYPE_CCOA_DEV_TEAM),
            ]);
        }
    }
    
    /**
     * 待确定任务操作
     * @param type $id
     * @return type
     * @throws NotAcceptableHttpException
     */
    public function actionWaitConfirm($id)
    {
        $model = $this->findModel($id);
       
        if(!($model->undertake_person == Yii::$app->user->id && $model->getIsStatusWaitConfirm()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        
        if ($model->load(Yii::$app->request->post())) {
            DemandAction::getInstance()->DemandWaitConfirm($model);
            return $this->redirect(['index']);
        } else {
            return $this->renderAjax('_wait_confirm', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * 取消任务
     * @param integer $id
     * @return type
     * @throws NotAcceptableHttpException
     */
    public function actionCancel($id)
    {
        $model = $this->findModel($id);
        if(!($model->create_by == Yii::$app->user->id && $model->getIsStatusDevelopingBefore()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        $oldStatus = $model->status;
        $post = Yii::$app->request->post();
        $cancel = ArrayHelper::getValue($post, 'reason');

        if ($model->load($post)){
            DemandAction::getInstance()->DemandCancelTask($model, $oldStatus, $cancel);
            return $this->redirect(['index']);
        } else {
            return $this->renderPartial('_cancel', [
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
     * 查看开发
     * 根据demand_task_id查找对应的开发任务，再跳转到开发任务详情页
     * @param integer $id   任务ID
     */
    public function actionCheckDev($id){
        $model = $this->findModel($id);
        if($model){
            //查找对应【开发】任务ID
            $course_task_model = \common\models\teamwork\CourseManage::find()
                    ->where(['demand_task_id' => $id])
                    ->one();
            if($course_task_model){
                return $this->redirect(['/teamwork/course/view','id'=>$course_task_model->id]);
            }
        }
        throw new NotFoundHttpException(Yii::t('rcoa', 'The requested page does not exist.'));
    }
    
    /**
     * 获取课程
     * @param integer $id                       专业/工种ID
     * @return type JSON
     */
    public function actionSearchSelect($id)
    {
        $errors = [];
        $items = [];
        
        try
        {
            Yii::$app->getResponse()->format = 'json';
            $items = $this->getCourses($id); 
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
     * 检测课程是否唯一
     * @param integer $id                        
     * @return type JSON
     */
    public function actionCheckUnique($id)
    { 
        $type = 1;
        $message = '所选的课程已经被选择了。';
        $errors = [];
        try
        {
            Yii::$app->getResponse()->format = 'json';
            $result = (new Query())->select(['course_id'])
                ->from(DemandTask::tableName())
                ->where(['and', ['course_id'=> $id], ['!=', 'status', DemandTask::STATUS_CANCEL]])
                ->one();   
            if($result == null){
                $type = 0;
                $message = '';
            }
        } catch (Exception $ex) {
            $errors [] = $ex->getMessage();
        }
        
        return [
            'type'=> $type,
            'message' => $message,
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
            throw new NotFoundHttpException(Yii::t('rcoa', 'The requested page does not exist.'));
        }
    }    

    /**
     * 获取行业
     * @return array
     */
    public function getItemType()
    {
        $itemType = ItemType::find()->with('itemManages')->all();
        return ArrayHelper::map($itemType, 'id', 'name');
    }
    
    /**
     * 获取层次/类型
     * @return array
     */
    public function getCollegesForSelect()
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getColleges(), 'id', 'name');
    }
    
    /**
     * 获取专业/工种
     * @param integer $itemId              
     * @return array
     */
    protected function getChildren($itemId)
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getChildren($itemId), 'id', 'name');
    }
    
    /**
     * 获取过滤的课程
     * @param integer $itemChildId
     * @return array
     */
    public function getCourses($itemChildId)
    {
        $taskCourse = (new Query())->select('course_id')
            ->from(DemandTask::tableName())
            ->where(['and', ['item_child_id'=> $itemChildId], ['!=', 'status', DemandTask::STATUS_CANCEL]]);
        return (new Query())->from(Item::tableName())
            ->where(['and', ['parent_id'=> $itemChildId], ['NOT IN', 'id', $taskCourse]])
            ->all(); 
    }
    
    /**
     * 获取专家库
     * @return array
     */
    public function getExpert(){
        $expert = Expert::find()->with('user')->all();
        return ArrayHelper::map($expert, 'u_id','user.nickname');
    }
    
    /**
     * 获取所有需求任务附件
     * @param integer $taskId                       任务id
     * @return array
     */
    public function getDemandTaskAnnexs($taskId)
    {
        return (new Query())
            ->from(DemandTaskAnnex::tableName())
            ->where(['task_id' => $taskId])
            ->all();
    }
    
    /**
     * 获取用户所在团队
     * @param TeamMemberTool $_tmTool
     * @param string $teamCategory              团队分类
     * @return array
     */
    public function getUserTeam($teamCategory)
    {
        $_tmTool = TeamMemberTool::getInstance();
        $teams = $_tmTool->getUserTeam(Yii::$app->user->id, $teamCategory);
        
        return ArrayHelper::map($teams, 'id', 'name');
    }
    
    /**
     * 获取所有开发团队
     * @return array
     */
    public function getDevelopTeams()
    {
        $_tmTool = TeamMemberTool::getInstance();
        $teams = $_tmTool->getTeamsByCategoryId(TeamCategory::TYPE_CCOA_DEV_TEAM);
        ArrayHelper::multisort($teams, 'index', SORT_ASC);  
        
        return ArrayHelper::map($teams, 'id', 'name');
    }
    
    /**
     * 获取所有任务创建者和承接人
     * @return array
     */
    public function getTaskCreatorUndertaker()
    {
        $results = (new Query())
                ->select([
                    "CONCAT(DemandTask.create_by, '_', CreateBy.nickname) AS create_by",
                    "CONCAT(DemandTask.undertake_person, '_', Undertaker.nickname) AS undertaker",
                ])
                ->from(['DemandTask' => DemandTask::tableName()])
                ->leftJoin(['CreateBy' => User::tableName()], 'CreateBy.id = DemandTask.create_by')
                ->leftJoin(['Undertaker' => User::tableName()], 'Undertaker.id = DemandTask.undertake_person')
                ->all();
        
        $createBys = [];
        $undertakers = [];
        foreach ($results as $item) {
            $createBys[explode('_', $item['create_by'])[0]] = isset(explode('_', $item['create_by'])[1]) ? explode('_', $item['create_by'])[1] : '';
            $undertakers[explode('_', $item['undertaker'])[0]] = isset(explode('_', $item['undertaker'])[1]) ? explode('_', $item['undertaker'])[1] : '';
        }
        
        return [
            'createBy' => array_filter($createBys),
            'undertaker' => array_filter($undertakers)
        ];
    }
    
    /**
     * 获取所有课程产品总额
     * @return  array
     */
    public function getProductTotal()
    {
        /* @var $dtQuery DemandQuery */
        $dtQuery = DemandQuery::getInstance();
        /* @var $results ActiveQuery */
        $results = $dtQuery->findProductTotal();
        $results->select(['Task_product.task_id', 'SUM(Product.unit_price * Task_product.number) AS totals']);
        $results->groupBy('Task_product.task_id');
        
        return ArrayHelper::map($results->all(), 'task_id', 'totals');
    }
 
}
