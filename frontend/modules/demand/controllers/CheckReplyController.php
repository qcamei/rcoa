<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandCheck;
use common\models\demand\DemandCheckReply;
use common\models\demand\searchs\DemandCheckReplySearch;
use frontend\modules\demand\utils\DemandAction;
use frontend\modules\demand\utils\DemandTool;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * CheckReplyController implements the CRUD actions for DemandCheckReply model.
 */
class CheckReplyController extends Controller
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
     * Lists all DemandCheckReply models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DemandCheckReplySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DemandCheckReply model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DemandCheckReply model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($task_id, $pass)
    {
        $model = new DemandCheckReply();
        $model->loadDefaultValues();
        $checkMdoel = $this->findCheckModel($task_id);
        $model->demand_check_id = $checkMdoel->id;
        $model->create_by = \Yii::$app->user->id;
        $createTeam = $checkMdoel->demandTask->create_team;
        //是否是审核人
        $isAuditor = DemandAction::getInstance()->getIsAuditor($createTeam);
        if($isAuditor){
            if(!($model->demandCheck->demandTask->getIsStatusCheck() || $model->demandCheck->demandTask->getIsStatusChecking()))
                throw new NotAcceptableHttpException('该任务状态为'.$checkMdoel->demandTask->getStatusName().'！');
        }else{
            throw new NotAcceptableHttpException('无权限操作！');
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if($pass == true){
                DemandAction::getInstance()->DemandPassCheckReply($model);
                return $this->redirect(['task/index']);
            }
            else{
                DemandAction::getInstance()->DemandNoPassCheckReply($model);
                return $this->redirect(['task/view', 'id' => $task_id]);
            }
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
                'pass' => $pass,
            ]);
        }
    }

    /**
     * Updates an existing DemandCheckReply model.
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
     * Deletes an existing DemandCheckReply model.
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
     * Finds the DemandCheckReply model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandCheckReply the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DemandCheckReply::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Finds the DemandCheck model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $task_id
     * @return DemandCheckReply the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCheckModel($task_id)
    {
        $check = DemandCheck::find()->where(['demand_task_id' => $task_id])
            ->orderBy('id desc')->one();
        
        if ($check !== null) {
            return $check;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
