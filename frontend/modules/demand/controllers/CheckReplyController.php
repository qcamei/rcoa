<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandCheck;
use common\models\demand\DemandCheckReply;
use common\models\demand\searchs\DemandCheckReplySearch;
use frontend\modules\demand\utils\DemandTool;
use wskeee\rbac\RbacName;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * CheckReplyController implements the CRUD actions for DemandCheckReply model.
 */
class CheckReplyController extends Controller
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
     * Lists all DemandCheckReply models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DemandCheckReplySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DemandCheckReply model.
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
     * Creates a new DemandCheckReply model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($demand_task_id, $pass)
    {
        $model = new DemandCheckReply();
        $model->loadDefaultValues();
        $check = $this->findCheckModel($demand_task_id);
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        $model->demand_check_id = $check->id;
        $model->create_by = \Yii::$app->user->id;
        
        if(!(\Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_CREATE_CHECK) 
            && $dtTool->getIsAuditor($model->demandCheck->demandTask->create_team)))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!($model->demandCheck->demandTask->getIsStatusCheck() || $model->demandCheck->demandTask->getIsStatusChecking()))
            throw new NotAcceptableHttpException('该任务状态为'.$model->demandCheck->demandTask->getStatusName().'！');
        
        if ($model->load(Yii::$app->request->post())) {
            if($pass == true){
                $dtTool->PassCheckReplyTask($model);
                return $this->redirect(['task/index', 'auditor' => Yii::$app->user->id]);
            }
            else{
                $dtTool->CreateCheckReplyTask($model);
                return $this->redirect(['task/view', 'id' => $demand_task_id]);
            }
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
                'pass' => $pass,
            ]);
        }
    }

    /**
     * Updates an existing DemandCheckReply model.
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
     * Deletes an existing DemandCheckReply model.
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
     * Finds the DemandCheckReply model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandCheckReply the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DemandCheckReply::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Finds the DemandCheck model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $demand_task_id
     * @return DemandCheckReply the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCheckModel($demand_task_id)
    {
        $check = DemandCheck::find()
                ->where(['demand_task_id' => $demand_task_id])
                ->orderBy('id desc')
                ->one();
        
        if ($check !== null) {
            return $check;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
