<?php

namespace backend\modules\scene_admin\controllers;

use common\models\scene\SceneAppraise;
use common\models\scene\SceneBook;
use common\models\scene\SceneBookUser;
use common\models\shoot\ShootAppraise;
use common\models\shoot\ShootAppraiseResult;
use common\models\shoot\ShootBookdetail;
use common\models\shoot\ShootBookdetailRoleName;
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
    public function actionIndex($carry_out = false)
    {
        if($carry_out){
            $code = 0;
            $msg = '';
            try {
                //保存预约
                $this->saveSceneBook();
                //保存预约接洽人和摄影师
                $this->saveSceneBookUser();
                //保存预约评价
                $this->saveSceneAppraise();
            } catch (Exception $ex) {
                $code = 1;
                $msg = $ex->getMessage();
            }
                
            return $this->render('index_result',['code' => $code,'msg' => $msg,'logs' => $this->logs]);    
        }
        return $this->render('index');
    }
    
    /**
     * 保存旧预约数据到新表里
     * @return message
     */
    private function saveSceneBook()
    {
        $shootBooks = $this->findShootBookdetail()->addSelect(['ShootBookdetail.*'])->all();
        $cameraNumber = $this->countCameraNumber();
        $existSceneBooks = array_keys($this->findSceneBook());
        $sceneBooks = [];
        $content = [];
        //组装预约数据
        foreach ($shootBooks as $shoot) {
            $sceneBooks[$shoot['id']] = [
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
                'camera_count' => isset($cameraNumber[$shoot['id']]) ? count($cameraNumber[$shoot['id']]) : 1,
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
        }
        //保存预约数据到表里
        foreach ($sceneBooks as $key => $value) {
            if(!in_array($value['id'], $existSceneBooks)){
                $msg = $this->createCommand(SceneBook::tableName(), $value);
                $content += [$key => $msg.'('.$value['id'].')'];
            }else{
                $content += [$key => '已经迁移成功('.$value['id'].')'];
            }
        }
        
        $this->addLog('迁移预约数据', $content);
    }
    
    /**
     * 保存接洽人和摄影师
     * @return message
     */
    private function saveSceneBookUser()
    {
        $shootBooks = $this->findShootBookdetail()
            ->addSelect(['ShootBookdetail.id', 'ShootBookdetail.site_id', 
                'ShootBookdetail.book_time', 'ShootBookdetail.index'])->all();
        $roleNames = $this->findShootBookdetailRoleName();
        $sceneBooks = $this->findSceneBook();
        $existSceneBookUsers = $this->findSceneBookUser();
        $bookUsers = [];
        $roleIds = [];
        $content = [];
        //获取id
        foreach ($shootBooks as $shoot){
            $boo_id = md5($shoot['site_id'] . date('Y-m-d', $shoot['book_time']) . $shoot['index'] . $shoot['id']);
            if(isset($sceneBooks[$boo_id]))
                $roleIds[$shoot['id']] = $sceneBooks[$boo_id];
        }
        //组装接洽人和摄影师
        foreach($roleNames as $role){
            if(isset($roleIds[$role['b_id']])){
                $bookUsers[$role['b_id']][] = [
                    'book_id' => $roleIds[$role['b_id']],
                    'role' => $role['role_name'] == 'r_contact' ? 1 : 2,
                    'user_id' => $role['u_id'],
                    'is_primary' => (int)$role['primary_foreign'],
                    'sort_order' =>  0,
                    'is_delete' => $role['iscancel'] == 'N' ? 0 : 1,
                    'created_at' => time(),
                    'updated_at' => time(),
                ];
            }
        }
        //保存接洽人和摄影师数据到表里
        foreach ($bookUsers as $key => $value) {
            if(!in_array($roleIds[$key], $existSceneBookUsers)){
                $columns = array_keys($value[0]);
                $msg = $this->createBatchInsert(SceneBookUser::tableName(), $columns, $value);
                $content += [$key => $msg.'('.$roleIds[$key].')'];
            }else{
                $content += [$key => '已经迁移成功('.$roleIds[$key].')'];
            }
        }
       
        $this->addLog('迁移接洽人和摄影师数据', $content);
    }
    
    /**
     * 保存预约评价
     * @return message
     */
    private function saveSceneAppraise()
    {
        $shootBooks = $this->findShootBookdetail()
            ->addSelect(['ShootBookdetail.id', 'ShootBookdetail.site_id', 
                'ShootBookdetail.book_time', 'ShootBookdetail.index'])->all();
        $shootAppraises = $this->findShootAppraise();
        $appraiseResults = $this->findShootAppraiseResult();
        $sceneBooks = $this->findSceneBook();
        $existSceneAppraise = $this->findSceneAppraise();
        $shootApps = [];
        $shootAppResults = [];
        $sceneApps = [];
        $appIds = [];
        $content = [];
        //获取id
        //获取id
        foreach ($shootBooks as $shoot){
            $boo_id = md5($shoot['site_id'] . date('Y-m-d', $shoot['book_time']) . $shoot['index'] . $shoot['id']);
            if(isset($sceneBooks[$boo_id]))
                $appIds[$shoot['id']] = $sceneBooks[$boo_id];
        }
        //组装获取评价题目分数和排序
        foreach ($shootAppraises as $appraise){
            $shootApps[$appraise['b_id']][$appraise['role_name']][$appraise['q_id']] = [
                'value' => $appraise['value'],
                'index' => $appraise['index'],
            ];
        }
        //组装场景评价数据
        foreach($appraiseResults as $result){
            if(isset($shootApps[$result['b_id']]) && isset($appIds[$result['b_id']])){
                $sceneApps[$result['b_id']][] = [
                    'book_id' => $appIds[$result['b_id']],
                    'role' => $result['role_name'] == 'r_contact' ? 1 : 2,
                    'q_id' => $result['q_id'],
                    'q_value' => $shootApps[$result['b_id']][$result['role_name']][$result['q_id']]['value'],
                    'index' => $shootApps[$result['b_id']][$result['role_name']][$result['q_id']]['index'] * -1,
                    'user_id' => $result['u_id'],
                    'user_value' => $result['value'],
                    'user_data' =>  $result['data'],
                    'created_at' => time(),
                    'updated_at' => time(),
                ];
            }
        }
        //保存场景评价数据到表里
        foreach ($sceneApps as $key => $value) {
            if(!in_array($appIds[$key], $existSceneAppraise)){
                $columns = array_keys($value[0]);
                $msg = $this->createBatchInsert(SceneAppraise::tableName(), $columns, $value);
                $content += [$key => $msg.'('.$appIds[$key].')'];
            }else{
                $content += [$key => '已经迁移成功('.$appIds[$key].')'];
            }
        }
        
        $this->addLog('迁移评价题目和评价结果', $content);
    }

    /**
     * 查询 SceneBook 数据
     * @return array
     */
    private function findSceneBook()
    {
        $query = (new Query())->select(['SceneBook.id'])
            ->from(['SceneBook' => SceneBook::tableName()]);
        
       return ArrayHelper::map($query->all(), 'id', 'id');
    }
    
    /**
     * 查询 SceneBookUser 数据
     * @return array
     */
    private function findSceneBookUser()
    {
        $query = (new Query())->select(['SceneBookUser.book_id'])
            ->from(['SceneBookUser' => SceneBookUser::tableName()]);
        
       return ArrayHelper::getColumn($query->all(), 'book_id');
    }
    
    /**
     * 查询 SceneAppraise 数据
     * @return array
     */
    private function findSceneAppraise()
    {
        $query = (new Query())->select(['SceneAppraise.book_id'])
            ->from(['SceneAppraise' => SceneAppraise::tableName()]);
        
       return ArrayHelper::getColumn($query->all(), 'book_id');
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
            ->where(['RoleName.b_id' => $this->findShootBookdetail()])
            ->andWhere(['iscancel' => 'N']);
        
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
    private function findShootAppraiseResult()
    {
        $query = (new Query)
           ->from(['AppraiseResult' => ShootAppraiseResult::tableName()])
           ->where(['AppraiseResult.b_id' => $this->findShootBookdetail()]);
       
        return $query->all();
    }
    
    /**
     * 根据摄影师计算机位数
     * @return array
     */
    private function countCameraNumber()
    {
        $shootMans = [];
        $roleUsers = $this->findShootBookdetailRoleName();
       
        foreach ($roleUsers as $user) {
            if($user['role_name'] == 'r_shoot_man')
                $shootMans[$user['b_id']][] = $user['u_id'];
        }
        
        return $shootMans;
    }

    /**
     * 插入单条数据
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
     * 插入多条数据
     * @param string $tableName         数据表
     * @param array $columns            插入字段
     * @param array $rows               插入数据
     * @param string $msg               消息
     * @return string                   插入数据情况
     */
    private function createBatchInsert($tableName, $columns, $rows, $msg = '')
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try{  
            Yii::$app->db->createCommand()->batchInsert($tableName, $columns, $rows)->execute();
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
    }
}
