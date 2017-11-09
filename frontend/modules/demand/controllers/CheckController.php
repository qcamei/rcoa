<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandCheck;
use common\models\demand\DemandCheckReply;
use common\models\demand\DemandTask;
use common\models\User;
use frontend\modules\demand\utils\DemandAction;
use frontend\modules\demand\utils\DemandTool;
use wskeee\rbac\RbacName;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * CheckController implements the CRUD actions for DemandCheck model.
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
        ];
    }

    /**
     * Lists all DemandCheck models.
     * @return mixed
     */
    public function actionIndex($task_id)
    {
        return $this->renderAjax('index', [
            'checks' => $this->getDemandCheck($task_id),
            'checkReplies' => $this->getDemandCheckReply($task_id)
        ]);
    }

    /**
     * Displays a single DemandCheck model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DemandCheck model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($task_id)
    {
        $model = new DemandCheck();
        $model->loadDefaultValues();
        $model->demand_task_id = $task_id;
        $model->create_by = \Yii::$app->user->id;
        if($model->demandTask->create_by == \Yii::$app->user->id){
            if(!($model->demandTask->getIsStatusDefault() || $model->demandTask->getIsStatusAdjusimenting()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->demandTask->getStatusName().'！');
        }else {
            throw new NotAcceptableHttpException('无权限操作！');
        }
      
        if ($model->load(Yii::$app->request->post())) {
            DemandAction::getInstance()->DemandCreateCheck($model);
            return $this->redirect(['task/view', 'id' => $model->demand_task_id]);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }
    }
    
    /**
     * Updates an existing DemandCheck model.
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
     * Deletes an existing DemandCheck model.
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
     * Finds the DemandCheck model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DemandCheck the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DemandCheck::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
        
    /**
     * 获取审核记录数据
     * @param integer $demand_task_id           需求任务ID
     * @return array
     */
    public function getDemandCheck($demand_task_id)
    {
        $results = (new Query())
                ->select([
                    'Demand_check.id', 'Demand_check.title', 'Demand_check.content', 'Demand_check.des',
                    'User.nickname', 'Demand_check.created_at'
                ])
                ->from(['Demand_check' => DemandCheck::tableName()])
                ->leftJoin(['User' => User::tableName()], 'User.id = Demand_check.create_by')
                ->where(['Demand_check.demand_task_id' => $demand_task_id])
                ->all();
        
        $checks = [];
        foreach ($results as $data) {
            $checks[] = [
                'id' => $data['id'],
                'title' => $data['title'],
                'content' => !empty($data['content']) ? $data['content'] : '无',
                'des' => $data['des'],
                'name' => $data['nickname'],
                'time' => date('Y-m-d H:i', $data['created_at']),
            ];
        }
        
        return $checks;
    }
    
    /**
     * 获取审核回复数据
     * @param integer $demand_task_id           需求任务ID
     * @return array
     */
    public function getDemandCheckReply($demand_task_id)
    {
        $results = (new Query())
                ->select([
                    'Demand_check_reply.id', 'Demand_check_reply.demand_check_id AS check_id',
                    'Demand_check_reply.title', 'Demand_check_reply.pass', 'Demand_check_reply.des',
                    'User.nickname', 'Demand_check_reply.created_at'
                ])
                ->from(['Demand_check_reply' => DemandCheckReply::tableName()])
                ->leftJoin(['Demand_check' => DemandCheck::tableName()], 'Demand_check.id = Demand_check_reply.demand_check_id')
                ->leftJoin(['User' => User::tableName()], 'User.id = Demand_check_reply.create_by')
                ->where(['Demand_check.demand_task_id' => $demand_task_id])
                ->all();
        
        $checkReplies = [];
        foreach ($results as $data) {
            $checkReplies[$data['check_id']] = [
                'id' => $data['id'],
                'title' => $data['title'],
                'pass' => $data['pass'],
                'des' => $data['des'],
                'name' => $data['nickname'],
                'time' => date('Y-m-d H:i', $data['created_at']),
            ];
        }
        
        return $checkReplies;
    }
}
