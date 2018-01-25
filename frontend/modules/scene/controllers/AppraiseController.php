<?php

namespace frontend\modules\scene\controllers;

use common\models\scene\SceneAppraise;
use common\models\scene\SceneAppraiseTemplate;
use common\models\scene\searchs\SceneAppraiseSearch;
use frontend\modules\scene\utils\SceneBookAction;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * AppraiseController implements the CRUD actions for SceneAppraise model.
 */
class AppraiseController extends Controller
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
     * Lists all SceneAppraise models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SceneAppraiseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SceneAppraise model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new SceneAppraise model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SceneAppraise(Yii::$app->request->queryParams);
        
        if(!($model->book->getIsStausShootIng() || $model->book->getIsAppraise()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->book->getStatusName ().'！');
        
        $searchModel = new SceneAppraiseSearch();
        $appraiseResult = $searchModel->search(Yii::$app->request->queryParams);
        
        if ($model->load(Yii::$app->request->post())) {
            SceneBookAction::getInstance()->CreateSceneAppraise(Yii::$app->request->post());
            return $this->redirect(['scene-book/view', 'id' => $model->book_id]);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
                'subjects' => SceneAppraiseTemplate::find()->all(),
                'appraiseResult' => $searchModel->search(Yii::$app->request->queryParams),
            ]);
        }
    }

    /**
     * Updates an existing SceneAppraise model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
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
     * Deletes an existing SceneAppraise model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the SceneAppraise model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SceneAppraise the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SceneAppraise::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    
}
