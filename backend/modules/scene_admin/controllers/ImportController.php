<?php

namespace backend\modules\scene_admin\controllers;

use common\models\demand\DemandWeight;
use common\models\scene\SceneBook;
use common\models\shoot\ShootAppraise;
use common\models\shoot\ShootAppraiseResult;
use common\models\shoot\ShootBookdetail;
use common\models\shoot\ShootBookdetailRoleName;
use Yii;
use yii\db\Exception;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * WorkitemController implements the CRUD actions for DemandWorkitemTemplate model.
 */
class ImportController extends Controller
{
    private  $logs;
    
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
        $this->saveSceneBook();
        return $this->render('index');
    }
    
    /**
     * 保存旧预约数据到新表里
     * @return message
     */
    public function saveSceneBook()
    {
        $shootBooks = $this->findShootBookdetail()->addSelect(['ShootBookdetail.*'])->all();
        $sceneBooks = [];
        $content = [];
        foreach ($shootBooks as $shoot) {
            $sceneBooks = [
                'id' => md5($shoot['site_id'] . date('Y-m-d', $shoot['book_time']) . $shoot['index'] . $shoot['id']),
                'site_id' => $shoot['site_id'],
                'date' => date('Y-m-d', $shoot['book_time']),
                'time_index' => $shoot['index'],
                'status' => $shoot['status'] == 5 ? 205 : ($shoot['status'] == 10 ? 300 : ($shoot['status'] == 13 ? 305 : ($shoot['status'] == 15 ? 500 : ($shoot['status'] == 20 ? 400 : 99)))),
                'business_id' => $shoot['business_id'],
                'level_id' => $shoot['fw_college'],
                'profession_id' => $shoot['fw_project'],
                'course_id' => $shoot['fw_course'],
                'lession_time' => $shoot['lession_time'],
                'content_type' => ShootBookdetail::$contentTypeMap[$shoot['content_type']],
                'shoot_mode' => $shoot['shoot_mode'],
                'is_photograph' => $shoot['photograph'],
                'camera_count' => 1,
                'start_time' => $shoot['start_time'],
                'remark' => $shoot['remark'],
                'is_transfer' => 0,
                'teacher_id' => $shoot['u_teacher'],
                'booker_id' => $shoot['u_booker'],
                'created_by' => $shoot['create_by'],
                'created_at' => $shoot['created_at'],
                'updated_at' => $shoot['updated_at'],
                'ver' => $shoot['ver'],
            ];
            $msg = $this->createCommand(SceneBook::tableName(), $sceneBooks);
            $content += [$shoot['id'] => $msg];
        }
        $this->addLog('迁移预约数据', $content);
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
     * 查询 ShootBookdetail 数据
     * @return query
     */
    private function findShootBookdetail()
    {
        $statusMap = [ShootBookdetail::STATUS_DEFAULT, ShootBookdetail::STATUS_BOOKING, ShootBookdetail::STATUS_CANCEL];
        $query = (new Query)->select(['ShootBookdetail.id'])
            ->from(['ShootBookdetail' => ShootBookdetail::tableName()])
            ->where(['NOT IN', 'ShootBookdetail.status', $statusMap]);
        
        return $query;
    }
    
    /**
     * 查询 ShootBookdetailRoleName 数据
     * @return array
     */
    private function findShootBookdetailRoleName()
    {
        $query = (new Query())
            ->from(['RoleName' => ShootBookdetailRoleName::tableName()])
            ->where(['RoleName.b_id' => $this->findShootBookdetail()]);
        
        return $query->all();
            
    }
    
    /**
     * 查询 ShootAppraise 数据
     * @return array
     */
    private function findShootAppraise()
    {
       $query = (new Query)
           ->from(['ShootAppraise' => ShootAppraise::tableName()])
           ->where(['ShootAppraise.b_id' => $this->findShootBookdetail()]);
       
        return $query->all();
    }
    
    /**
     * 查询 ShootAppraiseResult 数据
     * @return array
     */
    public function findShootAppraiseResult()
    {
        $query = (new Query)
           ->from(['AppraiseResult' => ShootAppraiseResult::tableName()])
           ->where(['AppraiseResult.b_id' => $this->findShootBookdetail()]);
       
        return $query->all();
    }
        
    /**
     * 插入数据
     * @param string $tableName         数据表
     * @param array $columns            插入字段
     * @param string $msg               消息
     * @return string                   插入数据情况
     */
    private function createCommand($tableName, $columns, $msg = '')
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try{  
            Yii::$app->db->createCommand()->insert($tableName, $columns)->execute();
            $trans->commit();  //提交事务
            $msg = '迁移成功';
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            $msg = $ex->getMessage();
        }
        
        return $msg;
    }
    
    /**
     * 添加记录
     * @param type $stepName    步骤名
     * @param type $content     内容
     */
    private function addLog($stepName, $content = '')
    {
        $this->logs[] = ['stepName' => $stepName,'content' => $content];
        var_dump($this->logs);exit;
    }
}
