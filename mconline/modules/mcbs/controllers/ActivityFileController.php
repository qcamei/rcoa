<?php

namespace mconline\modules\mcbs\controllers;

use common\models\mconline\McbsActivityFile;
use common\models\mconline\searchs\McbsActivityFileSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ActivityFileController  implements the CRUD actions for McbsActivityFile model.
 */
class ActivityFileController extends Controller {

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
     * Lists all McbsActivityFile models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new McbsActivityFileSearch();
        $dataProvider = $searchModel->searchFileList(Yii::$app->request->queryParams);
        
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single McbsActivityFile model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new McbsActivityFile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new McbsActivityFile();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing McbsActivityFile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
     * Deletes an existing McbsActivityFile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /*
     * 下载 
     * 如果不需要查数据库的话直接做参数传递  
     * yii::app ()->request->sendFile (文件名,  file_get_contents (文件路径));  
     */
    public function actionDownload($id) {
        if (isset($_GET['id'])) {
            $model = new McbsActivityFileSearch(); //你的model  
            $result = $model->find(array(
                'select' => array('Uploadfile.path', 'Uploadfile.name'),
                'condition' => 'id=:id', //条件  
                'params' => array(':id' => $id)
            ));
            if (!$result) {
                throw new CHttpException(404, '文件不存在！');
            } else {
                // 服务器端文件的路径   
                $fontArr = explode('/', $result->url);
                $fileName = end($fontArr); //得到文件名字  
                if (file_exists($result->url)) {
                    //发送两个参数一个是名称上面已经处理好，也可以改成你要的，后面是文件路径  
                    yii::app()->request->sendFile($fileName, file_get_contents($result->url));
                }
            }
        }
    }

    /**
     * Finds the McbsActivityFile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return McbsActivityFile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = McbsActivityFile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
