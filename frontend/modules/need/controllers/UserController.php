<?php

namespace frontend\modules\need\controllers;

use common\models\need\NeedTaskUser;
use common\models\need\searchs\NeedTaskUserSearch;
use common\models\RecentContacts;
use common\models\User;
use frontend\modules\need\utils\ActionUtils;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for NeedTaskUser model.
 */
class UserController extends Controller
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
     * Lists all NeedTaskUser models.
     * @return mixed
     */
    public function actionIndex($need_task_id)
    {
        $searchModel = new NeedTaskUserSearch();
        $dataProvider = $searchModel->search(['need_task_id' => $need_task_id]);

        return $this->renderAjax('index', [
            'model' => $searchModel->needTask,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single NeedTaskUser model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new NeedTaskUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param string $need_task_id
     * @return mixed
     */
    public function actionCreate($need_task_id)
    {
        $model = new NeedTaskUser(['need_task_id' => $need_task_id]);
        $model->loadDefaultValues();
        
        if($model->needTask->receive_by == Yii::$app->user->id){
            if($model->needTask->getIsFinished() || $model->needTask->is_del){
                throw new NotFoundHttpException('该任务已完成或取消');
            }
        }else{
            throw new NotFoundHttpException('无权限访问');
        }
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            return ActionUtils::getInstance()->CreateNeedTaskUser($model, Yii::$app->request->post());
        }

        return $this->renderAjax('create', [
            'model' => $model,
            'taskUsers' => $this->getHelpManList($need_task_id),
            'userRecentContacts' => $this->getUserRecentContacts(),
        ]);
    }

    /**
     * Updates an existing NeedTaskUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if($model->needTask->receive_by == Yii::$app->user->id){
            if($model->needTask->getIsFinished() || $model->needTask->is_del){
                throw new NotFoundHttpException('该任务已完成或取消！');
            }
        }else{
            throw new NotFoundHttpException('无权限访问');
        }
        
        if (\Yii::$app->request->isPost){
            Yii::$app->getResponse()->format = 'json';
            return ActionUtils::getInstance()->UpdateNeedTaskUser($model, Yii::$app->request->post());
        }
    }

    /**
     * Deletes an existing NeedTaskUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if($model->needTask->receive_by == Yii::$app->user->id){
            if($model->needTask->getIsFinished() || $model->needTask->is_del){
                throw new NotFoundHttpException('该任务已完成或取消！');
            }
        }else{
            throw new NotFoundHttpException('无权限访问');
        }
        
        if (\Yii::$app->request->isPost){
            Yii::$app->getResponse()->format = 'json';
            return ActionUtils::getInstance()->DeleteNeedTaskUser($model);
        }
    }

    /**
     * Finds the NeedTaskUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return NeedTaskUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NeedTaskUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
    /**
     * 获取所有协助人员
     * @param string $need_task_id
     * @return array
     */
    protected function getHelpManList($need_task_id)
    {
        //查找已添加的协作人员
        $query = (new Query())->select(['user_id'])->from(NeedTaskUser::tableName());
        $query->where(['need_task_id' => $need_task_id, 'is_del' => 0]);
        $user_ids = ArrayHelper::getColumn($query->all(), 'user_id');
        //合并创建者和已添加的协作人员
        $userIds = array_merge([Yii::$app->user->id], $user_ids);
        //查找所有可以添加的协作人员
        $user = (new Query())->select(['id', 'nickname'])->from(User::tableName());
        $user->where(['and', ['NOT IN', 'id', $userIds], [
            'company_id' => Yii::$app->user->identity->company_id,
            'status' => 10
        ]]);
        
        return ArrayHelper::map($user->all(), 'id', 'nickname');
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
}
