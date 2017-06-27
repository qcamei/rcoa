<?php

namespace backend\modules\worksystem\controllers;

use common\models\worksystem\searchs\WorksystemAttributestSearch;
use common\models\worksystem\WorksystemAttributes;
use common\models\worksystem\WorksystemTaskType;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * AttributesController implements the CRUD actions for WorksystemAttributes model.
 */
class AttributesController extends Controller
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
     * Lists all WorksystemAttributes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorksystemAttributestSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WorksystemAttributes model.
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
     * Creates a new WorksystemAttributes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WorksystemAttributes();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'taskTypes' => $this->getWorksystemTaskTypes(),
            ]);
        }
    }

    /**
     * Updates an existing WorksystemAttributes model.
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
                'taskTypes' => $this->getWorksystemTaskTypes(),
            ]);
        }
    }

    /**
     * Deletes an existing WorksystemAttributes model.
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
     * Finds the WorksystemAttributes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorksystemAttributes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorksystemAttributes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 获取所有工作系统任务类别
     * @return array
     */
    public function getWorksystemTaskTypes()
    {
        $taskTypes = (new Query())
                    ->select(['id', 'name'])
                    ->from(WorksystemTaskType::tableName())
                    ->all();
        
        return ArrayHelper::map($taskTypes, 'id', 'name');
    }
}
