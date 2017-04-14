<?php

namespace frontend\modules\demand\controllers;

use common\config\AppGlobalVariables;
use common\models\demand\DemandDelivery;
use common\models\demand\DemandTask;
use common\models\demand\DemandTaskAnnex;
use common\models\expert\Expert;
use common\models\team\TeamCategory;
use common\wskeee\job\JobManager;
use frontend\modules\demand\utils\DemandQuery;
use frontend\modules\demand\utils\DemandTool;
use frontend\modules\teamwork\utils\TeamworkTool;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\ActiveQuery;
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
    public function actionIndex($status = DemandTask::STATUS_DEFAULT, $create_by = null, $undertake_person = null, $auditor = null,
            $item_type_id = null, $item_id = null, $item_child_id = null, $course_id = null,
            $team_id = null, $keyword = null, $time = null, $mark = null,$page = null)
    {
        $page = $page == null ? 0 : $page-1; 
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        /* @var $dtTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $query = $dtTool->getDemandTaskInfo($id = null, $status, $create_by, $undertake_person, $auditor, $item_type_id, $item_id, $item_child_id, $course_id, $team_id, $keyword, $time, $mark);
        $count = $query->count();
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query->addSelect([
                'Demand_task.item_type_id', 'Demand_task.item_id', 'Demand_task.item_child_id', 'Demand_task.course_id',
                'Demand_task.team_id', 'Demand_task.undertake_person', 'Demand_task.create_by', 
                'Demand_task.plan_check_harvest_time', 'Demand_task.status', 'Demand_task.mode', 'Demand_task.progress'
            ])->limit(20)->offset($page*20)->all(),
        ]);
        $taskIds = ArrayHelper::getColumn($dataProvider->allModels, 'id');
        $taskStatus = ArrayHelper::map($dataProvider->allModels, 'id', 'status');
       
        return $this->render('index', [
            'twTool' => $twTool,
            'dataProvider' => $dataProvider,
            'operation' => $dtTool->getIsBelongToOwnOperate($taskIds, $taskStatus),
            'count' => $count,
            'itemType' => $this->getItemType(),
            'items' => $this->getCollegesForSelect(),
            'itemChild' => empty($mark) ? [] : $this->getChildren($item_id),
            'course' => empty($mark) ? [] : $this->getChildren($item_child_id),
            'team' => $this->getTeam(),
            'createBys' => $this->getCreateBys(),
            'undertakePersons' => $this->getUndertakePersons(),
            'productTotal' => $this->getProductTotal(),
            //搜索默认字段值
            'itemTypeId' => $item_type_id,
            'itemId' => $item_id,
            'itemChildId' => $item_child_id,
            'courseId' => $course_id,
            'keyword' => $keyword,
            'status' => $status,
            'createBy' => $create_by,
            'undertakePerson' => $undertake_person,
            'teamId' => $team_id,
            'time' => !empty($time) ? $time : null,
            'mark' => !empty($mark) ? $mark : 0,
        ]);
    }

    /**
     * Displays a single DemandTask model.
     * @param integer $id
     * @param integer $sign                     是否滚动到添加课程产品位置标识  1为是0为否（默认为0）
     * @param integer $develop                  是否现在就开始创建课程开发数据标识  1为是0为否（默认为0）
     * @return mixed
     */
    public function actionView($id, $sign = 0, $develop = 0)
    {
        $this->layout = '@app/views/layouts/main';
        $model = $this->findModel($id);
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        /* @var $dtTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /* @var $rbacManager RbacManager */  
        $rbacManager = \Yii::$app->authManager;
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
            'rbacManager' => $rbacManager,
            'annex' => $this->getAnnex($id),
            'sign' => $sign,
            'develop' => $develop,
            'dates' => $this->getDeliveryCreatedAt($id)
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
            return $this->redirect(['update', 'id' => $model->id, 'sign' => 1]);
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
     * 任务提交审核操作
     * @param integer $id
     * @return type
     * @throws NotAcceptableHttpException
     */
    public function actionSubmitCheck($id)
    {
        $model = $this->findModel($id);
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        if(!(\Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_CREATE) && $model->create_by == \Yii::$app->user->id))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->getIsStatusDefault())
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
                
        if ($model->load(Yii::$app->request->post())) {
            $dtTool->TaskSubmitCheck($model);
            return $this->redirect(['index','create_by' => Yii::$app->user->id, 
                    'undertake_person' => Yii::$app->user->id, 
                    'auditor' => Yii::$app->user->id
                ]);
        } else {
            return $this->renderPartial('submit_check', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DemandTask model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @param boolean $sign             标记：1为添加需求工作项数据，默认为0
     * @return mixed
     */
    public function actionUpdate($id, $sign = 0)
    {
        $this->layout = '@app/views/layouts/main';
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        
        if(!(\Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_UPDATE) && $model->create_by == \Yii::$app->user->id))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!($model->getIsStatusDefault() || $model->getIsStatusAdjusimenting()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        /* @var $dtTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $courses = $this->getCourses($model->item_child_id);
        
        if ($model->load($post)) {
            $dtTool->UpdateTask($model, $post);
            return $this->redirect(['view', 'id' => $model->id, 'sign' => 1]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'sign' => $sign,
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
        return $this->redirect(['index','create_by' => Yii::$app->user->id, 
            'undertake_person' => Yii::$app->user->id, 
            'auditor' => Yii::$app->user->id
        ]);
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
        $rbacManager = \Yii::$app->authManager;
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        /* @var $dtTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        if(!(\Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_UNDERTAKE) 
           && $rbacManager->isRole(RbacName::ROLE_DEMAND_UNDERTAKE_PERSON, \Yii::$app->user->id)))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->getIsStatusUndertake())
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        
        if ($model->load(Yii::$app->request->post())) {
            $dtTool->UndertakeTask($model);
            return $this->redirect(['view', 'id' => $model->id, 'develop' => 1]);
        } else {
            return $this->renderAjax('undertake', [
                'model' => $model,
                'team' => $twTool->getHotelTeam(),
                'developPrincipals' => $dtTool->getHotelTeamMemberId(),
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
        $model->status = DemandTask::STATUS_UPDATEING;
        $model->progress = DemandTask::$statusProgress[DemandTask::STATUS_UPDATEING];
        $model->reality_check_harvest_time = null;
        $dtTool->RecoveryTask($model);
        return $this->redirect(['view', 'id' => $model->id]);
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
        if(!\Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_CANCEL) && $model->create_by != \Yii::$app->user->id)
            throw new NotAcceptableHttpException('无权限操作！');
        if(!($model->getIsStatusCheck() || $model->getIsStatusUndertake()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName().'！');
        $oldStatus = $model->status;
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        $post = Yii::$app->request->post();
        $cancel = ArrayHelper::getValue($post, 'reason');

        if ($model->load($post)){
            $dtTool->CancelTask($model, $oldStatus, $cancel);
            return $this->redirect(['index','create_by' => Yii::$app->user->id, 
                'undertake_person' => Yii::$app->user->id, 
                'auditor' => Yii::$app->user->id,
            ]);
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
     
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/
    
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
                        ->where(['and', ['item_child_id'=> $id], ['!=', 'status', DemandTask::STATUS_CANCEL]]) : null;         
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
     * 检测课程是否唯一
     * @param type $id              任务ID
     * @return type JSON
     */
    public function actionCheckUnique($id = null)
    {
        Yii::$app->getResponse()->format = 'json';
        $courseId = ArrayHelper::getValue($post = Yii::$app->request->post(), 'DemandTask.course_id');
        $result = DemandTask::find()->select(['id', 'course_id'])  
                  ->where(['and', ['course_id'=> $courseId], ['!=', 'status', DemandTask::STATUS_CANCEL]])
                  ->andFilterWhere(['!=', 'id', $id])->all();         
        $errors = [];
        $message = '';
        $type = '';
        try
        {
            if(!empty($result)){
                $type = 1;
                $message = '所选的课程已经被选择了！';
            }else{
                $type = 0;
            }
        } catch (Exception $ex) {
            $errors [] = $ex->getMessage();
        }
        return [
            'types'=> $type,
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
        $existedCourses = DemandTask::find()
                ->where(['and', ['item_child_id' => $itemChildId], ['!=', 'status', DemandTask::STATUS_CANCEL]])->all();
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
    
    /**
     * 获取所有课程开发团队
     * @param integer $teamId      团队ID
     * @return array
     */
    public function getTeam()
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
        $createBys = $rbacManager->getItemUsers(RbacName::ROLE_DEMAND_PROMULGATOR);
        
        return ArrayHelper::map($createBys, 'id', 'nickname');
    }
    
    /**
     * 获取所有承接人
     * @return type
     */
    public function getUndertakePersons()
    {
        /* @var $rbacManager RbacManager */
        $rbacManager = Yii::$app->authManager;
        $undertakePersons = $rbacManager->getItemUsers(RbacName::ROLE_DEMAND_UNDERTAKE_PERSON);
        
        return ArrayHelper::map($undertakePersons, 'id', 'nickname');
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
    
    /**
     * 获取交付创建时间
     * @param integer $taskId
     */
    public function getDeliveryCreatedAt($taskId)
    {
        $dates = (new Query())
                ->select(['id', 'FROM_UNIXTIME(created_at, "%Y-%m-%d %H:%i:%s") AS date'])
                ->from(DemandDelivery::tableName())
                ->where(['demand_task_id' => $taskId])
                ->all();
        
        return ArrayHelper::map($dates, 'id', 'date');
    }
}
