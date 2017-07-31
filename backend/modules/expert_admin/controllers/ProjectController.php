<?php

namespace backend\modules\expert_admin\controllers;

use common\models\expert\ExpertProject;
use common\models\shoot\searchs\ExpertProjectSearch;
use wskeee\framework\FrameworkManager;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ProjectController implements the CRUD actions for ExpertProject model.
 */
class ProjectController extends Controller
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
     * Lists all ExpertProject models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExpertProjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExpertProject model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        throw new NotFoundHttpException("找不到页面！");
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ExpertProject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = \Yii::$app->fwManager;
        
        $model = new ExpertProject(\Yii::$app->getRequest()->getQueryParams());
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
            return $this->redirect(['/expert/default/view', 'id' => $model->expert_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'projects' => ArrayHelper::map($fwManager->getProjects(), 'id', 'name'),
            ]);
        }
    }

    /**
     * Updates an existing ExpertProject model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        /* @var $fwManager FrameworkManager */
        $fwManager = \Yii::$app->fwManager;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/expert/default/view', 'id' => $model->expert_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'projects' => ArrayHelper::map($fwManager->getProjects(), 'id', 'name'),
            ]);
        }
    }

    /**
     * Deletes an existing ExpertProject model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $expert_id = $model->expert_id;
        $model->delete();
        return $this->redirect(['/expert/default/view','id'=>$expert_id]);
    }

    /**
     * Finds the ExpertProject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExpertProject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExpertProject::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
