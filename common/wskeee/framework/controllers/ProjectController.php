<?php

namespace wskeee\framework\controllers;

use wskeee\framework\FrameworkManager;
use wskeee\framework\models\Item;
use wskeee\framework\models\Project;
use wskeee\framework\models\searchs\ItemSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ProjectController implements the CRUD actions for Project model.
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
     * Lists all Project models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch(['level'=>  Item::LEVEL_PROJECT]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Project model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new ItemSearch(['level'=>  Item::LEVEL_COURSE,'parent_id' => $id]);
        $childs = $searchModel->search([]);
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $childs
        ]); 
    }

    /**
     * Creates a new Project model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Project();
                
        if ($model->load(Yii::$app->request->post()) && $model->save()) 
        {
            /* @var $fwManager FrameworkManager */
            $fwManager = \Yii::$app->fwManager;
            $fwManager->invalidateCache();
            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            
            $model->parent_id = Yii::$app->getRequest()->getQueryParams('parent_id');
            return $this->render('create', [
                'model' => $model,
                'parents' => $this->getParents()
            ]);
        }
    }

    /**
     * Updates an existing Project model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            /* @var $fwManager FrameworkManager */
            $fwManager = \Yii::$app->fwManager;
            $fwManager->invalidateCache();
            
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'parents' => $this->getParents()
            ]);
        }
    }

    /**
     * Deletes an existing Project model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = \Yii::$app->fwManager;

        $this->findModel($id)->delete();

        $fwManager->invalidateCache();
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the Project model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Project the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 获取所有学院数据
     */
    protected function getParents()
    {
        $searchModel = new ItemSearch(['level' => Item::LEVEL_COLLEGE]);
        $results = $searchModel->search([])->models;
        $parents = ArrayHelper::map($results, 'id', 'name');
        return $parents;
    }
}
