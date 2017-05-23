<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandAppeal;
use common\models\demand\searchs\DemandAppealSearch;
use frontend\modules\demand\utils\DemandTool;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * AppealController implements the CRUD actions for DemandAppeal model.
 */
class AppealController extends Controller
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
     * Lists all DemandAppeal models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DemandAppealSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DemandAppeal model.
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
     * Creates a new DemandAppeal model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($demand_task_id)
    {
        $model = new DemandAppeal();
        $post = Yii::$app->request->post();
        /* @var $rbacManager RbacManager */  
        $rbacManager = \Yii::$app->authManager;
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        $model->demand_task_id = $demand_task_id;
        
        if(!($rbacManager->isRole(RbacName::ROLE_DEMAND_UNDERTAKE_PERSON, \Yii::$app->user->id) 
             && $model->demandTask->undertake_person == Yii::$app->user->id))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->demandTask->getIsStatusWaitConfirm())
            throw new NotAcceptableHttpException('该任务状态为'.$model->demandTask->getStatusName().'！');
        
        $model->reason = ArrayHelper::getValue($post, 'reason');
        $model->create_by = \Yii::$app->user->id;
        
        if ($model->load($post)) {
            $dtTool->CreateAppealTask($model);
            return $this->redirect(['task/view', 'id' => $model->demand_task_id]);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DemandAppeal model.
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

    /**
     * Deletes an existing DemandAppeal model.
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
     * Finds the DemandAppeal model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandAppeal the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DemandAppeal::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
