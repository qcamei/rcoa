<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandAcceptance;
use common\models\demand\DemandCheck;
use common\models\demand\searchs\DemandAcceptanceSearch;
use frontend\modules\demand\utils\DemandTool;
use wskeee\rbac\RbacName;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * AcceptanceController implements the CRUD actions for DemandAcceptance model.
 */
class AcceptanceController extends Controller
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
     * Lists all DemandAcceptance models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DemandAcceptanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DemandAcceptance model.
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
     * Creates a new DemandAcceptance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($task_id)
    {
        $model = new DemandAcceptance();
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        $dtTool::$table = DemandAcceptance::tableName();
        if(!\Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_CREATE_ACCEPTANCE) || $dtTool->getIsCompleteCheck($task_id))
            throw new NotAcceptableHttpException('无权限操作！');
        $model->task_id = $task_id;
        $model->create_by = \Yii::$app->user->id;
        if(!($model->task->getIsStatusAcceptance() || $model->task->getIsStatusAcceptanceing()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->task->getStatusName().'！');

        if ($model->load(Yii::$app->request->post())) {
            $dtTool->CreateAcceptanceTask($model);
            return $this->redirect(['task/view', 'id' => $model->task_id]);
        } else {
            return $this->renderPartial('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DemandAcceptance model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        $dtTool::$table = DemandAcceptance::tableName();
        if((!\Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_UPDATE_ACCEPTANCE)
           && $model->create_by != \Yii::$app->user->id) || !$dtTool->getIsCompleteCheck($model->task_id))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->task->getIsStatusUpdateing())
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
     * 提交验收记录
     * @param integer $task_id                      任务ID
     * @throws NotAcceptableHttpException
     */
    public function actionSubmit($task_id)
    {
        /* @var $model DemandCheck */
        $model = DemandAcceptance::findOne(['task_id' => $task_id, 'status' => DemandCheck::STATUS_NOTCOMPLETE]);
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        $dtTool::$table = DemandAcceptance::tableName();
        if((!(\Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_SUBMIT_ACCEPTANCE) && $model->task->developPrincipals->u_id == \Yii::$app->user->id)
           && $dtTool->getIsCompleteCheck($task_id)))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->task->getIsStatusUpdateing())
            throw new NotAcceptableHttpException('该任务状态为'.$model->task->getStatusName().'！');
        
        $model->complete_time = date('Y-m-d H:i', time());
        $model->status = DemandCheck::STATUS_COMPLETE;
        $dtTool->SubmitAcceptanceTask($model);
        $this->redirect(['task/index']);
    }

    /**
     * Deletes an existing DemandAcceptance model.
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
     * Finds the DemandAcceptance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandAcceptance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DemandAcceptance::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
