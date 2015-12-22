<?php

namespace frontend\modules\shoot\controllers;

use common\models\shoot\searchs\ShootBookdetailSearch;
use common\models\shoot\ShootAppraiseTemplate;
use common\models\shoot\ShootAppraiseWork;
use common\models\shoot\ShootBookdetail;
use common\models\shoot\ShootHistory;
use common\models\shoot\ShootSite;
use wskeee\ee\EeManager;
use wskeee\framework\FrameworkManager;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use wskeee\utils\DateUtil;
use Yii;
use yii\base\Exception;
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
        $fwManager = \Yii::$app->get('fwManager');
        
        $date=  isset(Yii::$app->request->queryParams['date']) ? 
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
     * Displays a single ShootBookdetail model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $dataProvider = $model->historys;
        
        return $this->render('view', [
                    'model' => $this->findModel($id),
                    'dataProvider' => new ArrayDataProvider([
                        'allModels' => $dataProvider,
                    ]),
                    'shootmans' => $this->isRole(RbacName::ROLE_SHOOT_LEADER) ?
                            $this->getRoleToUsers(RbacName::ROLE_SHOOT_MAN) : [],
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
        /** 全并且get参数与post参数 */
        $post = ArrayHelper::merge($post, $body);
        /**
         * 先查找对应数据（临时预约锁定的数据）
         * 找不到再新建数据
         */
        

        if (isset($post['b_id']))
            $model = ShootBookdetail::findOne($post['b_id']);
        if (!isset($model)) {
            $model = new ShootBookdetail();
            $model->loadDefaultValues();
        } else if ($model->getIsBooking() && ($model->create_by && $model->create_by != Yii::$app->user->id)) {
            throw new NotAcceptableHttpException('非法操作！');
        } else if ($model->getIsBooking() && $model->create_by && $model->create_by == Yii::$app->user->id) {
            //清除之前临时预约
            //            $tempbook = ShootBookdetail::find()
            //                    ->andWhere('create_by' => Yii::$app->user->id)
            //                    ->andWhere('status' =>  ShootBookdetail::STATUS_BOOKING)
                
        }
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            /** 保存预约 */
            if($this->saveNewBookdetail($model))
                /** 创建新增 */
                $this->sendNewShootNotification ($model, '新增');
            return $this->redirect([ 'view', 'id' => $model->id]);
        } else {
            $model->status = ShootBookdetail::STATUS_BOOKING;
            $model->u_booker = Yii::$app->user->id;
            $model->u_contacter = Yii::$app->user->id;
            $model->create_by = Yii::$app->user->id;
              
            !isset($post['site_id']) ? : $model->site_id = $post['site_id'];
            !isset($post['book_time']) ? : $model->book_time = $post['book_time'];
            !isset($post['index']) ? : $model->index = $post['index'];

            $model->setScenario(ShootBookdetail::SCENARIO_TEMP_CREATE);
            $model->save();
            $model->setScenario(ShootBookdetail::SCENARIO_DEFAULT);
            /** 设置上下晚预约的默认开始时间 */
            if($model->index == $model::TIME_INDEX_MORNING)
            {
                $model->start_time = $model::START_TIME_MORNING;
            }
            
            else if($model->index == $model::TIME_INDEX_AFTERNOON)
            {
                $model->start_time = $model::START_TIME_AFTERNOON;
            }
            
            else if($model->index == $model::TIME_INDEX_NIGHT)
            {
                 $model->start_time = $model::START_TIME_NIGHT;
            }
            
            return $this->render('create', [
                        'model' => $model,
                        'users' => $this->getRoleToUsers(RbacName::ROLE_WD),
                        'colleges' => $this->getCollegesForSelect(),
                        'projects' => [],
                        'courses' => [],
            ]);
        }
    }
    
    /**
     * 
     * @param ShootBookdetail $model
     */
    private function saveNewBookdetail($model)
    {
        $trans = \Yii::$app->db->beginTransaction();
        try
        {
            $model->status = ShootBookdetail::STATUS_ASSIGN;
            
            if(!$model->save())
                throw new Exception(json_encode($model->getErrors()));
            
            $work = new ShootAppraiseWork(['b_id'=>$model->id]);
            if(!$work->save(ShootAppraiseTemplate::find()->asArray()->all()))
                throw new Exception(json_encode($model->getErrors()));
            
            $trans->commit();
            return true;
        } catch (\Exception $ex) {
            $trans ->rollBack();
            
            throw new NotFoundHttpException("保存任务失败！".$ex->getMessage()); 
            return false;
        }
    }
    
    /**
     * 创建/指派/更改指派 成功 发送 邮件、ee 通知
     * @param type $model
     * @param type $mode  标题模式
     * @param type $type  操作 null表示创建 1表示指派 2表示更改指派
     */
    private function sendNewShootNotification($model, $mode, $type=null)
    {       
        /** 传进view 模板参数 */
        $params = [
            'b_id' => $model->id,
            'model' => $model,
            'bookTime' => date('Y/m/d ',$model->book_time).Yii::t('rcoa', 'Week '.date('D',$model->book_time)).' '.$model->getTimeIndexName(),
        ];
         /** 主题 */
        $subject = "拍摄-".$mode."-".$model->fwCourse->name;
        if(!isset($type)){
            //创建--给所有摄影组长发送通知
            $this->sendShootLeadersNotification($params, $subject);
        }else if($type == 1) {
            //指派--给编导发送通知
            $this->sendBookerNotification($model, $params, $subject);
            //指派--给接洽人发送通知
            $this->sendContacterNotification($model, $params, $subject, 'shoot\ShootAssign-u_contacter-html');
            //指派--给摄影师发送通知
            $this->sendShootManNotification($model, $params, $subject, 'shoot\ShootAssign-u_shoot_man-html');
            //指派--给老师发送通知
            $this->sendTeacherNotification($model, $params, $subject);
        }else if($type == 2) {
            //更改指派--给接洽人发送通知
            $this->sendContacterNotification($model, $params, $subject, 'shoot\ShootEditAssign-u_contacter-html');
            //更改指派--给旧摄影师发送通知
            $this->sendShootManNotification($model, $params, $subject, 'shoot\ShootEditAssign-u_shoot_man-html');
            //更改指派--给新摄影师发送通知
            $this->sendShootManNotification($model, $params, $subject, 'shoot\ShootAssign-u_shoot_man-html');
        }
    }

    /**
     * 给所有摄影组长 发送 ee通知 email
     * @param type $model
     * @param type $params      模版参数
     * @param type $subject     主题
     */
    public function sendShootLeadersNotification($params, $subject){
        /* @var $authManager RbacManager */
        $authManager = Yii::$app->authManager;
        /**  查找所有摄影组长 */
        $shootLeaders = $authManager->getItemUsers(RbacName::ROLE_SHOOT_LEADER);
        /**  所有摄影师组长ee */
        $receivers_ee = array_filter(ArrayHelper::getColumn($shootLeaders, 'ee'));
        /**  所有摄影师组长邮箱地址 */
        $receivers_mail = array_filter(ArrayHelper::getColumn($shootLeaders, 'email'));
         /** 发送ee消息 */
        EeManager::sendEeByView( 'shoot\newShoot-html', $params, $receivers_ee, $subject);
        /** 发送邮件消息 */
        Yii::$app->mailer->compose( 'shoot\newShoot-html', $params)
            ->setTo($receivers_mail)
            ->setSubject($subject)
            ->send();
    }
    
    /**
     * 给编导 发送 ee通知 email
     * @param type $model
    * @param type $params      模版参数
     * @param type $subject     主题
     */
    public function sendBookerNotification($model, $params, $subject){
         /**  查找编导ee和mail */
        $shootBooker_ee = $model->booker->ee;
        $shootBooker_mail = $model->booker->email;
         /** 发送ee消息 */
        EeManager::sendEeByView('shoot\ShootAssign-u_contacter-html', $params,$shootBooker_ee, $subject);
        /** 发送邮件消息 */
        Yii::$app->mailer->compose('shoot\ShootAssign-u_contacter-html', $params)
            ->setTo($shootBooker_mail)
            ->setSubject($subject)
            ->send();
    }
    
    /**
     * 给接洽人 发送 ee通知 email
     * @param type $model
     * @param type $params      模版参数
     * @param type $subject     主题
     * @param type $views       视图
     */
    public function sendContacterNotification($model, $params, $subject, $views){
        /**  查找接洽人ee和mail */
        $shootContacter_ee = $model->contacter->ee;
        $shootContacter_mail = $model->contacter->email;
        /** 发送ee消息 */
        EeManager::sendEeByView($views, $params,$shootContacter_ee, $subject);
        /** 发送邮件消息 */
        Yii::$app->mailer->compose($views, $params)
            ->setTo($shootContacter_mail)
            ->setSubject($subject)
            ->send();
    }
    
    /**
     * 给摄影师 发送 ee通知 email
     * @param type $model
     * @param type $params      模版参数
     * @param type $subject     主题
     * @param type $views       视图
     */
    public function sendShootManNotification($model, $params, $subject, $views) {
        /**  查找摄影师ee和mail */
        $shootMan_ee = $model->shootMan->ee;
        $shootMan_mail = $model->shootMan->email;
        /** 发送ee消息 */
        EeManager::sendEeByView($views, $params,$shootMan_ee, $subject);
        /** 发送邮件消息 */
         Yii::$app->mailer->compose($views, $params)
            ->setTo($shootMan_mail)
            ->setSubject($subject)
            ->send();
    }
    
    /**
     * 给老师 发送 ee通知 email
     * @param type $model
     * @param type $params      模版参数
     * @param type $subject     主题
     */
    public function sendTeacherNotification($model, $params, $subject){
         /**  查找老师ee和mail */
        $shootTeacher_ee = $model->teacher->ee;
        $shootTeacher_mail = $model->teacher->email;
        /** 发送ee消息 */
        EeManager::sendEeByView('shoot\ShootAssign-u_teacher-html', $params, $shootTeacher_ee, $subject);
        /** 发送邮件消息 */
        Yii::$app->mailer->compose('shoot\ShootAssign-u_teacher-html', $params)
            ->setTo($shootTeacher_mail)
            ->setSubject($subject)
            ->send();
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
     * Updates an existing ShootBookdetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            $this->saveNewHistory($model);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'users' => $this->getRoleToUsers(RbacName::ROLE_WD),
                'colleges' => $this->getCollegesForSelect(),
                'projects' => $this->getFwItemForSelect($model->fw_college),
                'courses' => $this->getFwItemForSelect($model->fw_project),
            ]);
        }
    }
    /**
     * 历史记录保存
     * @param type $model
     */
    public function saveNewHistory($model)
    {
        $post = Yii::$app->getRequest()->getBodyParams();
        $dbTrans = \Yii::$app->db->beginTransaction();
        try{ 
            $history = new ShootHistory();
            /**编辑原因为空不保存*/
            if(!empty($post['editreason'])){
                $history->b_id = $model->id;
                $history->u_id = Yii::$app->user->id;
                $history->history = $post['editreason'];
                $history->save();
                $dbTrans->commit();  
            } 
        } catch (Exception $ex) {
            $dbTrans->rollback();     
        }
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
     * 指派摄影师
     * @param int $id           任务id
     * @param int $shoot_man_id 指派摄影师id
     */
    public function actionAssign($id)
    {
        $model = $this->findModel($id);
        try
        {   
            $oldShootMan = $model->u_shoot_man;
            if($model->load(\Yii::$app->getRequest()->post()) && $model->validate());
            {
                $model->status = $model->u_shoot_man == null ? ShootBookdetail::STATUS_ASSIGN : ShootBookdetail::STATUS_SHOOTING;
                $model->save();
                Yii::$app->getSession()->setFlash('success','操作成功！');
                $this->saveNewHistory($model);
                /** oldShootMan 非空为‘更改指派’*/
                if($oldShootMan != null){
                    $this->sendNewShootNotification($model, '更改指派', 2);
                }  else {
                    $this->sendNewShootNotification($model, '指派', 1);
                }
            }
        } catch (\Exception $ex) {
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
        
        $this->redirect(['view', 'id' => $id]);
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
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getCollegesForSelect()
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = \Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getColleges(), 'id', 'name');
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
     * 获取项目
     * @param int $itemId
     */
    protected function getFwItemForSelect($itemId)
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = \Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getChildren($itemId), 'id', 'name');
    }


    /**
     * 获取角色的用户
     * @param string $roleName 角色
     */
    protected function getRoleToUsers($roleName)
    {
        /* @var $rbacManager RbacManager */
        $rbacManager = \Yii::$app->authManager;
        return ArrayHelper::map($rbacManager->getItemUsers($roleName), 'id', 'nickname');
    }
    
    /**
     * 判断当前用户是否属于指定角色
     * @param string $roleName
     * @return bool
     */
    
    protected function isRole($roleName)
    {
        /* @var $rbacManager RbacManager */
        $rbacManager = \Yii::$app->authManager;
        return $rbacManager->isRole($roleName, Yii::$app->user->id);
    }
}
