<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandTaskProduct;
use common\models\product\Product;
use frontend\modules\demand\utils\DemandQuery;
use wskeee\rbac\RbacName;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
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
     * @param integer $task_id
     * @return mixed
     */
    public function actionList($task_id)
    {
        $productTotal = $this->getProductTotal($task_id);
        
        return $this->renderAjax('list', [
            'data' => $this->getProducts(),
            'totals' => !empty(ArrayHelper::getValue($productTotal, 'totals')) ? ArrayHelper::getValue($productTotal, 'totals') : 0,
            'lessons' => !empty(ArrayHelper::getValue($productTotal, 'lessons')) ? ArrayHelper::getValue($productTotal, 'lessons') : 0,
            'task_id' => $task_id,
        ]);
    }
    
    /**
     * Index all DemandTaskProduct models.
     * @param integer $task_id
     * @return mixed
     */
    public function actionIndex($task_id)
    {
        $model = new DemandTaskProduct();
        $productTotal = $this->getProductTotal($task_id);
        $model->task_id = $task_id;
        
        return $this->renderPartial('index', [
            'model' => $model,
            'data' => $this->getDataProducts($task_id),
            'totals' => !empty(ArrayHelper::getValue($productTotal, 'totals')) ? ArrayHelper::getValue($productTotal, 'totals') : 0,
            'lessons' => !empty(ArrayHelper::getValue($productTotal, 'lessons')) ? ArrayHelper::getValue($productTotal, 'lessons') : 0,
            'totalPrice' => $this->getProductTotalPrice($task_id),
            'mark' => $model->task->getIsStatusDefault() || $model->task->getIsStatusAdjusimenting() ? true : false,
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
        $productTotal = $this->getProductTotal($task_id);
        $model->task_id = $task_id;
        
        return $this->renderAjax('view', [
            'product' => $product,
            'model' => $model,
            'totals' => !empty(ArrayHelper::getValue($productTotal, 'totals')) ? ArrayHelper::getValue($productTotal, 'totals') : 0,
            'task_id' => $task_id,
            'product_id' => $product_id,
            'mark' => $model->task->getIsStatusDefault() || $model->task->getIsStatusAdjusimenting() ? true : false,
        ]);
    }

    /**
     * Creates a new DemandTaskProduct model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     
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
    }*/

    /**
     * Updates an existing DemandTaskProduct model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     
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
    }*/

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
            if ($model->delete() > 0 && \Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_DELETE_PRODUCT) 
                && $model->task->create_by == \Yii::$app->user->id)
            {
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
            if ($model->load(Yii::$app->request->post()) && $model->save() 
                && \Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_CREATE_PRODUCT) 
                && $model->task->create_by == \Yii::$app->user->id)
            {
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
     * 获取课程产品总额和总学时
     * @param integer $taskId              任务ID
     * @return  array
     */
    public function getProductTotal($taskId)
    {
        /* @var $dtQuery DemandQuery */
        $dtQuery = DemandQuery::getInstance();
        /* @var $results ActiveQuery */
        $results = $dtQuery->getProductTotal();
        $results->where(['Task_product.task_id' => $taskId]);
        return $results->one();
    }
    
    /**
     * 获取每个课程产品总额
     * @param integer $taskId               任务ID
     * @return  array
     */
    public function getProductTotalPrice($taskId)
    {
        /* @var $dtQuery DemandQuery */
        $dtQuery = DemandQuery::getInstance();
        /* @var $results ActiveQuery */
        $results = $dtQuery->getProductTotal();
        $results->select(['(Product.unit_price * Task_product.number) AS goods_total', 'Task_product.product_id']);
        $results->where(['Task_product.task_id' => $taskId]);
        $results->groupBy('Task_product.product_id');
        return ArrayHelper::map($results->all(), 'product_id', 'goods_total');
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
