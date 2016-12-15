<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandCheck;
use common\models\demand\searchs\DemandCheckSearch;
use frontend\modules\demand\utils\DemandTool;
use wskeee\rbac\RbacName;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;




/**
 * CheckController implements the CRUD actions for DemandCheck model.
 */
class CheckController extends Controller
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
     * Lists all DemandCheck models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DemandCheckSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DemandCheck model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderPartial('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DemandCheck model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($task_id)
    {
        $model = new DemandCheck();
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        $dtTool::$table = DemandCheck::tableName();
        if(!\Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_CREATE_CHECK) || $dtTool->getIsCompleteCheck($task_id))
            throw new NotAcceptableHttpException('无权限操作！');
        $model->task_id = $task_id;
        $model->create_by = \Yii::$app->user->id;
        if(!($model->task->getIsStatusCheck() || $model->task->getIsStatusChecking()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->task->getStatusName().'！');
        
        if ($model->load(Yii::$app->request->post())) {
            $dtTool->CreateCheckTask($model);
            return $this->redirect(['task/view', 'id' => $model->task_id]);
        } else {
            return $this->renderPartial('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DemandCheck model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        $dtTool::$table = DemandCheck::tableName();
        if((!\Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_UPDATE_CHECK)
           && $model->create_by != Yii::$app->user->id) || !$dtTool->getIsCompleteCheck($model->task_id))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->task->getIsStatusAdjusimenting())
            throw new NotAcceptableHttpException('该任务状态为'.$model->task->getStatusName().'！');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['task/view', 'id' => $model->task_id]);
        } else {
            return $this->renderPartial('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * 提交审核记录
     * @param integer $task_id                      任务ID
     * @throws NotAcceptableHttpException
     */
    public function actionSubmit($task_id)
    {
        /* @var $model DemandCheck */
        $model = DemandCheck::findOne(['task_id' => $task_id, 'status' => DemandCheck::STATUS_NOTCOMPLETE]);
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        $dtTool::$table = DemandCheck::tableName();
        if(!(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_SUBMIT_CHECK) && $dtTool->getIsCompleteCheck($task_id)))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->task->getIsStatusAdjusimenting())
            throw new NotAcceptableHttpException('该任务状态为'.$model->task->getStatusName().'！');
        
        $model->complete_time = date('Y-m-d H:i', time());
        $model->status = DemandCheck::STATUS_COMPLETE;
        $dtTool->SubmitCheckTask($model);
        $this->redirect(['task/index']);
    }
    
    /**
     * Deletes an existing DemandCheck model.
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
     * Finds the DemandCheck model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandCheck the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DemandCheck::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
