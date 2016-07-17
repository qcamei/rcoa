<?php

namespace frontend\modules\shoot\controllers;

use common\models\expert\Expert;
use common\models\shoot\searchs\ShootBookdetailSearch;
use common\models\shoot\ShootBookdetail;
use common\models\shoot\ShootBookdetailRoleName;
use common\models\shoot\ShootSite;
use common\wskeee\job\JobManager;
use frontend\modules\shoot\BookdetailTool;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\ItemType;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use wskeee\utils\DateUtil;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * BookdetailController implements the CRUD actions for ShootBookdetail model.
 */

class BookdetailController extends Controller
{
    
    /** 设置delete方法的传值方式 */ 
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ], 
            'access' => [
                'class' => AccessControl::className(),
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
     * Lists all ShootBookdetail models.
     * @return mixed
     */
    public function actionIndex()
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        $date = isset(Yii::$app->request->queryParams['date']) ? 
            date('Y-m-d',strtotime(Yii::$app->request->queryParams['date'])) : date('Y-m-d');
        $se = DateUtil::getWeekSE($date);
        $site = !isset(Yii::$app->request->queryParams['site']) ? :
            Yii::$app->request->queryParams['site'] ;
        
        $dataProvider = ShootBookdetailSearch::searchWeek($site, $se);
        
        return $this->render('index', [
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $dataProvider,
                'sort' => [
                    'attributes' => ['book_time'],
                ],
                'pagination' => [
                    'pageSize' =>21,
                ],
            ]),
            
            'date' => $date,
            'site' => $site,
            'sites' => $this->getSiteForSelect(),
            'prevWeek' => DateUtil::getWeekSE($date,-1)['start'],
            'nextWeek' => DateUtil::getWeekSE($date,1)['start'],
        ]);
        
       
    }

    /**
     * Creates a new ShootBookdetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (!Yii::$app->user->can(RbacName::PERMSSIONT_SHOOT_CREATE))
            throw new UnauthorizedHttpException('无权操作！');
        $post = Yii::$app->getRequest()->getQueryParams();
        $body = Yii::$app->getRequest()->getBodyParams();
        
        //全并且get参数与post参数
        $post = ArrayHelper::merge($post, $body);
        
        /* @var $bookdetailTool BookdetailTool */
        $bookdetailTool = Yii::$app->get('bookdetailTool');
       
        /** 先查找对应数据（临时预约锁定的数据）找不到再新建数据 */
        if (isset($post['b_id']))
            $model = ShootBookdetail::findOne($post['b_id']);
        else  
            $this->getIsNewBookdetail($post); //判断同一时间段是否存在相同的预约

        if (!isset($model)) {
            $model = new ShootBookdetail();
            $model->loadDefaultValues();
        } else if($model->getIsBooking() && ($model->create_by && $model->create_by != Yii::$app->user->id)) 
            throw new NotAcceptableHttpException('正在预约中！');
        
        if ($model->load(Yii::$app->request->post())) {
            $model->u_contacter = $post['ShootBookdetail']['u_contacter'][0];
            $model->status = ShootBookdetail::STATUS_ASSIGN ;
            //判断两个数组是否存在交集
            $isIntersection = $this->isTwoArrayIntersection($model, RbacName::ROLE_CONTACT, $post['ShootBookdetail']['u_contacter']); 
            //保存预约
            $bookdetailTool->saveNewBookdetail($model, $post['ShootBookdetail']['u_contacter'], $isIntersection); 
            
            return $this->redirect([ 'index', 
                'date' => date('Y-m-d', $model->book_time), 
                'b_id' => $model->id, 
                'site'=> $model->site_id
            ]);
        } else {
            //设置创建预约拍摄时默认属性
            $bookdetailTool->setNewBookdetailProperty($model, $post); 
            //已指派了的接洽人
            $alreadyContactsArray = $this->getIsRoleNames(RbacName::ROLE_CONTACT, $model->book_time, $model->index); 
            //所有接洽人
            $allContactsArray = $this->getRoleToUsers(RbacName::ROLE_CONTACT); 
            
            return $this->render('create', [
                'model' => $model,
                'bookers' => $this->getRoleToUsers(RbacName::ROLE_WD),   //编导
                'contacts' => array_diff($allContactsArray, $alreadyContactsArray), //接洽人
                'teachers' => $this->getExpert(),
                'colleges' => $this->getCollegesForSelect(),
                'projects' => [],
                'courses' => [],
                'business' => $this->getBusiness(),
            ]);
        }
    }
    
     /**
     * 退出任务创建，清除锁定
     * @param 退出任务的时间 $date
     * @param 任务id $b_id
     */
    public function actionExitCreate($date,$b_id)
    {
        $model = $this->findModel($b_id);
        if($model != null && $model->getIsBooking() && $model->create_by && $model->create_by == Yii::$app->user->id)
        {
            $model->setScenario(ShootBookdetail::SCENARIO_TEMP_CREATE);
            $model->status = ShootBookdetail::STATUS_DEFAULT;
            $model->save();
        }
        
        $this->redirect(['index','date'=>$date,'b_id'=>$b_id, 'site'=>$model->site_id]);
    }
    
    /**
     * Displays a single ShootBookdetail model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        //设置用户对通知已读
        $jobManager->setNotificationHasReady(2, Yii::$app->user->id, $id);  
       
        if(!$model->getIsAssign() && !$model->getIsAppraise()){
            //取消用户与任务通知的关联
            $jobManager->cancelNotification(2, $model->id, Yii::$app->user->id);  
        }
        
        //被指派了的摄影师
        $alreadyShootMansArray = $this->getIsRoleNames(RbacName::ROLE_SHOOT_MAN, $model->book_time, $model->index); 
        //所有摄影师
        $allShootMansArray = $this->getRoleToUsers(RbacName::ROLE_SHOOT_MAN); 
        /** 修改时设置Select2 value值*/
        $assignedShootMans = $this->getShootBookdetailRoleNames($id, RbacName::ROLE_SHOOT_MAN); //已经被指派的摄影师
        $shootMansKey = [];
        foreach ($assignedShootMans as $key => $value)
            $shootMansKey[] = $key;
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'reloadShootMans' =>$this->getReloadRoleNames($id, RbacName::ROLE_SHOOT_MAN),
            'reloadContacts' =>$this->getReloadRoleNames($id, RbacName::ROLE_CONTACT),
            'assignedShootMans' => $assignedShootMans,
            'shootMansKey' => $shootMansKey,
            'shootmans' => $this->isRole(RbacName::ROLE_SHOOT_LEADER) ?
                    array_diff($allShootMansArray, $alreadyShootMansArray) : [],
        ]);
    }
    
    /**
     * 指派摄影师
     * @param int $id           任务id
     * @param int $shoot_man_id 指派摄影师id
     */
    public function actionAssign($id)
    {
        $post = Yii::$app->getRequest()->getBodyParams();
        $model = $this->findModel($id);
        if(!$model->canAssign() && !$model->getIsAssign())
            throw new NotAcceptableHttpException('该任务'.$model->getStatusName());
        /* @var $bookdetailTool BookdetailTool */
        $bookdetailTool = Yii::$app->get('bookdetailTool');
        $oldShootMan = $model->u_shoot_man;
        $model->u_shoot_man = $post['shoot_man'][0];
        if(!empty($model->u_shoot_man))
           $model->status = $model::STATUS_SHOOTING;
        $assignedShootMans = $this->getShootBookdetailRoleNames($id, RbacName::ROLE_SHOOT_MAN);
        $isIntersection = $this->isTwoArrayIntersection($model, RbacName::ROLE_SHOOT_MAN, $post['shoot_man']);
        
        $bookdetailTool->saveAssignTask($model, $oldShootMan, $assignedShootMans, $post['shoot_man'], $isIntersection);
        
        $this->redirect(['index',
            'date' => date('Y-m-d', $model->book_time), 
            'b_id' => $model->id, 
            'site'=> $model->site_id
        ]);
    }
    
    /**
     * Updates an existing ShootBookdetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $post = Yii::$app->getRequest()->getBodyParams();
        $model = $this->findModel($id);
        if(!$model->canEdit() && !$model->getIsAssign())
            throw new NotAcceptableHttpException('该任务'.$model->getStatusName());
        /* @var $bookdetailTool BookdetailTool */
        $bookdetailTool = Yii::$app->get('bookdetailTool');
        /** 修改时设置Select2 value值*/
        $alreadyContacts = $this->getShootBookdetailRoleNames($id, RbacName::ROLE_CONTACT);
        $contacts = [];
        foreach ($alreadyContacts as $key => $value)
            $contacts[] = (string)$key;
        
        if ($model->load(Yii::$app->request->post())) {
            $model->u_contacter = $post['ShootBookdetail']['u_contacter'][0];
            $model->status = ShootBookdetail::STATUS_ASSIGN ;
            $isIntersection = $this->isTwoArrayIntersection($model, RbacName::ROLE_CONTACT, $post['ShootBookdetail']['u_contacter']);
            
            $bookdetailTool->saveUpdateTask($model, $post['ShootBookdetail']['u_contacter'], $contacts, $isIntersection);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            //已指派了的接洽人
            $alreadyContactsArray = $this->getIsRoleNames(RbacName::ROLE_CONTACT, $model->book_time, $model->index); 
            //所有接洽人
            $allContactsArray = $this->getRoleToUsers(RbacName::ROLE_CONTACT); 
            $contactsKey = [];
            foreach ($alreadyContacts as $key => $value)
                $contactsKey[] = $key;
            $model->u_contacter = $contactsKey;
            return $this->render('update', [
                'model' => $model,
                'bookers' => $this->getRoleToUsers(RbacName::ROLE_WD),   //编导
                'contacts' => array_diff($allContactsArray,$alreadyContactsArray), //接洽人
                'alreadyContacts' => $alreadyContacts,
                'contactsKey' => $contactsKey,
                'teachers' => $this->getExpert(),
                'colleges' => $this->getCollegesForSelect(),
                'projects' => $this->getFwItemForSelect($model->fw_college),
                'courses' => $this->getFwItemForSelect($model->fw_project),
                'business' => $this->getBusiness(),
            ]);
        }
    }
    
    /**
     * 取消任务
     * @param type $id
     */
    public function actionCancel($id)
    {   
        $model = $this->findModel($id);
        /* @var $bookdetailTool BookdetailTool */
        $bookdetailTool = Yii::$app->get('bookdetailTool');
        if(!$model->getIsAssign() && !Yii::$app->user->can(RbacName::PERMSSIONT_SHOOT_CANCEL, ['job'=>$model]))
            throw new NotAcceptableHttpException('该任务'.$model->getStatusName());
        else{
            $model->status =  $model::STATUS_CANCEL;
            $u_contacter = $this->getShootBookdetailRoleNames($id, RbacName::ROLE_CONTACT);
            $u_shoot_man = $this->getShootBookdetailRoleNames($id, RbacName::ROLE_SHOOT_MAN);
            //全并两个数组的值
            $roleNmaeAll = ArrayHelper::merge($u_contacter, $u_shoot_man);

            $bookdetailTool->saveCancelTask($model, $roleNmaeAll);
        }
        return $this->redirect(['index', 'date' => date('Y-m-d', $model->book_time), 'b_id' => $model->id, 'site'=> $model->site_id]);
    }

    /**
     * Deletes an existing ShootBookdetail model.
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
     * Finds the ShootBookdetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ShootBookdetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = ShootBookdetail::findOne($id);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('请求的页面不存在。');
        }
    }
    
    /**
     * 读取拍摄任务已指派的角色数据
     * @param type $b_id    拍摄任务id
     * @return type
     * @throws NotFoundHttpException
     */
    protected function findShootBookdetailRoleName($b_id){
        $roleName = ShootBookdetailRoleName::findOne($b_id);
        if ($roleName !== null) {
            return $roleName;
        } else {
            throw new NotFoundHttpException('请求的页面不存在');
        }
    }
    
    /**
     * 获取项目
     * @return type
     */
    protected function getCollegesForSelect()
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getColleges(), 'id', 'name');
    }
    
    
    /**
     * 获取子项目
     * @param int $itemId
     */
    protected function getFwItemForSelect($itemId)
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getChildren($itemId), 'id', 'name');
    }  
    
    /**
     * 获取角色的用户
     * @param string $roleName 角色
     */
    protected function getRoleToUsers($roleName)
    {
        /* @var $rbacManager RbacManager */
        $rbacManager = Yii::$app->authManager;
        return ArrayHelper::map($rbacManager->getItemUsers($roleName), 'id', 'nickname');
    }
    
    /**
     * 场地下拉数据
     */
    protected  function getSiteForSelect()
    {
        $sites = ShootSite::find()
                ->all();
        return ArrayHelper::map($sites, 'id', 'name');
    }
    /**
     * 获取专家库
     * @return type
     */
    protected function getExpert(){
        $expert = Expert::find()
                ->with('user') 
                ->all();
         return ArrayHelper::map($expert, 'u_id','user.nickname');
    }
    /**
     * 获取类别
     * @return type
     */
    protected function getBusiness(){
        $business = ItemType::find()->all();
        return ArrayHelper::map($business, 'id','name');
    }

    /**
      * 获取拍摄任务已指派角色
      * @param type $b_id 任务id
      * @return type
      */
    protected function getShootBookdetailRoleNames($b_id, $roleName){
        $roleNames = ShootBookdetailRoleName::find()
                ->where(['b_id' => $b_id, 'role_name' => $roleName])
                ->orderBy('primary_foreign DESC')
                ->with('u') 
                ->all();
        return ArrayHelper::map($roleNames, 'u_id','u.nickname');
    }
    
    /**
     * 获取拍摄任务所有已指派的角色信息
     * 重组角色为一个新的数组
     * @param type $b_id
     * @param type $roleName 角色名
     */
    protected function getReloadRoleNames($b_id, $roleName){
        $roleNames = ShootBookdetailRoleName::find()
                    ->where(['b_id'=> $b_id, 'role_name' => $roleName,])
                    ->orderBy('primary_foreign DESC')
                    ->all();
        $newRoleNames = [];
        foreach ($roleNames as $roleNamesValue){
            $newRoleNames[] = $roleNamesValue->primary_foreign == 1 ? 
                           '<span style="color:blue;">' . $roleNamesValue->u->nickname . '( '.$roleNamesValue->u->phone.' )</span>' :   //设置主角色
                           $roleNamesValue->u->nickname;
        }
        return $newRoleNames;
    }
    
     /**
     * 判断同一时间段是否存在相同的预约
     * @param type $post
     * @throws NotAcceptableHttpException
     */
    public function getIsNewBookdetail($post){
        $query = ShootBookdetail::find()
                ->where([
                    'site_id'=>$post['site_id'],  
                    'index'=>$post['index'],  
                    'status'=>ShootBookdetail::STATUS_BOOKING,
                ])
                ->andWhere('book_time >=' . strtotime(date('Y-m-d', $post['book_time'])))  
                ->andWhere('book_time <=' . strtotime(date('Y-m-d',strtotime("+1 days",$post['book_time']))))  
                ->all();  
        if(count ($query) > 0)  
            throw new NotAcceptableHttpException('正在预约中！');  
    }
    
    /**
     * 同一时间段的拍摄任务是否存在已被指派过的角色
     * @param type $roleName  角色
     * @param type $bookTime   拍摄任务时间
     * @param type $index      顺序
     * @return type
     */
    protected function getIsRoleNames($roleName, $bookTime, $index){
        
        $models = ShootBookdetail::find()
                ->where('book_time >=' . strtotime(date('Y-m-d',$bookTime)))
                ->andWhere('book_time <=' . strtotime(date('Y-m-d',strtotime("+1 days",$bookTime))))
                ->andWhere( '`index` =' . $index)
                ->all();
        
        $roleNames = ShootBookdetailRoleName::find()
                ->where([
                    'b_id'=> ArrayHelper::getColumn($models, 'id'), 
                    'role_name' => $roleName
                ])
                ->andWhere("iscancel != 'Y'")
                ->all();
        return ArrayHelper::map($roleNames, 'u_id', 'u.nickname');
    }
    
    
    
    /**
     * 判断两个数组是否有交集
     * @param type $model
     * @param type $roleName    角色名
     * @param type $post
     * @return boolean  true为存在
     */
    protected function isTwoArrayIntersection($model, $roleName, $post=null)
    {
        $alreadyRoleNames = $this->getIsRoleNames($roleName, $model->book_time, $model->index);
        /** $post || $alreadyRoleNames 为空 return false*/
        if(empty($post) || empty($alreadyRoleNames))
            return false;
       
        /** 是否有交集 */
        foreach (array_values($post) as $temp){
           if(in_array($temp,array_keys($alreadyRoleNames))){
               return true;
            }
        }
        
        unset($post,$alreadyRoleNames,$temp);
        return false;
    }
    
    /**
     * 判断当前用户是否属于指定角色
     * @param string $roleName
     * @return bool
     */
    
    protected function isRole($roleName)
    {
        /* @var $rbacManager RbacManager */
        $rbacManager = Yii::$app->authManager;
        return $rbacManager->isRole($roleName, Yii::$app->user->id);
    }
   
}
