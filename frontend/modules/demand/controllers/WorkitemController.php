<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandWorkitem;
use common\models\demand\searchs\DemandWorkitemSearch;
use common\models\workitem\Workitem;
use common\models\workitem\WorkitemCabinet;
use common\models\workitem\WorkitemCost;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

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
            //access验证是否有登录
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['list'],
                'rules' => [
                    [
                        'actions' => ['list'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
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
     * 展示样例
     * @return mixed
     */
    public function actionList()
    {
        $cabinets = WorkitemCabinet::find()
                ->select(['workitem_id','path','type'])
                ->where(['is_delete' => 'N'])
                ->asArray()
                ->all();
        
        $models = Workitem::find()
                ->orderBy(['index' => SORT_ASC])
                ->all();
        $costs = WorkitemCost::find()
                ->orderBy(['target_month' => SORT_ASC])
                ->asArray()
                ->all();
        
        $costs = ArrayHelper::index($costs, 'workitem_id');
        $cabinets = ArrayHelper::index($cabinets,null,['workitem_id']);
        return $this->render('list', [
            'models' => $models,
            'costs' => $costs,
            'cabinets' => $cabinets,
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
