<?php

namespace backend\modules\demand\controllers;

use common\models\demand\DemandTask;
use common\models\demand\DemandWeight;
use common\models\demand\DemandWeightTemplate;
use common\models\demand\DemandWorkitem;
use common\models\demand\DemandWorkitemTemplate;
use common\models\workitem\WorkitemCost;
use wskeee\framework\models\Course;
use Yii;
use yii\db\Exception;
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
    public function actionIndex($carry_out = false)
    {
        if($carry_out == true){
            $workitemResult = $this->saveDemandWorkitem();
            $weightResult = $this->saveDemandWeight();

            if($workitemResult['num'] > 0 && $weightResult['num'] > 0){
                Yii::$app->getSession()->setFlash('success','操作成功！');
            }else if($workitemResult['num'] == 0 && $weightResult['num'] == 0){
                Yii::$app->getSession()->setFlash('info','没有添加数据！');
            }else{
                Yii::$app->getSession()->setFlash('error','操作失败！');
            }
            return $this->redirect(['index', 'carry_out' => false]);
        }else{
            return $this->render('index');
        }
    }
    
    /**
     * 保存数据到需求工作项里面
     * @return message
     */
    public function saveDemandWorkitem()
    {
        $tasks = $this->findDemandTask();
        $workitem_template = $this->findDemandWorkitemTemplate();
        $workitems = [];
        foreach ($tasks as $data) {
            if($data['is_new'] == true){
                foreach ($workitem_template as $workitem) {
                    unset($workitem['id']);
                    $workitem += [
                        'demand_task_id' => $data['id'], 
                        'value' => null,
                        'created_at' => (int)$data['created_at'],
                        'updated_at' => time(),
                    ];
                    $workitems[] = $workitem;
                }
            }
        }
        
        $result = $this->batchInsert(DemandWorkitem::tableName(), [
            'workitem_type_id',  'workitem_id', 'is_new',  
            'value_type', 'index', 'cost', 'demand_task_id', 
            'value', 'created_at', 'updated_at'
        ], $workitems);

        return $result;
        
    }
    
    /**
     * 保存数据到需求权重表
     * @return message
     */
    public function saveDemandWeight()
    {
        $tasks = $this->findDemandTask();
        $weight_template = $this->findDemandWeightTemplate();
        $weights = [];
        foreach ($tasks as $data) {
            if($data['is_new'] == true){
                foreach ($weight_template as $weight) {
                    $weight += [
                        'demand_task_id' => $data['id'], 
                        'created_at' => (int)$data['created_at'],
                        'updated_at' => time(),
                    ];
                    $weights[] = $weight;
                }
            }
        }
        
        $result = $this->batchInsert(DemandWeight::tableName(), [
            'workitem_type_id',  'weight', 'sl_weight',  
            'zl_weight', 'demand_task_id', 'created_at', 'updated_at'
        ], $weights);

        return $result;
    }


    /**
     * 查询需求任务
     * @return array
     */
    public function findDemandTask()
    {
        $workitems = $this->findDemandWorkitem();
        $weight = $this->findDemandWeight();
        
        $tasks = (new Query())
                ->select(['Demand_task.id', 'Demand_task.created_at'])
                ->from(['Demand_task' => DemandTask::tableName()])
                ->all();
        
        $rows = [];
        foreach ($tasks as $index => $data) {
            if(!isset($workitems[$data['id']]) || !isset($weight[$data['id']])){
                $data += ['is_new' => true];
                $rows[$index] = $data;
            }
        }
        
        return $rows;
    }
    
    /**
     * 查询需求工作项
     * @return array
     */
    public function findDemandWorkitem()
    {
        $workitems = (new Query())
                    ->select(['Demand_worekitem.demand_task_id'])
                    ->from(['Demand_worekitem' => DemandWorkitem::tableName()])
                    ->all();
        
        return ArrayHelper::map($workitems, 'demand_task_id', 'demand_task_id');
    }
    
    /**
     * 查询需求工作项模版
     * @return array
     */
    public function findDemandWorkitemTemplate()
    {
        $query_target_month = (new Query())
            ->select([
                'CONCAT(Wrkitem_template.workitem_id, "_", Wrkitem_template.is_new) AS id',
                'Wrkitem_template.workitem_type_id',
                'Wrkitem_template.workitem_id',
                'Wrkitem_template.is_new',
                'Wrkitem_template.value_type',
                'Wrkitem_template.index',
                'if(Wrkitem_template.is_new = TRUE, Workitem_cost.cost_new, Workitem_cost.cost_remould) AS cost'
            ])
            ->from(['Wrkitem_template' => DemandWorkitemTemplate::tableName()])
            ->leftJoin(['Workitem_cost' => WorkitemCost::tableName()], 'Workitem_cost.workitem_id = Wrkitem_template.workitem_id')
            ->orderBy(['Workitem_cost.target_month' => SORT_DESC, 'Wrkitem_template.workitem_id' => SORT_DESC, 'Wrkitem_template.is_new'=> SORT_DESC]);
                    
         
        $workitemTemplate = (new Query())
            ->select(['*'])
            ->from(['Target_month' => $query_target_month])
            ->groupBy('Target_month.id')
            ->all();
        
        return $workitemTemplate;
    }
    
    /**
     * 查询需求权重
     * @return array
     */
    public function findDemandWeight()
    {
        $weight = (new Query())
                  ->select(['Demand_weight.demand_task_id'])
                  ->from(['Demand_weight' => DemandWeight::tableName()])
                  ->all();
        
        return ArrayHelper::map($weight, 'demand_task_id', 'demand_task_id');
    }
    
    /**
     * 查询需求权重模版
     * @return array
     */
    public function findDemandWeightTemplate()
    {
        $weightTemplate = (new Query())
                        ->select([
                            'Weight_template.workitem_type_id',
                            'Weight_template.weight',
                            'Weight_template.sl_weight',
                            'Weight_template.zl_weight'
                        ])
                        ->from(['Weight_template' => DemandWeightTemplate::tableName()])
                        ->all();
        
        return $weightTemplate;
    }
    
    /**
     * 插入数据
     * @param string $tableName         数据表
     * @param array $columns            插入字段
     * @param array $rows               数据
     * @return array                    成功插入的条数 [num,result]
     */
    private function batchInsert($tableName, $columns, $rows)
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        $number = 0;
        $result = 1;
        $msg = '';
        try
        {  
            $number = Yii::$app->db->createCommand()->batchInsert($tableName, $columns, $rows)->execute();
            $trans->commit();  //提交事务
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            $number = -1;
            $result = 0;
            $msg = $ex->getMessage();
        }
        return ['num'=>$number,'result'=>$result,'msg'=>$msg];
    }
}
