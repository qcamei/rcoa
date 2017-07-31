<?php

namespace backend\modules\teamwork_admin\controllers;

use common\models\teamwork\Link;
use common\models\teamwork\Phase;
use common\models\teamwork\searchs\LinkSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * LinkController implements the CRUD actions for Link model.
 */
class LinkController extends Controller
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
     * Lists all Link models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LinkSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Link model.
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
     * Creates a new Link model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Link();
        $post = Yii::$app->request->post();
        $model->loadDefaultValues();
        unset($post['Link']['phase_id']);
        $model->phase_id  = Yii::$app->request->queryParams['phase_id'];
        $model->create_by = Yii::$app->user->id;
        $model->template_type_id = $model->phase->template_type_id;
        
        if ($model->load($post) && $model->save()) {
            
            return $this->redirect(['/teamwork/phase/view', 'id' => $model->phase_id]);
        } else {
            
            return $this->render('create', [
                'model' => $model,
                //'phaseId' => $phaseId,
                //'phases' => ArrayHelper::map(Phase::findOne(['id' => $phaseId]), 'id', 'name'),
            ]);
        }
    }

    /**
     * Updates an existing Link model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->phase_id = $model->phase_id;
        $model->create_by = Yii::$app->user->id;
        $post = Yii::$app->request->post();
        $model->template_type_id = $model->phase->template_type_id;
        unset($post['Link']['phase_id']);
       
        if ($model->load($post) && $model->save()) {
            return $this->redirect(['/teamwork/phase/view', 'id' => $model->phase_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                //'phases' => ArrayHelper::map(Phase::find()->all(), 'id', 'name'),
            ]);
        }
    }

    /**
     * Deletes an existing Link model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        return $this->redirect(['/teamwork/phase/view', 'id' => $model->phase_id]);
    }

    /**
     * Finds the Link model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Link the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Link::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
