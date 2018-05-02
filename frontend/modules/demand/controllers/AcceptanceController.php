<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandAcceptance;
use common\models\demand\DemandAcceptanceData;
use common\models\demand\DemandAppeal;
use common\models\demand\DemandAppealReply;
use common\models\demand\DemandDelivery;
use common\models\demand\DemandDeliveryData;
use common\models\demand\DemandTask;
use common\models\demand\DemandWorkitem;
use common\models\demand\searchs\DemandAcceptanceSearch;
use Detection\MobileDetect;
use frontend\modules\demand\utils\DemandAction;
use frontend\modules\demand\utils\DemandQuery;
use frontend\modules\demand\utils\DemandTool;
use Yii;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * AcceptanceController implements the CRUD actions for DemandAcceptance model.
 */
class AcceptanceController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
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
     * Lists all DemandAcceptance models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new DemandAcceptanceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

   
    
    /**
     * Displays a single DemandAcceptance model.
     * @param integer $demand_task_id
     * @return mixed
     */
    public function actionView($task_id, $delivery_id = null) {
        $detect = new MobileDetect();
        $delivery = $this->findDeliveryModel($task_id);
        $delivery_id = empty($delivery_id) ? $delivery->id : $delivery_id;
        $model = $this->findModel($task_id, $delivery_id);
        $model->demand_task_id = $task_id;
        
        return $this->renderAjax(!$detect->isMobile() ? 'view' : 'wap_view', [
            'model' => $model,
            'demand_task_id' => $task_id,
            'delivery_id' => $delivery_id,
            'dates' => $this->getDeliveryCreatedAt($task_id),
            'workitemType' => DemandTool::getInstance()->getDemandWorkitemTypeData($task_id),
            'workitem' => DemandTool::getInstance()->getDemandWorkitemData($task_id),
            'delivery' => DemandTool::getInstance()->getDemandDeliveryData($task_id, $delivery_id),
            'acceptance' => DemandTool::getInstance()->getDemandAcceptanceData($task_id, $delivery_id),
            'percentage' => $this->getWorkitemTypePercentage($task_id, $delivery_id),
        ]);        
    }
    
    /**
     * Creates a new DemandAcceptance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($task_id, $pass = 0) {
        $this->layout = '@app/views/layouts/main';
        $model = new DemandAcceptance();
        $deliveryModel = $this->findDeliveryModel($task_id);
        $model->loadDefaultValues();
        $model->demand_task_id = $task_id;
        if($model->demandTask->create_by == \Yii::$app->user->id){
            if(!($model->demandTask->getIsStatusAcceptance() || $model->demandTask->getIsStatusAcceptanceing() || $model->demandTask->getIsStatusAppealing()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->demandTask->getStatusName().'！');
        }else {
            throw new NotAcceptableHttpException('无权限操作！');
        }    
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->saveDemandAcceptanceData($model, Yii::$app->request->post());
            DemandAction::getInstance()->DemandCreateAcceptance($model);
            return $this->redirect(['task/view', 'id' => $model->demand_task_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                 'pass' => $pass,
                'deliveryModel' => $deliveryModel,
                'detect' => new MobileDetect(),
                'workitemType' => DemandTool::getInstance()->getDemandWorkitemTypeData($model->demand_task_id),
                'workitem' => DemandTool::getInstance()->getDemandWorkitemData($model->demand_task_id),
                'delivery' => DemandTool::getInstance()->getDemandDeliveryData($model->demand_task_id, $deliveryModel->id),
                'percentage' => $this->getWorkitemTypePercentage($model->demand_task_id, $deliveryModel->id),
            ]);
        }
    }

    /**
     * Updates an existing DemandAcceptance model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($task_id, $delivery_id){
        $this->layout = '@app/views/layouts/main';
        $deliveryModel = $this->findDeliveryModel($task_id);
        $model = $this->findModel($task_id, $delivery_id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'deliveryModel' => $deliveryModel,
                'detect' => new MobileDetect(),
                'workitemType' => DemandTool::getInstance()->getDemandWorkitemTypeData($model->demand_task_id),
                'workitem' => DemandTool::getInstance()->getDemandWorkitemData($model->demand_task_id),
                'delivery' => DemandTool::getInstance()->getDemandDeliveryData($model->demand_task_id, $deliveryModel->id),
                'percentage' => $this->getWorkitemTypePercentage($model->demand_task_id, $deliveryModel->id),
            ]);
        }
    }

    /**
     * Deletes an existing DemandAcceptance model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
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
    protected function findModel($task_id = null, $delivery_id = null) {
        $model = DemandAcceptance::find()->filterWhere(['demand_task_id' => $task_id])
                ->andFilterWhere(['demand_delivery_id' => $delivery_id])->orderBy('id desc')->one();
        
        if ($model !== null) {
            return $model;
        } else {
            return new DemandAcceptance();
        }
    }

    /**
     * Finds the DemandDelivery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandDelivery the loaded delivery
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findDeliveryModel($task_id) {
        $delivery = DemandDelivery::find()->where(['demand_task_id' => $task_id])
            ->orderBy('id desc')->one();
        
        if ($delivery !== null) {
            return $delivery;
        } else {
            return new DemandDelivery();
        }
    }    
    
    /**
     * Finds the DemandAppeal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandDelivery the loaded delivery
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findAppealModel($task_id) {
        $appeal = DemandAppeal::find()->where(['demand_task_id' => $task_id])
            ->orderBy('id desc')->one();
        
        if ($appeal !== null) {
            return $appeal;
        } else {
            return null;
        }
    }    
    
    /**
     * 获取交付创建时间
     * @param integer $demand_task_id           引用需求任务ID
     * @return type
     */
    public function getDeliveryCreatedAt($demand_task_id)
    {
        $dates = (new Query())
                ->select(['id', 'FROM_UNIXTIME(created_at, "%Y-%m-%d %H:%i:%s") AS date'])
                ->from(DemandDelivery::tableName())
                ->where(['demand_task_id' => $demand_task_id])
                ->orderBy('created_at DESC')
                ->all();
        
        return ArrayHelper::map($dates, 'id', 'date');
    }
    
    /**
     * 获取工作项类型数量的百分比
     * @param integer $demand_task_id                  引用需求任务ID
     * @param integer $delivery_id                      引用交付ID
     * @return array
     */
    public function getWorkitemTypePercentage($demand_task_id, $delivery_id)
    {
        $percentage = (new Query())
                ->select(['Demand_workitem.workitem_type_id', 'SUM(Delivery_data.`value`) / SUM(Demand_workitem.`value`) * 100 AS percentage'])
                ->from(['Demand_workitem' => DemandWorkitem::tableName()])
                ->leftJoin(['Delivery_data' => DemandDeliveryData::tableName()], 'Delivery_data.demand_workitem_id = Demand_workitem.id')
                ->where(['Demand_workitem.demand_task_id' => $demand_task_id, 'Delivery_data.demand_delivery_id' => $delivery_id])
                ->groupBy('Demand_workitem.workitem_type_id')
                ->all();
 
        return ArrayHelper::map($percentage, 'workitem_type_id', 'percentage');
    }
    
    /**
     * 需求任务的绩效得分
     * @param integer $demand_task_id
     * @param integer $delivery_id
     * @param integer $acceptance_id
     * @return array
     */
    public function getDemandTaskScore($demand_task_id, $delivery_id, $acceptance_id)
    {
        /* @var $dtQuery DemandQuery */
        $dtQuery = DemandQuery::getInstance();
        
        $score = (new Query())
                ->select(['Demand_score.id', 'SUM(Demand_score.score) AS score'])
                ->from(['Demand_score' => $dtQuery->findDemandWorkitemTypeScore($demand_task_id, $delivery_id, $acceptance_id)])
                ->all();
        
        return ArrayHelper::map($score, 'id', 'score');
    }

    /**
     * 保存需求验收数据到表里
     * @param DemandAcceptance $model              
     * @param type $post              
     */
    public function saveDemandAcceptanceData($model, $post)
    {
        $datas = [];
        $values = ArrayHelper::getValue($post, 'value');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        { 
            if($model != null){
                foreach ($values as $key => $value) {
                    $datas[] = [
                        'demand_acceptance_id' => $model->id,
                        'workitem_type_id' => $key,
                        'value' => $value,
                    ];
                }

                ArrayHelper::multisort($datas, 'workitem_type_id', SORT_ASC);
                /** 添加$values数组到表里 */
                Yii::$app->db->createCommand()->batchInsert(DemandAcceptanceData::tableName(), 
                ['demand_acceptance_id', 'workitem_type_id', 'value'], $datas)->execute();
                
                if($model->pass == false){
                    \Yii::$app->db->createCommand()->update(DemandTask::tableName(), [
                        'status' => DemandTask::STATUS_UPDATEING], ['id' => $model->demand_task_id])->execute();
                }else{
                    $score = $this->getDemandTaskScore($model->demand_task_id, $model->demand_delivery_id, $model->id);
                    $number = $model->updateAll(['score' => $score[$model->demand_task_id]], ['id' => $model->id]);
                    if($model->demandTask->getIsStatusAppealing())
                        $this->saveDemandAppealReply($model->demand_task_id, $model->pass);
                    \Yii::$app->db->createCommand()->update(DemandTask::tableName(), [
                        'status' => DemandTask::STATUS_WAITCONFIRM,
                        'progress' => DemandTask::$statusProgress[DemandTask::STATUS_WAITCONFIRM],
                        'score' => $score[$model->demand_task_id],
                        'reality_check_harvest_time' => Date('Y-m-d H:i', $model->created_at),
                        ], ['id' => $model->demand_task_id])->execute();
                }
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage()); 
        }
    }
    
    /**
     * 保存需求回复数据到表里
     * @param integer $demand_task_id          需求任务ID
     * @param integer $pass                    是否同意
     */
    public function saveDemandAppealReply($demand_task_id, $pass)
    {
        /* @var $appeal DemandAppeal */
        $appeal = $this->findAppealModel($demand_task_id);
        $replys[] = [
            'demand_appeal_id' => $appeal->id,
            'title' => '回复',
            'pass' => $pass,
            'des' => '无',
            'create_by' => \Yii::$app->user->id,
            'created_at' => time(),
            'updated_at' => time(),
        ];
       
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(DemandAppealReply::tableName(), 
        ['demand_appeal_id', 'title', 'pass', 'des', 'create_by', 'created_at', 'updated_at'], $replys)->execute();
    }
}
