<?php

namespace frontend\modules\multimedia\controllers;

use common\models\multimedia\MultimediaContentType;
use common\models\multimedia\MultimediaProducer;
use common\models\multimedia\MultimediaTask;
use common\models\team\TeamMember;
use common\models\User;
use frontend\modules\multimedia\utils\MultimediaConvertRule;
use frontend\modules\multimedia\utils\MultimediaTool;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class HomeController extends Controller
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
    
    public function actionIndex()
    {
        //[content_type:[content_type,proportion,name],...]
        $rule = ArrayHelper::map($this->findConvertRule(), 'id','name');
        $results = $this->findProducerData();  
        $datas_create_by = [];
        $datas_producer = [];
        
        $target;
        
        /** 换算为标准工作量 */
        foreach ($results as $index => $result){            
            //标准工作时间
            $type = $rule[$result['content_type']];
            $value = (int)($result['production_video_length']/60) * MultimediaConvertRule::getInstance()->getRuleProportion($result['content_type']);
            //添加到创建者（编导）数组
            $this->addData($datas_create_by, $result['create_by'], $type, $value);
            //添加到制作者（制作人）数组
            $this->addData($datas_producer, $result['producer'], $type, $value);
        }
        ArrayHelper::multisort($rule, function($item){return $item == '板书' ? -1 : 1; });
        /* @var $multimedia MultimediaTool */
        $multimedia = MultimediaTool::getInstance();
        return $this->render('index',[
            'multimedia'=>$multimedia,
            'rules' => $rule,
            'datas_create_by'=>$datas_create_by,
            'datas_producer'=>$datas_producer,
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
    private function findProducerData(){
        $query = (new Query())
                ->select([
                    'Task.id',
                    'Task.production_video_length',
                    'Task.content_type',
                    'CreateUser.nickname AS create_by',
                    'ProducerUser.nickname AS producer'])
                ->from(['Task'=>  MultimediaTask::tableName()])
                ->leftJoin(['Producer'=>  MultimediaProducer::tableName()], 'Producer.task_id = Task.id')
                ->leftJoin(['CreateUser'=> User::tableName()], 'CreateUser.id = Task.create_by')
                ->leftJoin(['TeamMember' => TeamMember::tableName()], 'Producer.producer = TeamMember.id')
                ->leftJoin(['ProducerUser'=>  User::tableName()], 'ProducerUser.id = TeamMember.u_id')
                ->where(['Task.status' => MultimediaTask::STATUS_COMPLETED])
                ->andWhere(['between','Task.real_carry_out',date('Y-m-01', time()),date('Y-m-t', time())]);
        return $query->all(Yii::$app->db);
    }
}
