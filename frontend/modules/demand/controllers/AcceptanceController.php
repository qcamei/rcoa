<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandAcceptance;
use common\models\demand\DemandDelivery;
use common\models\demand\DemandDeliveryData;
use common\models\demand\DemandWorkitem;
use common\models\demand\searchs\DemandAcceptanceSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DemandAcceptance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($demand_task_id)
    {
        $this->layout = '@app/views/layouts/main';
        $model = new DemandAcceptance();
        $delivery = $this->findDeliveryModel($demand_task_id);
        $model->loadDefaultValues();
        
        $model->demand_task_id = $demand_task_id;
        $model->create_by = \Yii::$app->user->id;
        
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['task/view', 'id' => $model->demand_task_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'delivery' => $delivery,
                'wdArrays' => $this->getWorkitemDeliveryDatas($model),
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
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
    
    /**
     * Finds the DemandDelivery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandDelivery the loaded delivery
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findDeliveryModel($demand_task_id)
    {
        $delivery = DemandDelivery::find()
                    ->where(['demand_task_id' => $demand_task_id])
                    ->orderBy('demand_task_id DESC')
                    ->one();
        if ($delivery !== null) {
            return $delivery;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 获取需求和支付数据
     * @param DemandAcceptance $model
     */
    public function getWorkitemDeliveryDatas($model)
    {
        $delivery = $this->findDeliveryModel($model->demand_task_id);
        $deliveryDatas = DemandDeliveryData::find()
                         ->where(['demand_delivery_id' => $delivery->id])
                         ->all();
        $deliverys = [];
        foreach ($deliveryDatas as $dModel) {
            /* @var $dModel DemandDeliveryData */
            $deliverys [$dModel->demandWorkitem->workitemType->name][$dModel->demandWorkitem->workitem->name][] = [
                'workitem_type_id' => $dModel->demandWorkitem->workitem_type_id,
                'is_new' => $dModel->demandWorkitem->is_new,
                'value_type' => $dModel->demandWorkitem->value_type,
                'is_workitem' => '0',
                'value' => $dModel->value,
                'unit' => $dModel->demandWorkitem->workitem->unit,
            ];
        }
            
        $workitems = [];
        foreach ($model->demandTask->demandWorkitems as $wModel) {
            /* @var $wModel DemandWorkitem */
            $workitems[$wModel->workitemType->name][$wModel->workitem->name][] = [
                'workitem_type_id' => $wModel->workitem_type_id,
                'is_new' => $wModel->is_new,
                'value_type' => $wModel->value_type,
                'is_workitem' => '1',
                'value' => $wModel->value,
                'unit' => $wModel->workitem->unit,
            ];
        }
        //var_dump( ArrayHelper::merge($workitems, $deliverys));exit;
        return ArrayHelper::merge($workitems, $deliverys);
    }
}
