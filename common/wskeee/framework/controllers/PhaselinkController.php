<?php

namespace common\wskeee\framework\controllers;

use Yii;
use wskeee\framework\models\PhaseLink;
use wskeee\framework\models\searchs\PhaseLinkSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PhaselinkController implements the CRUD actions for PhaseLink model.
 */
class PhaselinkController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all PhaseLink models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PhaseLinkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PhaseLink model.
     * @param integer $phases_id
     * @param integer $link_id
     * @return mixed
     */
    public function actionView($phases_id, $link_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($phases_id, $link_id),
        ]);
    }

    /**
     * Creates a new PhaseLink model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PhaseLink();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'phases_id' => $model->phases_id, 'link_id' => $model->link_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PhaseLink model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $phases_id
     * @param integer $link_id
     * @return mixed
     */
    public function actionUpdate($phases_id, $link_id)
    {
        $model = $this->findModel($phases_id, $link_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'phases_id' => $model->phases_id, 'link_id' => $model->link_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PhaseLink model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $phases_id
     * @param integer $link_id
     * @return mixed
     */
    public function actionDelete($phases_id, $link_id)
    {
        $this->findModel($phases_id, $link_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PhaseLink model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $phases_id
     * @param integer $link_id
     * @return PhaseLink the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($phases_id, $link_id)
    {
        if (($model = PhaseLink::findOne(['phases_id' => $phases_id, 'link_id' => $link_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
