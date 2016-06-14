<?php

namespace common\wskeee\filemanage\controllers;

use wskeee\filemanage\FileManageTool;
use wskeee\filemanage\models\FileManage;
use wskeee\filemanage\models\searchs\FileManageSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * FileController implements the CRUD actions for FileManage model.
 */
class FileController extends Controller
{
    public function behaviors()
    {
        return [
            //验证delete时为post传值
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
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
     * Lists all FileManage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $get = Yii::$app->request->queryParams;
        /* @var $fileManage FileManageTool */
        $fileManage = Yii::$app->get('fileManage');
        if (!$fileManage->isFmOwner(!isset($get['id'])? null : $get['id']))
            throw new UnauthorizedHttpException('无访问权限！');
        
        $dataProvider = new ActiveDataProvider([
            'query' => FileManage::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'get' => $get,
            'bread' => $fileManage->getFileManageBread(!isset($get['id'])? null : $get['id']),
            'list' => $fileManage->getFileManageLeftList(!isset($get['id'])? null : $get['id']),
        ]);
    }

    /**
     * Displays a single FileManage model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $get = Yii::$app->request->queryParams;
        /* @var $fileManage FileManageTool */
        $fileManage = Yii::$app->get('fileManage');
        if (!$fileManage->isFmOwner($id))
            throw new UnauthorizedHttpException('无访问权限！');
       
        return $this->render('view', [
            'model' => $this->findModel($id),
            'get' => $get,
            'bread' => $fileManage->getFileManageBread($id),
            'list' => $fileManage->getFileManageLeftList($id),
        ]);
    }

    /**
     * Creates a new FileManage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FileManage();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing FileManage model.
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
     * Lists all FileManage models.
     * @return mixed
     */
    public function actionSearch($keyword)
    {
        /* @var $fileManage FileManageTool */
        $fileManage = Yii::$app->get('fileManage');
        return $this->render('search', [
            'keyword' => $keyword,
            'fmSearch' => $this->findCategories($keyword, 'keyword'),
            'fileManage' => $fileManage,
        ]);
    }

    /**
     * Deletes an existing FileManage model.
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
     * Finds the FileManage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FileManage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FileManage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 搜索关键字
     * @param type $keyword     关键字
     * @param type $fieldName   字段名
     * @return type
     * @throws NotFoundHttpException
     */
    protected function findCategories($keyword, $fieldName = null){
        $model = FileManage::find();
        $model->FilterWhere(['like', $fieldName, $keyword]);
        $data = $model -> all();
        if ($data !== null) {
            return $data;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
