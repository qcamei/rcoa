<?php

namespace frontend\modules\need\controllers;

use common\models\need\NeedAttachments;
use common\models\need\NeedTask;
use common\models\need\searchs\NeedTaskSearch;
use common\models\RecentContacts;
use common\models\User;
use common\modules\webuploader\models\Uploadfile;
use frontend\modules\need\utils\ActionUtils;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TaskController implements the CRUD actions for NeedTask model.
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
                'class' => VerbFilter::class,
                'actions' => [
                    //'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ]
        ];
    }

    /**
     * Lists all NeedTask models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NeedTaskSearch();
        $results = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $results['dataProvider'],
            'totalCount' => $results['dataProvider']->totalCount,
            'isHasReceive' => $this->getIsHasReceiveToDeveloper(),                                                                                                                                                                                                                                                                               
            //下拉选择
            'allBusiness' => $this->getBusiness(),
            'allLayer' => $this->getCollegesForSelect(),
            'allProfession' => $this->getChildren(ArrayHelper::getValue(Yii::$app->request->queryParams, 'NeedTaskSearch.layer_id')),
            'allCourse' => $this->getChildren(ArrayHelper::getValue(Yii::$app->request->queryParams, 'NeedTaskSearch.profession_id')),
            'allCreatedBy' => $this->getUsersGroup($results['created_by']),
            'allReceiveBy' => $this->getUsersGroup($results['receive_by']),
        ]);
    }

    /**
     * Displays a single NeedTask model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'isHasReceive' => $this->getIsHasReceiveToDeveloper()
        ]);
    }

    /**
     * Creates a new NeedTask model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        $model = NeedTask::findOne(ArrayHelper::getValue($post, 'NeedTask.id'));
        if($model === null){
            $model = new NeedTask([
                'id' => md5(time() . rand(1, 99999999)),
                'company_id' => Yii::$app->user->identity->company_id,
                'created_by' => Yii::$app->user->id,
            ]);
            $model->scenario = NeedTask::SCENARIO_TEMP_CREATE;
            $model->save();
            $model->scenario = NeedTask::SCENARIO_DEFAULT;
            $model->loadDefaultValues();
        }
        
        if ($model->load($post)) {
            ActionUtils::getInstance()->CreateNeedTask($model, $post);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $model->need_time = date('Y-m-d H:i', strtotime('+ 3 day'));
        return $this->render('create', [
            'model' => $model,
            'allBusiness' => $this->getBusiness(),
            'allLayer' => $this->getCollegesForSelect(),
            'allProfession' => [],
            'allCourse' => [],
            'allAuditBy' => $this->getUsersGroup(),
            'attFiles' => [],
        ]);
    }

    /**
     * Updates an existing NeedTask model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($model->created_by == \Yii::$app->user->id){
            if(!($model->getIsCreateing() || $model->getIsChangeAudit() || $model->getIsWaitReceive())){
                throw new NotFoundHttpException('该任务为' . $model->getStatusName());
            }
            if($model->is_del){
                throw new NotFoundHttpException('该任务已取消');
            }
        }else{
            throw new NotFoundHttpException('无权限访问');
        }
       
        if ($model->load(Yii::$app->request->post())) {
            ActionUtils::getInstance()->UpdateNeedTask($model, Yii::$app->request->post());
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        $model->need_time = date('Y-m-d H:i', $model->need_time);
        return $this->render('update', [
            'model' => $model,
            'allBusiness' => $this->getBusiness(),
            'allLayer' => $this->getCollegesForSelect(),
            'allProfession' => $this->getChildren($model->layer_id),
            'allCourse' => $this->getCourses($model, $model->profession_id),
            'allAuditBy' => $this->getUsersGroup(),
            'attFiles' => $this->getUploadfileByAttachment($id),
        ]);
    }

    /**
     * 提交审核
     * @param string $id
     * @return mixed
     */
    public function actionSubmit($id)
    {
        $model = $this->findModel($id);
        
        if($model->created_by == \Yii::$app->user->id){
            if(!($model->getIsCreateing() || $model->getIsChangeAudit())){
                throw new NotFoundHttpException('该任务为' . $model->getStatusName());
            }
            if($model->is_del){
                throw new NotFoundHttpException('该任务已取消');
            }
        }else{
            throw new NotFoundHttpException('无权限访问');
        }
        
        ActionUtils::getInstance()->SubmitAuditNeedTask($model);
        return $this->redirect(['view', 'id' => $model->id]);
    }
    
    /**
     * 取消审核
     * @param string $id
     * @return mixed
     */
    public function actionCancel($id)
    {
        $model = $this->findModel($id);
        
        if($model->created_by == \Yii::$app->user->id){
            if(!$model->getIsAuditing()){
                throw new NotFoundHttpException('该任务为' . $model->getStatusName());
            }
            if($model->is_del){
                throw new NotFoundHttpException('该任务已取消');
            }
        }else{
            throw new NotFoundHttpException('无权限访问');
        }
        
        ActionUtils::getInstance()->CancelAuditNeedTask($model);
        return $this->redirect(['view', 'id' => $model->id]);
    }
    
    /**
     * 审核任务
     * @param string $id
     * @return mixed
     */
    public function actionAudit($id)
    {
        $model = $this->findModel($id);
        
        if($model->audit_by == \Yii::$app->user->id){
            if(!$model->getIsAuditing()){
                throw new NotFoundHttpException('该任务为' . $model->getStatusName());
            }
            if($model->is_del){
                throw new NotFoundHttpException('该任务已取消');
            }
        }else{
            throw new NotFoundHttpException('无权限访问');
        }
        
        if(\Yii::$app->request->isPost) {
            ActionUtils::getInstance()->AuditNeedTask($model, Yii::$app->request->post());
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        return $this->renderAjax('audit');
    }
    
    /**
     * 承接任务
     * @param string $id
     * @return mixed
     */
    public function actionReceive($id)
    {
        $model = $this->findModel($id);
        
//        if($this->getIsHasReceiveToDeveloper()){
            if(!$model->getIsWaitReceive()){
                throw new NotFoundHttpException('该任务为' . $model->getStatusName());
            }
            if($model->is_del){
                throw new NotFoundHttpException('该任务已取消');
            }
//        }else{
//            throw new NotFoundHttpException('无权限访问');
//        }
        
        ActionUtils::getInstance()->ReceiveNeedTask($model);
        return $this->redirect(['view', 'id' => $model->id]);
    }
    
    /**
     * 开始制作
     * @param string $id
     * @return mixed
     */
    public function actionStart($id)
    {
        $model = $this->findModel($id);
        
        if($model->receive_by == \Yii::$app->user->id){
            if(!$model->getIsWaitStart()){
                throw new NotFoundHttpException('该任务为' . $model->getStatusName());
            }
            if($model->is_del){
                throw new NotFoundHttpException('该任务已取消');
            }
        }else{
            throw new NotFoundHttpException('无权限访问');
        }
        
        ActionUtils::getInstance()->StartMakeNeedTask($model);
        return $this->redirect(['view', 'id' => $model->id]);
    }
    
    /**
     * 转让任务
     * @param string $id
     * @return mixed
     */
    public function actionTransfer($id)
    {
        $model = $this->findModel($id);
        
        if($model->receive_by == \Yii::$app->user->id){
            if(!$model->getIsWaitStart()){
                throw new NotFoundHttpException('该任务为' . $model->getStatusName());
            }
            if($model->is_del){
                throw new NotFoundHttpException('该任务已取消');
            }
        }else{
            throw new NotFoundHttpException('无权限访问');
        }
        
        if($model->load(Yii::$app->request->post())) {
            ActionUtils::getInstance()->TransferNeedTask($model, Yii::$app->request->post());
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        return $this->renderAjax('transfer', [
            'model' => $model,
            'receiveBys' => $this->getUsersGroup(),
            'userRecentContacts' => $this->getUserRecentContacts(),
        ]);
    }
    
    /**
     * 验收任务
     * @param string $id
     * @return mixed
     */
    public function actionCheck($id)
    {
        $model = $this->findModel($id);
        
        if($model->created_by == \Yii::$app->user->id){
            if(!$model->getIsChecking()){
                throw new NotFoundHttpException('该任务为' . $model->getStatusName());
            }
            if($model->is_del){
                throw new NotFoundHttpException('该任务已取消');
            }
        }else{
            throw new NotFoundHttpException('无权限访问');
        }
        
        if(\Yii::$app->request->isPost) {
            ActionUtils::getInstance()->CheckNeedTask($model, Yii::$app->request->post());
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        return $this->renderAjax('check', [
            'model' => $model,
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $model->contents,
            ]),
        ]);
    }
    
    /**
     * Deletes an existing NeedTask model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if(!$model->is_del){
            if($model->status < NeedTask::STATUS_WAITSTART){
                if($model->created_by != \Yii::$app->user->id){
                    throw new NotFoundHttpException('无权限访问');
                }
            }
            if($model->status > NeedTask::STATUS_WAITRECEIVE){
                if($model->receive_by != \Yii::$app->user->id){
                    throw new NotFoundHttpException('无权限访问');
                }
            }
        }else{
            throw new NotFoundHttpException('该任务已取消');
        }
        
        if(Yii::$app->request->isPost){
            ActionUtils::getInstance()->DeleteNeedTask($model, Yii::$app->request->post());
            return $this->redirect(['index']);
        }
        
        return $this->renderAjax('delete');
    }

    /**
     * Finds the NeedTask model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return NeedTask the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NeedTask::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
    /**
     * 获取行业
     * @return array
     */
    protected function getBusiness()
    {
        $itemType = ItemType::find()->with('itemManages')->all();
        return ArrayHelper::map($itemType, 'id', 'name');
    }
    
    /**
     * 获取层次/类型
     * @return array
     */
    protected function getCollegesForSelect()
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
     * 获取过滤后的课程
     * @param NeedTask $model
     * @param integer $itemChildId
     * @return array
     */
    protected function getCourses($model, $itemChildId)
    {
        //查询需求任务课程
        $taskCourse = (new Query())->select('course_id')->from(['NeedTask' => NeedTask::tableName()])
            ->where(['NeedTask.profession_id'=> $itemChildId, 'NeedTask.is_del' => 0]);
        //查询基础数据课程
        $itemCourse = (new Query())->from(Item::tableName())
            ->where(['and', ['parent_id'=> $itemChildId], ['NOT IN', 'id', $taskCourse]]);
        return ArrayHelper::merge([$model->course_id => $model->course->name], 
                ArrayHelper::map($itemCourse->all(), 'id', 'name'));
    }
    
    /**
     * 获取所有用户
     * @param string $user_id
     * @return array
     */
    protected function getUsersGroup($user_id = null)
    {
        $users = User::find()
            ->where(['status' => 10, 'company_id' => Yii::$app->user->identity->company_id])
            ->andFilterWhere(['id' => is_array($user_id) ? array_unique($user_id) : $user_id]);
    
        return ArrayHelper::map($users->all(), 'id', 'nickname');
    }

    /**
     * 获取已上传的附件
     * @param string $need_task_id
     * @return array
     */
    protected function getUploadfileByAttachment($need_task_id)
    {
        $uploadFile = (new Query());
        $uploadFile->select(['Uploadfile.id', 'Uploadfile.name', 'Uploadfile.size']);
        $uploadFile->from(['Attachment' => NeedAttachments::tableName()]);
        $uploadFile->leftJoin(['Uploadfile' => Uploadfile::tableName()], 'Uploadfile.id = Attachment.upload_file_id');
        $uploadFile->where(['Attachment.need_task_id' => $need_task_id]);
        $uploadFile->andWhere(['Attachment.is_del' => 0, 'Uploadfile.is_del' => 0]);
        
        $hasFile = $uploadFile->all();
        if($hasFile !== null){
            return $hasFile;
        }
    }
    
    /**
     * 获取用户关联的最近联系人
     * @return array
     */
    protected function getUserRecentContacts()
    {
        $query = (new Query())->select(['User.id','User.nickname','User.avatar'])
            ->from(['RecentContacts'=>RecentContacts::tableName()]);
        
        $query->leftJoin(['User'=> User::tableName()],'User.id = RecentContacts.contacts_id');
        $query->where(['user_id'=> \Yii::$app->user->id]);
        $query->orderBy(['RecentContacts.updated_at' => SORT_DESC]);
        
        return $query->limit(8)->all();
    }
    
    /**
     * 获取拥有承接权限的开发人员
     * @return boolean
     */
    protected function getIsHasReceiveToDeveloper()
    {
        $developer = ActionUtils::getHasReceiveToDeveloper();
        
        if(in_array(\Yii::$app->user->id, $developer['u_id'])){
            return true;
        }
        
        return false;
    }
}
