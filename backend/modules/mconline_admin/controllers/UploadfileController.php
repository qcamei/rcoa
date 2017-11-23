<?php

namespace backend\modules\mconline_admin\controllers;

use backend\components\BaseController;
use common\models\User;
use wskeee\webuploader\models\searchs\UploadfileSearch;
use wskeee\webuploader\models\Uploadfile;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * UploadfileController implements the CRUD actions for Uploadfile model.
 */
class UploadfileController extends BaseController {

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
     * Lists all Uploadfile models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new UploadfileSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'uploadBy' => $this->getUploadBy(),
        ]);
    }

    /**
     * Displays a single Uploadfile model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Uploadfile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Uploadfile();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Uploadfile model.
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
     * Deletes an existing Uploadfile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {

        $model = $this->findModel($id);
        
        $path = Yii::getAlias('@mconline') . '/web/' . $model->path;
        if (file_exists($path)) {
            if (unlink($path)) {
                $model->is_del = 1;
                $model->update();
            }
        } else {
            Yii::$app->getSession()->setFlash('error', '该文件不存在！');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Uploadfile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Uploadfile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Uploadfile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 查询上传者
     * @return array
     */
    public function getUploadBy() {
        $uploadBy = (new Query())
                ->select(['Uploadfile.id', 'Uploadfile.created_by'])
                ->from(['Uploadfile' => Uploadfile::tableName()])
                //关联查询上传者
                ->leftJoin(['CreateBy' => User::tableName()], 'CreateBy.id = Uploadfile.created_by')
                ->addSelect(['CreateBy.nickname AS username'])
                ->groupBy('created_by')
                ->all();

        return ArrayHelper::map($uploadBy, 'created_by', 'username');
    }

}
