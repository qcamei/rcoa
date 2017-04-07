<?php

namespace frontend\modules\demand\controllers;

use Yii;
use common\models\demand\DemandWorkitem;
use common\models\demand\searchs\DemandWorkitemSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WorkitemController implements the CRUD actions for DemandWorkitem model.
 */
class WorkitemController extends Controller
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
     * Lists all DemandWorkitem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DemandWorkitemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DemandWorkitem model.
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
     * Creates a new DemandWorkitem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DemandWorkitem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DemandWorkitem model.
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
     * Deletes an existing DemandWorkitem model.
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
     * Ajax 动态保存数据库操作
     * @param integer $task_id
     * @param integer $product_id
     * @return json      
     */
    public function actionSave()
    {
        /*$model = $this->findModel($task_id, $product_id);
        $post = Yii::$app->request->post();
        $number = ArrayHelper::getValue($post, 'DemandTaskProduct.number');
        $lessonsTotal = ArrayHelper::getValue($this->getProductTotal($task_id), 'lessons');
        $model->task_id = $task_id;
        $model->product_id = $product_id;
        $oldnumber = $model->number;*/
        
        /** 开启事务 
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if ($model->load($post) && $model->save() && $model->task->lesson_time >= $lessonsTotal - $oldnumber + $number
                && \Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_CREATE_PRODUCT) && $model->task->create_by == \Yii::$app->user->id)
            {
                if($number == 0)
                    $model->delete();
                $trans->commit();  //提交事务
                Yii::$app->getSession()->setFlash('success','操作成功！');
                return $this->redirect(['list', 'task_id' => $model->task_id]);
            }else{
                //throw new \Exception($model->getErrors());
                Yii::$app->getSession()->setFlash('error', '产品总学时不能超过需求任务学时');
                return $this->redirect(['view', 'task_id' => $task_id, 'product_id' => $product_id]);
            }
            
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
        }*/
        
    }

    /**
     * Finds the DemandWorkitem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandWorkitem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DemandWorkitem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
