<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandTaskProduct;
use common\models\demand\searchs\DemandTaskProductSearch;
use common\models\product\Product;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ProductController implements the CRUD actions for DemandTaskProduct model.
 */
class ProductController extends Controller
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
     * Lists all DemandTaskProduct models.
     * @return mixed
     */
    public function actionIndex($task_id)
    {
        Yii::$app->getResponse()->format = 'json';
        $dataProducts = $this->getDataProducts($task_id);
        $items = [];
        $errors = [];
        $type = '';
        try
        {
            if(!empty($dataProducts)){
                $items = $dataProducts;
                $type = 1;
            }
        } catch (Exception $ex) {
            $errors [] = $ex->getMessage();
        }
        return [
            'type' => $type,
            'data' => $items,
            'error' => $errors
        ];
    }
    
    /**
     * Lists all DemandTaskProduct models.
     * @return mixed
     */
    public function actionList($task_id)
    {
        return $this->renderAjax('list', [
            'data' => $this->getProducts(),
            'task_id' => $task_id,
        ]);
    }
    
    /**
     * Displays a single DemandTaskProduct model.
     * @param integer $task_id
     * @param integer $product_id
     * @return mixed
     */
    public function actionView($task_id, $product_id)
    {
        $product = Product::findOne(['id' => $product_id]);
        $model = $this->findModel($task_id, $product_id);

        return $this->renderAjax('view', [
            'product' => $product,
            'model' => $model,
            'task_id' => $task_id,
            'product_id' => $product_id,
        ]);
    }

    /**
     * Creates a new DemandTaskProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DemandTaskProduct();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DemandTaskProduct model.
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
     * Deletes an existing DemandTaskProduct model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $task_id
     * @param integer $product_id
     * @return mixed
     */
    public function actionDeletes($task_id, $product_id)
    {
        Yii::$app->getResponse()->format = 'json';
        $model = $this->findModel($task_id, $product_id);
        $type = '';
        $message = '';

        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if ($model->delete() > 0){
               $type = 1;
               $message = '操作成功！';
               $trans->commit();  //提交事务
            }else{
                $type = 0;
                $message = '操作失败！';
                $trans ->rollBack(); //回滚事务
                //throw new \Exception($model->getErrors());
            }
            
            //Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (\Exception $ex) {
            
            //Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
        
        return [
            'type' => $type,
            'error' => $message,
        ];

    }
    
    /**
     * Ajax 动态保存数据库操作
     * @param integer $task_id
     * @param integer $product_id
     * @return json      
     */
    public function actionSave($task_id, $product_id)
    {
        Yii::$app->getResponse()->format = 'json';
        $model = $this->findModel($task_id, $product_id);
        $model->task_id = $task_id;
        $model->product_id = $product_id;
        $type = '';
        $message = '';
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if ($model->load(Yii::$app->request->post()) && $model->save()){
               $type = 1;
               $message = '操作成功！';
               $trans->commit();  //提交事务
            }else{
                $type = 0;
                $message = '操作失败！';
                $trans ->rollBack(); //回滚事务
                //throw new \Exception($model->getErrors());
            }
            
            //Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (\Exception $ex) {
            
            //Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
        
        return [
            'type' => $type,
            'error' => $message,
        ];
    }

    /**
     * Finds the DemandTaskProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $task_id
     * @param integer $product_id
     * @return DemandTaskProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($task_id, $product_id)
    {
        $model = DemandTaskProduct::findOne(['task_id'=> $task_id, 'product_id' => $product_id]);
        if ($model !== null) {
            return $model;
        } else {
            return new DemandTaskProduct();
        }
    }
    
    /**
     * Finds the DemandTaskProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandTaskProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     
    protected function findModel($id)
    {
        if (($model = DemandTaskProduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(Yii::t('rcoa', 'The requested page does not exist.'));
        }
    }*/
    
    /**
     * 获取已创建的课程产品
     * @param integer $taskId              任务ID
     * @return array
     */
    public function getDataProducts($taskId)
    {
        $dataProduct = (new Query())
                ->select([
                    'Task_product.task_id', 'Task_product.product_id', 'Task_product.number',
                    'Product.name', 'Product.unit_price', 'Product.currency', 
                    'Product.image', 'Product.des'
                ])
                ->from(['Task_product' => DemandTaskProduct::tableName()])
                ->leftJoin(['Product' => Product::tableName()], 'Product.id = Task_product.product_id')
                ->where(['Task_product.task_id' => $taskId])
                ->all();
        
        return $dataProduct;
    }

    /**
     * 获取所有目录、产品
     * @return array
     */
    public function getProducts()
    {
        $products = (new Query())->from(Product::tableName())->all();        
        return $this->__getProducts($products);
    }
    
    /**
     * 递归组合产品列表
     * @param array $products           获取所有的产品
     * @param integer $parent_id        父级ID
     * @return array
     */
    private function __getProducts($allProducts, $parent_id = null){
        $products = [];
        foreach ($allProducts AS $id => $product){
            if($product['parent_id'] == $parent_id)
                $products [] = [
                    'id' => $product['id'],  
                    'name' => $product['name'],  
                    'type' => $product['level'] == Product::CLASSIFICATION ? 'dir' : 'content',  
                    'des' => $product['des'],
                    'price' => $product['currency'].$product['unit_price'],
                    'children' => $this->__getProducts($allProducts, $product['id']),
                  ];
        }
        return $products;
    }
}
