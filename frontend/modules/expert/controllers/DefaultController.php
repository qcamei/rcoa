<?php

namespace frontend\modules\expert\controllers;

use common\models\expert\Expert;
use common\models\expert\ExpertProject;
use common\models\expert\ExpertType;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for Expert model.
 */
class DefaultController extends Controller
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
     * Lists all Expert models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = ExpertType::find()->all();
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Expert model.
     * @param integer $id
     * @return mixed
     */
    public function actionType($id)
    {       
        $model = $this->findModel(['type' => $id]);
        $modelExpert = $this->findExpert(['type' => $id]);
        //var_dump($model);exit;
        
        return $this->render('type', [
            'model' => $model,
            'modelExpert' => $modelExpert,
            
        ]);
    }
    
    /**
     * Displays a single Expert model.
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
     * Creates a new Expert model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Expert();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->u_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Expert model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->u_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Expert model.
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
     * 用ajax调用专家库 
     * @param type $id
     * @return type
     */
    public function actionSearch($id)
    {
        \Yii::$app->getResponse()->format = 'json';
        
        $expert = Expert::findOne($id);
        
        return [
            'result' => 0/1,
            'data'=>[
                'img' => $expert->personal_image,
                'phone' => $expert->user->phone,
                'email' => $expert->user->email,
            ]
        ];
    }
    
    /**
     * Finds the Expert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Expert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Expert::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Finds the Expert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $cons
     * @return Expert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findExpert($cons)
    {
        $modelExpert = Expert::find()
                ->where($cons)
                ->all();
        if ($modelExpert !== null) {
            return $modelExpert;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
