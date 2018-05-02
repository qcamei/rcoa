<?php

namespace backend\modules\demand_admin\controllers;

use common\models\demand\DemandWorkitemTemplate;
use common\models\demand\DemandWorkitemTemplateType;
use common\models\demand\searchs\DemandWorkitemTemplateSearch;
use common\models\workitem\Workitem;
use common\models\workitem\WorkitemType;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * WorkitemController implements the CRUD actions for DemandWorkitemTemplate model.
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
     * Lists all DemandWorkitemTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DemandWorkitemTemplateSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DemandWorkitemTemplate model.
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
     * Creates a new DemandWorkitemTemplate model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DemandWorkitemTemplate();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'templateTypes' => $this->getTemplateTypes(),
                'workitemTypes' => $this->getWorkitemTypes(),
                'workitems' => $this->getWorkitems(),
            ]);
        }
    }

    /**
     * Updates an existing DemandWorkitemTemplate model.
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
                'templateTypes' => $this->getTemplateTypes(),
                'workitemTypes' => $this->getWorkitemTypes(),
                'workitems' => $this->getWorkitems(),
            ]);
        }
    }

    /**
     * Deletes an existing DemandWorkitemTemplate model.
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
     * Finds the DemandWorkitemTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandWorkitemTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DemandWorkitemTemplate::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 获取模版类型
     * @return array
     */
    public function getTemplateTypes()
    {
        $templateTypes = DemandWorkitemTemplateType::find()->select(['id', 'name'])->all();
        return ArrayHelper::map($templateTypes, 'id', 'name');
    }
    
    /**
     * 获取工作项类型
     * @return array
     */
    public function getWorkitemTypes()
    {
        $workitemTypes = WorkitemType::find()->select(['id', 'name'])->all();
        return ArrayHelper::map($workitemTypes, 'id', 'name');
    }
    
    /**
     * 获取工作项
     * @return array
     */
    public function getWorkitems()
    {
        $workitems = Workitem::find()->select(['id', 'name'])->all();
        return ArrayHelper::map($workitems, 'id', 'name');
    }
}
