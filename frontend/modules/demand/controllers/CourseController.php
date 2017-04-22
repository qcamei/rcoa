<?php

namespace frontend\modules\demand\controllers;

use wskeee\framework\FrameworkManager;
use wskeee\framework\models\Course;
use wskeee\framework\models\Item;
use wskeee\framework\models\searchs\CourseSearch;
use wskeee\framework\models\searchs\ItemSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * CourseController implements the CRUD actions for Course model.
 */
class CourseController extends BasedataController
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
        ];
    }

    /**
     * Lists all Course models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CourseSearch(['level' => Item::LEVEL_COURSE]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'rbac' => $this->getRbac(),
        ]);
    }

    /**
     * Displays a single Course model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'rbac' => $this->getRbac(),
        ]);
    }

    /**
     * Creates a new Course model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        parent::actionCreate();
        
        $model = new Course();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            /* @var $fwManager FrameworkManager */
            $fwManager = \Yii::$app->fwManager;
            $fwManager->invalidateCache();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->parent_id = Yii::$app->getRequest()->getQueryParam('parent_id');
            $colleges = $this->getParents(Item::LEVEL_COLLEGE);
            $projects = $model->parent_id ? $this->getParents(Item::LEVEL_PROJECT,$model->parent->parent_id) : [];
            return $this->render('create', [
                'model' => $model,
                'colleges' => $colleges,
                'projects' => $projects,
            ]);
        }
    }

    /**
     * Updates an existing Course model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        parent::actionUpdate($id);
        
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            /* @var $fwManager FrameworkManager */
            $fwManager = \Yii::$app->fwManager;
            $fwManager->invalidateCache();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $colleges = $this->getParents(Item::LEVEL_COLLEGE);
            $projects = $model->parent_id ? $this->getParents(Item::LEVEL_PROJECT,$model->parent->parent_id) : [];
            return $this->render('update', [
                'model' => $model,
                'colleges' => $colleges,
                'projects' => $projects,
            ]);
        }
    }

    /**
     * Deletes an existing Course model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id,$callback=null)
    {
        parent::actionDelete($id);
        
        $this->findModel($id)->delete();
        /* @var $fwManager FrameworkManager */
        $fwManager = \Yii::$app->fwManager;
        $fwManager->invalidateCache();
        return $this->redirect([$callback ? $callback : 'index']);
    }
    
    /**
     * 获取基础数据
     * @param int $level        基础数据类型
     * @param int $parent_id    父级id
     * @return array
     */
    public function actionSearch($level=null,$parent_id=null)
    {
        \Yii::$app->getResponse()->format = 'json';
        $code = 0;
        $msg = '';
        $data = [];
        if(!$level && !$parent_id){
            $code = 1;
            $msg = '缺少参数，level和parent_id必须指定一个！';
        }else
            $data = $this->getParents($level, $parent_id);
        return [
            'code'=>$code,
            'data'=>$data,
            'msg'=>$msg,
        ];
    }

    /**
     * Finds the Course model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Course the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Course::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
     /**
     * 获取所有子项目数据
     */
    protected function getParents($level=Item::LEVEL_PROJECT,$parent_id=null)
    {
        $searchModel = new ItemSearch(['level' => $level,'parent_id'=>$parent_id]);
        $results = $searchModel->search([])->query->all();
        $parents = ArrayHelper::map($results, 'id', 'name');
        return $parents;
    }
}
