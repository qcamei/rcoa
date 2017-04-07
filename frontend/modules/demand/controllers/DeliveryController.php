<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandDelivery;
use common\models\demand\DemandDeliveryData;
use common\models\demand\DemandTask;
use common\models\demand\searchs\DemandDeliverySearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * DeliveryController implements the CRUD actions for DemandDelivery model.
 */
class DeliveryController extends Controller
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
     * Lists all DemandDelivery models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DemandDeliverySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DemandDelivery model.
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
     * Creates a new DemandDelivery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($demand_task_id)
    {
        $this->layout = '@app/views/layouts/main';
        $model = new DemandDelivery();
        $model->loadDefaultValues();
        $post = Yii::$app->request->post();
        
        $model->demand_task_id = $demand_task_id;
        $model->create_by = \Yii::$app->user->id;
        $model->des = ArrayHelper::getValue($post, 'des');
      
        if($model->demandTask->developPrincipals->u_id != \Yii::$app->user->id)
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->demandTask->getIsStatusDeveloping())
            throw new NotAcceptableHttpException('该任务状态为'.$model->demandTask->getStatusName().'！');
        
        if (\Yii::$app->getRequest()->isPost && $model->save()) {
            $this->saveDemandDeliveryData($model, $post);
            return $this->redirect(['task/view', 'id' => $model->demand_task_id]);
        } else {
            
            return $this->render('create', [
                'model' => $model,
                'workitems' => $model->demandTask->demandWorkitems,
            ]);
        }
    }

    /**
     * Updates an existing DemandDelivery model.
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
     * Deletes an existing DemandDelivery model.
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
     * Finds the DemandDelivery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandDelivery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DemandDelivery::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 保存需求交付数据到表里
     * @param DemandDelivery $model              
     * @param type $post              
     */
    public function saveDemandDeliveryData($model, $post){
        $values = ArrayHelper::getValue($post, 'value');
        $datas = [];
        foreach ($values as $key => $value) {
            if(!is_numeric($value)){  
                if(strpos($value ,":"))  {  
                    $times =  explode(":", $value);  
                }else if(strpos($value ,'：')){  
                    $times =  explode("：", $value);
                }
                $h = (int)$times[0] ;  
                $m = (int)$times[1];  
                $s = count($times) == 3 ? (int)$times[2] : 0;  
                $value = $h * 3600 + $m * 60 + $s;  
            }else{
                $value = (int)$value;
            }
            $datas[] = [
                'demand_delivery_id' => $model->id,
                'demand_workitem_id' => $key,
                'value' => $value,
            ];
        }
        ArrayHelper::multisort($datas, 'demand_workitem_id', SORT_ASC);
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        { 
            if($model !== null && $datas != null){
                /** 添加$values数组到表里 */
                Yii::$app->db->createCommand()->batchInsert(DemandDeliveryData::tableName(), 
                ['demand_delivery_id', 'demand_workitem_id', 'value'], $datas)->execute();
                \Yii::$app->db->createCommand()->update(DemandTask::tableName(), ['status' => DemandTask::STATUS_ACCEPTANCE])->execute();
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage()); 
        }
    }
}
