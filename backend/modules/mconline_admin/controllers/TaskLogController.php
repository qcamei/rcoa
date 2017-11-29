<?php

namespace backend\modules\mconline_admin\controllers;

use common\models\ScheduledTaskLog;
use common\models\searchs\ScheduledTaskLogSearch;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TaskLogController implements the CRUD actions for ScheduledTaskLog model.
 */
class TaskLogController extends Controller {

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
     * Lists all ScheduledTaskLog models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new ScheduledTaskLogSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'params' => Yii::$app->request->queryParams,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ScheduledTaskLog model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {

        return $this->render('view', 
                $this->searchFileCheck($id)
        );
    }

    /**
     * Displays a single ScheduledTaskLog model.
     * @param string $id
     * @return mixed
     */
    public function actionViewSpace($id) {

        return $this->render('view-space', [
                    'model' => $this->searchSpaceCheck($id),
        ]);
    }

    /**
     * Creates a new ScheduledTaskLog model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ScheduledTaskLog();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ScheduledTaskLog model.
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
     * Deletes an existing ScheduledTaskLog model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ScheduledTaskLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return ScheduledTaskLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ScheduledTaskLog::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 文件到期检查
     * @param int $id
     * @return array
     */
    public function searchFileCheck($id) {
        $query = (new Query())
                ->select(['feedback', 'result'])
                ->from(['ScheduledTaskLog' => ScheduledTaskLog::tableName()])
                ->where(['type' => 1, 'id' => $id])
                ->one();
        if($query['result']){
            $feedback = json_decode($query['feedback'], true);
        }else{
            $feedback = $query['feedback'];
        }
        $dataProvider = new ArrayDataProvider([
            'allModels' => ArrayHelper::getValue($feedback, 'file_results'),
        ]);
        return [
            'model' => $feedback,
            'result' => $query['result'],
            'dataProvider' => $dataProvider
        ];
    }

    /**
     * 空间上限检查
     * @param int $id
     * @return array
     */
    public function searchSpaceCheck($id) {
        $query = (new Query())
                ->select(['feedback', 'result'])
                ->from(['ScheduledTaskLog' => ScheduledTaskLog::tableName()])
                ->where(['type' => 2, 'id' => $id])
                ->one();

        return [
            'result' => $query['result'],
            'feedback' => $query['result'] == 1 ? json_decode($query['feedback'], true) : $query['feedback'],
        ];
    }

}
