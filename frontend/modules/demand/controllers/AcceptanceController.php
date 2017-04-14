<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandAcceptance;
use common\models\demand\DemandAcceptanceData;
use common\models\demand\DemandDelivery;
use common\models\demand\DemandDeliveryData;
use common\models\demand\DemandTask;
use common\models\demand\DemandWorkitem;
use common\models\demand\searchs\DemandAcceptanceSearch;
use common\models\workitem\Workitem;
use common\models\workitem\WorkitemType;
use frontend\modules\demand\utils\DemandTool;
use wskeee\rbac\RbacName;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
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
    public function actionView($demand_task_id, $delivery_id = null) {
        $delivery = $this->findDeliveryModel($demand_task_id);
        $delivery_id = empty($delivery_id) ? $delivery->id : $delivery_id;
        return $this->renderAjax('view', [
            'demand_task_id' => $demand_task_id,
            'datas' => $this->getW_D_A_Datas($demand_task_id, $delivery_id),
        ]);
        
        
    }

    /**
     * Creates a new DemandAcceptance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($demand_task_id) {
        $this->layout = '@app/views/layouts/main';
        $model = new DemandAcceptance();
        $delivery = $this->findDeliveryModel($demand_task_id);
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        $post = Yii::$app->request->post();
        $model->loadDefaultValues();
        $model->demand_task_id = $demand_task_id;
        
        if(!\Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_CREATE_ACCEPTANCE) 
           && $model->demandTask->create_by != \Yii::$app->user->id)
            throw new NotAcceptableHttpException('无权限操作！');
        if(!($model->demandTask->getIsStatusAcceptance() || $model->demandTask->getIsStatusAcceptanceing()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->demandTask->getStatusName().'！');
        
        $model->demand_delivery_id = ArrayHelper::getValue($post, 'DemandAcceptance.demand_delivery_id');
        $model->pass = ArrayHelper::getValue($post, 'DemandAcceptance.pass');
        $model->des = ArrayHelper::getValue($post, 'DemandAcceptance.des');
        $model->create_by = \Yii::$app->user->id;

        if (\Yii::$app->getRequest()->isPost && $model->save()) {
            $this->saveDemandAcceptanceData($model, $post);
            $dtTool->CreateAcceptanceTask($model);
            return $this->redirect(['task/view', 'id' => $model->demand_task_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'delivery' => $delivery,
                'wdArrays' => $this->getWorkitemDeliveryDatas($delivery->id),
                'percentage' => $this->getWorkitemTypePercentage($model->demand_task_id, $delivery->id),
            ]);
        }
    }

    /**
     * Updates an existing DemandAcceptance model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
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
    protected function findModel($id) {
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
    protected function findDeliveryModel($demand_task_id) {
        $delivery = DemandDelivery::find()
                ->where(['demand_task_id' => $demand_task_id])
                ->orderBy('id DESC')
                ->one();
        if ($delivery !== null) {
            return $delivery;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 获取需求和支付数据
     * @param integer $deliveryId      交付ID
     * @return array
     */
    public function getWorkitemDeliveryDatas($deliveryId) {
        $datas = (new Query())
                ->select(['Workitem_type.id AS workitem_type_id', 'Workitem_type.name AS workitem_type_name', 'Workitem_type.icon',
                    'Workitem.id AS workitem_id', 'Workitem.name AS workitem_name', 'Workitem.unit',
                    'Demand_workitem.id AS demand_workitem_id', 'Demand_workitem.is_new', 'Demand_workitem.value_type', 'Demand_workitem.value AS demand_workitem_value',
                    'Delivery_data.value AS delivery_value'
                ])
                ->from(['Delivery_data' => DemandDeliveryData::tableName()])
                ->leftJoin(['Demand_workitem' => DemandWorkitem::tableName()], 'Demand_workitem.id = Delivery_data.demand_workitem_id')
                ->leftJoin(['Workitem' => Workitem::tableName()], 'Workitem.id = Demand_workitem.workitem_id')
                ->leftJoin(['Workitem_type' => WorkitemType::tableName()], 'Workitem_type.id = Demand_workitem.workitem_type_id')
                ->where(['Delivery_data.demand_delivery_id' => $deliveryId])
                ->all();
        /** 交付数据 */
        $deliverys = [];
        foreach ($datas as $data) {
            if (!isset($deliverys[$data['workitem_type_name']])){
                $deliverys[$data['workitem_type_name']] = [
                    'id' => $data['workitem_type_id'],
                    'name' => $data['workitem_type_name'],
                    'icon' => $data['icon'],
                    'childs' => [],
                ];
            }
            if(!isset($deliverys[$data['workitem_type_name']]['childs'][$data['workitem_name']])){
                $deliverys[$data['workitem_type_name']]['childs'][$data['workitem_name']] = [
                    'id' => $data['workitem_id'],
                    'name' => $data['workitem_name'],
                    'childs' => [],
                ];
            }
            $deliverys[$data['workitem_type_name']]['childs'][$data['workitem_name']]['childs'][] = [
                'is_new' => $data['is_new'],
                'is_workitem' => 0,
                'value_type' => $data['value_type'],
                'value' => $data['delivery_value'],
                'unit' => $data['unit']
            ];
           
        }
        /** 需求数据 */
        $workitems = [];
        foreach ($datas as $data) {
            if (!isset($workitems[$data['workitem_type_name']])){
                $workitems[$data['workitem_type_name']] = [
                    'id' => $data['workitem_type_id'],
                    'name' => $data['workitem_type_name'],
                    'icon' => $data['icon'],
                    'childs' => [],
                ];
            }
            if(!isset($workitems[$data['workitem_type_name']]['childs'][$data['workitem_name']])){
                $workitems[$data['workitem_type_name']]['childs'][$data['workitem_name']] = [
                    'id' => $data['workitem_id'],
                    'name' => $data['workitem_name'],
                    'childs' => [],
                ];
            }
            $workitems[$data['workitem_type_name']]['childs'][$data['workitem_name']]['childs'][] = [
                'is_new' => $data['is_new'],
                'is_workitem' => 1,
                'value_type' => $data['value_type'],
                'value' => $data['demand_workitem_value'],
                'unit' => $data['unit']
            ];
           
          
        }
        
        return ArrayHelper::merge($workitems, $deliverys);
    }
    
    /**
     * 获取工作项类型数量的百分比
     * @param integer $taskId                  引用需求任务ID
     * @param integer $deliveryId              引用交付ID
     * @return array
     */
    public function getWorkitemTypePercentage($taskId, $deliveryId)
    {
        $percentage = (new Query())
                ->select(['Workitem_type.id', '(SUM(Delivery_data.`value`) / (IF(SUM(Demand_workitem.`value`) = 0, 1, SUM(Demand_workitem.`value`)))) * 100 AS percentage'])
                ->from(['Workitem_type' => WorkitemType::tableName()])
                ->leftJoin(['Demand_workitem' => DemandWorkitem::tableName()], 'Demand_workitem.workitem_type_id = Workitem_type.id')
                ->leftJoin(['Delivery_data' => DemandDeliveryData::tableName()], 'Delivery_data.demand_workitem_id = Demand_workitem.id')
                ->where(['Demand_workitem.demand_task_id' => $taskId, 'Delivery_data.demand_delivery_id' => $deliveryId])
                ->groupBy('Demand_workitem.workitem_type_id')
                ->all();

        return ArrayHelper::map($percentage, 'id', 'percentage');
    }
    
    /**
     * 保存需求交付数据到表里
     * @param DemandAcceptance $model              
     * @param type $post              
     */
    public function saveDemandAcceptanceData($model, $post){
        $values = ArrayHelper::getValue($post, 'value');
        $datas = [];
        foreach ($values as $key => $value) {
            $datas[] = [
                'demand_acceptance_id' => $model->id,
                'workitem_type_id' => $key,
                'value' => $value,
            ];
        }
        ArrayHelper::multisort($datas, 'workitem_type_id', SORT_ASC);
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        { 
            if($model !== null && $datas != null){
                /** 添加$values数组到表里 */
                Yii::$app->db->createCommand()->batchInsert(DemandAcceptanceData::tableName(), 
                ['demand_acceptance_id', 'workitem_type_id', 'value'], $datas)->execute();
                if($model->pass == false){
                    \Yii::$app->db->createCommand()->update(DemandTask::tableName(), [
                        'status' => DemandTask::STATUS_UPDATEING], ['id' => $model->demand_task_id])->execute();
                }else{
                    \Yii::$app->db->createCommand()->update(DemandTask::tableName(), [
                        'status' => DemandTask::STATUS_COMPLETED,
                        'progress' => DemandTask::$statusProgress[DemandTask::STATUS_COMPLETED],
                        'reality_check_harvest_time' => Date('Y-m-d H:i', time()),
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
     * 获取所有需求交付验收数据
     * @param integer $demand_task_id              引用需求任务ID
     * @param integer $delivery_id                 引用交付ID
     * @return array
     */
    public function getW_D_A_Datas($demand_task_id, $delivery_id) 
    {
        
        $datas = (new Query())
            ->select([
                'Demand_workitem.workitem_type_id',  'Demand_workitem.workitem_id', 'Demand_workitem.created_at AS demand_workitem_time',
                'Demand_task.id AS demand_task_id', 'Demand_task.des AS demand_workitem_des',
                'Demand_workitem.is_new', 'Demand_workitem.value_type','Demand_workitem.value AS demand_workitem_value',
                'Workitem.name AS workitem_name', 'Workitem.unit',
                'Workitem_type.name AS workitem_type_name', 'Workitem_type.icon',
                'Delivery.des AS delivery_des','Delivery.created_at AS delivery_time',
                'Delivery_data.value AS deliver_data_value',
                'Acceptance.pass', 'Acceptance.des AS acceptance_des', 'Acceptance.created_at AS acceptance_time',
                'Acceptance_data.workitem_type_id AS ad_workitem_type_id',
                'Acceptance_data.value AS acceptance_data_value'
            ])
            ->from(['Demand_workitem' => DemandWorkitem::tableName()])
            ->leftJoin(['Demand_task' => DemandTask::tableName()], 'Demand_task.id = Demand_workitem.demand_task_id')
            ->leftJoin(['Workitem' => Workitem::tableName()], 'Workitem.id = Demand_workitem.workitem_id')
            ->leftJoin(['Workitem_type' => WorkitemType::tableName()], 'Workitem_type.id = Demand_workitem.workitem_type_id')
            ->leftJoin(['Delivery' => DemandDelivery::tableName()], 'Delivery.demand_task_id = Demand_workitem.demand_task_id')
            ->leftJoin(['Delivery_data' => DemandDeliveryData::tableName()], '(Delivery_data.demand_delivery_id = Delivery.id AND Delivery_data.demand_workitem_id = Demand_workitem.id)')
            ->leftJoin(['Acceptance' => DemandAcceptance::tableName()], '(Acceptance.demand_task_id = Demand_workitem.demand_task_id AND Acceptance.demand_delivery_id = Delivery.id)')
            ->leftJoin(['Acceptance_data' => DemandAcceptanceData::tableName()], 'Acceptance_data.demand_acceptance_id = Acceptance.id')
            ->where([
                'Demand_workitem.demand_task_id' => $demand_task_id,
                'Delivery.demand_task_id' => $demand_task_id,
                'Acceptance.demand_task_id' => $demand_task_id,
                'Delivery.id' => $delivery_id
            ])->all();        

        $workitemType = [];         //工作项类型数据
        $demandDelivery = [];       //需求和交付数据
        $acceptance = [];           //验收数据
        $workitemValue = [];        //需求数量
        $deliveryValue = [];        //交付数量
        $timeDes = [];
        foreach ($datas as $data) {
            $workitemType[$data['workitem_type_id']] =[
                'id' => $data['workitem_type_id'],
                'name' => $data['workitem_type_name'],
                'icon' => $data['icon'],
            ];
            if(!isset($demandDelivery[$data['workitem_id']])){
                $demandDelivery[$data['workitem_id']] = [
                    'id' => $data['workitem_id'],
                    'workitem_type' => $data['workitem_type_id'],
                    'name' => $data['workitem_name'],
                    'childs' => [],
                ];
            }
            $demandDelivery[$data['workitem_id']]['childs'][$data['is_new']] = [
                'is_new' => $data['is_new'],
                'value_type' => $data['value_type'],
                'demand_workitem_value' => $data['demand_workitem_value'],
                'deliver_data_value' => $data['deliver_data_value'],
                'unit' => $data['unit'],
            ];
            
            if($data['ad_workitem_type_id'] == $demandDelivery[$data['workitem_id']]['workitem_type']){
                $workitemValue[$data['ad_workitem_type_id']][] = $data['demand_workitem_value'];
                $deliveryValue[$data['ad_workitem_type_id']][] = $data['deliver_data_value'];
            }
            $acceptance[$data['ad_workitem_type_id']] = [
                'acceptance_data_value' => $data['acceptance_data_value'],
                
            ];
            $timeDes[$data['demand_task_id']] = [
                'pass' => $data['pass'],
                'demand_workitem_des' => $data['demand_workitem_des'],
                'demand_workitem_time' => date('Y-m-d H:i', $data['demand_workitem_time']),
                'delivery_des' => $data['delivery_des'],
                'delivery_time' => date('Y-m-d H:i', $data['delivery_time']),
                'acceptance_des' => $data['acceptance_des'],
                'acceptance_time' => date('Y-m-d H:i', $data['acceptance_time']),
            ];
        }
        foreach ($workitemValue as $key => $workitem){
            $acceptance[$key]['workitem_value'] = array_sum($workitem);
        }
        foreach ($deliveryValue as $key => $delivery){
            $acceptance[$key]['deliver_value'] = array_sum($delivery);
        }
        
        return [
            'workitemType' => $workitemType,
            'demandDelivery' => $demandDelivery,
            'acceptance' => $acceptance,
            'timeDes' => $timeDes
        ];
     
    }
    
    
    
}
