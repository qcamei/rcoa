<?php

namespace frontend\modules\demand\controllers;

use wskeee\framework\FrameworkManager;
use wskeee\framework\models\College;
use wskeee\framework\models\Item;
use wskeee\framework\models\searchs\ItemSearch;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * CollegeController implements the CRUD actions for College model.
 */
class CollegeController extends BasedataController
{
    /**
     * Lists all College models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch(['level'=>  Item::LEVEL_COLLEGE]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single College model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $searchModel = new ItemSearch(['level'=>  Item::LEVEL_PROJECT,'parent_id'=>$id]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider'=>$dataProvider,
        ]);
    }

    /**
     * Creates a new College model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        //parent::actionCreate();
        
        $model = new College();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            /* @var $fwManager FrameworkManager */
            $fwManager = \Yii::$app->fwManager;
            $fwManager->invalidateCache();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing College model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        //parent::actionUpdate($id);
        
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            /* @var $fwManager FrameworkManager */
            $fwManager = \Yii::$app->fwManager;
            $fwManager->invalidateCache();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing College model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //parent::actionDelete($id);
        
        $this->findModel($id)->delete();
        
        /* @var $fwManager FrameworkManager */
        $fwManager = \Yii::$app->fwManager;
        $fwManager->invalidateCache();

        return $this->redirect(['index']);
    }

    /**
     * Finds the College model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return College the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = College::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
