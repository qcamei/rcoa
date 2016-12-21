<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandTaskProduct;
use common\models\product\Product;
use Yii;
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
     * Index all DemandTaskProduct models.
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'data' => $this->getProducts(),
        ]);
    }
    
    /**
     * Lists all DemandTaskProduct models.
     * @return mixed
     */
    public function actionList()
    {
        return $this->renderAjax('list', [
            'data' => $this->getProducts(),
        ]);
    }

    /**
     * Displays a single DemandTaskProduct model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findProductModel($id);
        return $this->renderAjax('view', [
            'model' => $model,
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
            return $this->render('create', [
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
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DemandTaskProduct model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandTaskProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DemandTaskProduct::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('rcoa', 'The requested page does not exist.'));
        }
    }
    
    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandTaskProduct the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findProductModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('rcoa', 'The requested page does not exist.'));
        }
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
