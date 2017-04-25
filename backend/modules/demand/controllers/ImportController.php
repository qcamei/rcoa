<?php

namespace backend\modules\demand\controllers;

use common\models\demand\DemandTask;
use common\models\demand\DemandWorkitem;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * WorkitemController implements the CRUD actions for DemandWorkitemTemplate model.
 */
class ImportController extends Controller
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
     * Lists all DemandWorkitemTemplate models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->findDemandWorkitem();
        return $this->render('index', [
            
        ]);
    }
    
    public function saveDemandWorkitem()
    {
        
    }
    
    public function findDemandTask()
    {
        $task = (new Query())
                ->select(['Demand_task.id'])
                ->from(['Demand_task' => DemandTask::tableName()])
                ->all();
        
        return ArrayHelper::map($task, 'id', 'id');
    }
    
    public function findDemandWorkitem()
    {
        $workitem = (new Query())
                    ->select(['Demand_worekitem.demand_task_id'])
                    ->from(['Demand_worekitem' => DemandWorkitem::tableName()])
                    ->all();
        
        return ArrayHelper::map($workitem, 'demand_task_id', 'demand_task_id');
    }
    
    public function find
}
