<?php

namespace backend\modules\product\controllers;

use common\models\product\ProductDetails;
use common\models\product\searchs\ProductDetailsSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * DetailsController implements the CRUD actions for ProductDetails model.
 */
class DetailsController extends Controller
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
     * Lists all ProductDetails models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductDetailsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ProductDetails model.
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
     * Creates a new ProductDetails model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($product_id)
    {
        $model = new ProductDetails();
        $model->loadDefaultValues();
        $post = Yii::$app->request->post();
        $model->product_id = $product_id;
        
        if ($model->load(Yii::$app->request->post())) {
            $this->Upload($model);
            return $this->redirect(['default/view', 'id' => $model->product_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'productId' => $product_id,
            ]);
        }
    }

    /**
     * Updates an existing ProductDetails model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        $upload = UploadedFile::getInstance($model, 'details');  
        if($upload != null){
            $model->uploadpath = $this->fileExists(Yii::getAlias('@filedata').'/product/'.date('Y-m-d', time()).'/');
            $upload->saveAs($model->uploadpath .$upload->name);
            Yii::$app->db->createCommand()->update(ProductDetails::tableName(), 
                ['details' =>  '/filedata/product/'.date('Y-m-d', time()).'/'.$upload->name, 'index' => ArrayHelper::getValue($post, 'ProductDetails.index')], ['id' => $id])->execute();
        }
        
        if ($model->load($post)) {
            return $this->redirect(['default/view', 'id' => $model->product_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ProductDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['default/view', 'id' => $model->product_id]);
    }

    /**
     * Finds the ProductDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ProductDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ProductDetails::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('rcoa', 'The requested page does not exist.'));
        }
    }
    
    /**
     * 
     * @param ProductDetails $model
     */
    public function Upload($model){
        $upload = UploadedFile::getInstances($model, 'details');  
            
        if($upload != null && $model->validate()){
            $values = [];
            $model->uploadpath = $this->fileExists(Yii::getAlias('@filedata').'/product/'.date('Y-m-d', time()).'/');
            foreach ($upload as $index => $fl){
                $fl->saveAs($model->uploadpath .$fl->baseName. '.' . $fl->extension);
                $values[] = [
                        'product_id' => $model->product_id,
                        'created_at' => time(),
                        'updated_at' => time(),
                        'details' => '/filedata/product/'.date('Y-m-d', time()).'/'.$fl->baseName.'.'.$fl->extension,
                        'index' => $index,
                    ];

            }
            Yii::$app->db->createCommand()->batchInsert(ProductDetails::tableName(), 
                ['product_id', 'created_at', 'updated_at', 'details', 'index'], $values)->execute();
        }
            
    }
    
    /**
     * 检查目标路径是否存在，不存即创建目标
     * @param string $uploadpath    目录路径
     * @return string
     */
    private function fileExists($uploadpath) {

        if (!file_exists($uploadpath)) {
            mkdir($uploadpath);
        }
        return $uploadpath;
    }
    
}   
