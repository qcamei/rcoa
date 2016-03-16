<?php

namespace backend\controllers;

use Yii;
use yii\db\Query;
use yii\web\Controller;

class BannerController extends Controller
{
    public function actionIndex()
    {
        $query = new Query();
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels'=>$query->from('ccoa_banner')->all(),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    /**
     * Creates a new System model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $query = new Query();
        $model = $query->from('ccoa_banner');
        $post = Yii::$app->request->post();
        
        if (!empty($post)) {
            
            return $this->redirect('index');
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing System model.
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
}
