<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandAppeal;
use common\models\demand\DemandAppealReply;
use common\models\demand\DemandReply;
use common\models\demand\searchs\DemandReplySearch;
use frontend\modules\demand\utils\DemandTool;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * ReplyController implements the CRUD actions for DemandReply model.
 */
class AppealReplyController extends Controller
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
     * Lists all DemandReply models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DemandReplySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DemandReply model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DemandReply model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($demand_task_id)
    {
        $model = new DemandAppealReply();
        $model->loadDefaultValues();
        $appeal = $this->findAppealModel($demand_task_id);
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        $model->demand_appeal_id = $appeal->id;
        $model->create_by = \Yii::$app->user->id;
        
        if(!($model->demandAppeal->demandTask->create_by == \Yii::$app->user->id))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->demandAppeal->demandTask->getIsStatusAppealing())
            throw new NotAcceptableHttpException('该任务状态为'.$model->demandTask->getStatusName().'！');
        
        if ($model->load(Yii::$app->request->post())) {
            $dtTool->SubmitAppealReplyTask($model);
            return $this->redirect(['task/view', 'id' => $model->demandAppeal->demand_task_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DemandReply model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DemandReply model.
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
     * Finds the DemandReply model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandReply the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DemandAppealReply::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Finds the DemandAppeal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandReply the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findAppealModel($demand_task_id)
    {
        $appeal = DemandAppeal::find()
                ->where(['demand_task_id' => $demand_task_id])
                ->orderBy('id desc')
                ->one();
        
        if ($appeal !== null) {
            return $appeal;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
