<?php

namespace frontend\modules\multimedia\controllers;

use common\models\multimedia\MultimediaContentType;
use common\models\multimedia\MultimediaProducer;
use common\models\multimedia\MultimediaTask;
use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\User;
use frontend\modules\multimedia\utils\MultimediaConvertRule;
use frontend\modules\multimedia\utils\MultimediaTool;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class StatisticsController extends Controller
{   
    public function actionIndex()
    {
        $type = Yii::$app->getRequest()->getQueryParam("type");
        if($type == null || $type == 0)
            return $this->statisticsA ();
        else
            return $this->statisticsB ();
    }
    /**
     * 统计标准工作量
     */
    private function statisticsA(){
        $date = Yii::$app->getRequest()->getQueryParam("date");
        if($date == null) $date = date('Y-m',  time());
                
         //[content_type:[content_type,proportion,name],...]
        $rule = ArrayHelper::map($this->findConvertRule(), 'id','name');
        $results = $this->findProducerData($date);  
        $datas_create_by = [];          //编导数据
        $datas_producer = [];           //制作人数据
        $datas_team = [];               //团队所有数据
        $datas_team_own_aid = [];       //自己团队或者支撑其它团队的数据
        $allWorkload = 0;               //所有工作量
        
        $target;
        
        /** 换算为标准工作量 */
        foreach ($results as $index => $result){            
            //标准工作时间
            $type = $rule[$result['content_type']];
            $value = (int)$result['production_video_length']/60 * MultimediaConvertRule::getInstance()->getRuleProportion($result['content_type']);
            $allWorkload += $value;
            //添加到创建者（编导）数组
            $this->addData($datas_create_by, $result['create_by'], $type, $value);
            //添加到制作者（制作人）数组
            $this->addData($datas_producer, $result['producer'], $type, $value);
            //添加创建团队数据
            $this->addData($datas_team, $result['create_team'], $type, $value);
            //添加自己团队生产的数据
            $this->addData($datas_team_own_aid, 
                    $result['brace_mark'] ? $result['make_team'] : $result['create_team'] , //如果是支撑任务，把数据加到对应支撑的团队里
                    $result['brace_mark'] ? "支撑" : "部内", 
                    $value);
        }
        ArrayHelper::multisort($rule, function($item){return $item == '板书' ? -1 : 1; });
        return $this->render('index',[
            'multimedia'=>  MultimediaTool::getInstance(),
            'date' => $date,
            
            'datas_team' => $datas_team,
            'datas_team_own_aid' => $datas_team_own_aid,
            'datas_create_by' => $datas_create_by,
            'datas_producer' => $datas_producer,
            'rules'=> $rule,
            'allWorkload' => $allWorkload,
        ]);
    }
    /**
     * 统计成品时长
     */
    private function statisticsB(){
        $dateRange = Yii::$app->getRequest()->getQueryParam("dateRange");
         //[content_type:[content_type,proportion,name],...]
        $rule = ArrayHelper::map($this->findConvertRule(), 'id','name');
        $results = $this->findProducerDataB($dateRange);  
        $datas_all_type = ['data'=>[]];             //总比例
        $datas_team_type = [];                      //团队比例
        $allWorkload = 0;                           //所有工作量
        
        $target;
        
        /** 换算为标准工作量 */
        foreach ($results as $index => $result){            
            //标准工作时间
            $type = $rule[$result['content_type']];
            $value = $result['production_video_length'];
            $allWorkload += $value;
            
            //所有部门
            $this->addData($datas_all_type, 'data', $type, $value);
            //添加到部门数组
            $this->addData($datas_team_type, $result['create_team'], $type, $value);
        }
        ArrayHelper::multisort($rule, function($item){return $item == '板书' ? -1 : 1; });
        return $this->render('index_1',[
            'multimedia'=>  MultimediaTool::getInstance(),
            'dateRange' => $dateRange,
            
            'datas_all_type' => $datas_all_type,
            'datas_team_type' => $datas_team_type,
            'rules'=> $rule,
            'allWorkload' => $allWorkload,
        ]);
    }
    
    /**
     * 添加 数据项
     * @param type $target  目标数组
     * @param type $item    项名称
     * @param type $type    数据类型
     * @param type $value   数据值
     */
    private function addData(&$target,$item,$type,$value){
        //创建 item 数组
        if(!isset($target[$item]))
            $target[$item] = [];
        $item_target = &$target[$item];
        
        //创建 不同类型数组并且累加
        if(!isset($item_target[$type]))
            $item_target[$type] = 0;
        $item_target[$type] += $value;
    }
    
    /** 
     * 获取本月标准工作时间换算规则
     * @return array [{content_type,name,proportion}]
     */
    private function findConvertRule(){
        /** 通过分组拿到最合适的比例数据 */
        $query = (new Query())
                ->select(['Type.id','Type.`name`'])
                ->from(['Type'=>  MultimediaContentType::tableName()]);
        
        return $query->all(Yii::$app->db);
    }
    
    /**
     * 查找本月所有人标准工作量
     */
    private function findProducerData($dateRange){
        $query = (new Query())
                ->select([
                    'Task.id',
                    'Task.brace_mark',
                    'Task.production_video_length',
                    'Task.content_type',
                    'Task.real_carry_out',
                    'CreateTeam.name AS create_team',
                    'MakeTeam.name AS make_team',
                    'CreateUser.nickname AS create_by',
                    'ProducerUser.nickname AS producer'])
                ->from(['Task'=>  MultimediaTask::tableName()])
                ->leftJoin(['CreateTeam'=> Team::tableName()], 'CreateTeam.id = Task.create_team')//创建团队
                ->leftJoin(['MakeTeam'=>  Team::tableName()], 'MakeTeam.id = Task.make_team')//制作团队
                ->leftJoin(['TaskProducer'=>  MultimediaProducer::tableName()], 'TaskProducer.task_id = Task.id')//
                ->leftJoin(['TeamMember'=> TeamMember::tableName()], 'TeamMember.id = TaskProducer.producer')//任务关联的团队成员
                ->leftJoin(['ProducerUser'=>  User::tableName()], 'ProducerUser.id = TeamMember.u_id')//制作成员-基本用户
                ->leftJoin(['CreateUser'=> User::tableName()], 'CreateUser.id = Task.create_by')
                ->where(['Task.status' => MultimediaTask::STATUS_COMPLETED])
                ->andWhere(["DATE_FORMAT(Task.real_carry_out,'%Y-%c')" => $dateRange]);
        return $query->all(Yii::$app->db);
    }
    /**
    * 查找本月所有任务
    */
   private function findProducerDataB($dateRange){
       $query = (new Query())
               ->select([
                   'Task.id',
                   'Task.production_video_length',
                   'Task.content_type',
                   'Task.real_carry_out',
                   'CreateTeam.name AS create_team'])
               ->from(['Task'=>  MultimediaTask::tableName()])
               ->leftJoin(['CreateTeam'=> Team::tableName()], 'CreateTeam.id = Task.create_team')//创建团队
               ->where(['Task.status' => MultimediaTask::STATUS_COMPLETED]);
       /* 当时间段参数不为空时 */
        if($dateRange){
            $dateRange_Arr = explode(" - ",$dateRange);
            $query ->andWhere(['between','Task.real_carry_out',$dateRange_Arr[0], $dateRange_Arr[1]]);
        }
       return $query->all(Yii::$app->db);
   }
}
