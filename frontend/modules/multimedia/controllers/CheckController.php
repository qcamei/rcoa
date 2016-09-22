<?php

namespace frontend\modules\multimedia\controllers;

use common\models\multimedia\MultimediaCheck;
use common\models\multimedia\searchs\MultimediaCheckSearch;
use frontend\modules\multimedia\MultimediaTool;
use wskeee\rbac\RbacName;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * CheckController implements the CRUD actions for MultimediaCheck model.
 */
class CheckController extends Controller
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
     * Lists all MultimediaCheck models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MultimediaCheckSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        /*return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);*/
    }

    /**
     * Displays a single MultimediaCheck model.
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
     * Creates a new MultimediaCheck model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($task_id)
    {
        /* @var $multimedia MultimediaTool */
        $multimedia = \Yii::$app->get('multimedia');
        if(!\Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_CREATE_CHECK))
            throw new NotAcceptableHttpException('无权限操作！');
        
        $model = new MultimediaCheck();
        $model->task_id = $task_id;
        $model->create_by = \Yii::$app->user->id;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['default/view', 'id' => $model->task_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'task_id' => $task_id,
            ]);
        }
    }

    /**
     * Updates an existing MultimediaCheck model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if(!\Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_UPDATE_CHECK) && $model->create_by != Yii::$app->user->id)
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing MultimediaCheck model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(!\Yii::$app->user->can(RbacName::PERMSSION_MULTIMEDIA_TASK_DELETE_CHECK) && $model->create_by != Yii::$app->user->id)
            throw new NotAcceptableHttpException('无权限操作！');
        
        $model->delete();
        return $this->redirect(['default/view', 'id' => $model->task_id]);
    }

    /**
     * Finds the MultimediaCheck model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MultimediaCheck the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MultimediaCheck::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('rcoa', 'The requested page does not exist.'));
        }
    }
}
