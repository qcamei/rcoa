<?php

namespace backend\modules\team_admin\controllers;

use common\models\team\searchs\TeamCategorySearch;
use common\models\team\TeamCategory;
use common\models\team\TeamCategoryMap;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TeamCategoryController implements the CRUD actions for TeamCategory model.
 */
class TeamCategoryController extends Controller
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
     * Lists all TeamCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TeamCategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TeamCategory model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        $mpas = TeamCategoryMap::find()
                ->where(['category_id'=>$id,'is_delete'=>'N'])
                ->with('team')
                ->orderBy('index')
                ->all();
        $children = new ArrayDataProvider([
                    'allModels' => $mpas,
                ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'children'=> $children,
        ]);
    }

    /**
     * Creates a new TeamCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TeamCategory();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            TeamMemberTool::getInstance()->invalidateCache();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $model->loadDefaultValues();
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing TeamCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            TeamMemberTool::getInstance()->invalidateCache();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing TeamCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_delete = 'Y';
        if ($model->save()) {
            TeamMemberTool::getInstance()->invalidateCache();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the TeamCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TeamCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TeamCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
