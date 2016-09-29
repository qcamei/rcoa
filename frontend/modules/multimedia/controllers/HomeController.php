<?php

namespace frontend\modules\multimedia\controllers;

use common\models\multimedia\MultimediaContentType;
use common\models\multimedia\MultimediaProducer;
use common\models\multimedia\MultimediaTask;
use common\models\multimedia\MultimediaTypeProportion;
use common\models\User;
use frontend\modules\multimedia\MultimediaTool;
use frontend\modules\multimedia\utils\MultimediaConvertRule;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class HomeController extends Controller
{
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
            //重组创建者数据 [user1:[type1:xx,type2:xx,type3:xx],user2...]
            if(!isset($datas_create_by[$result['create_by']]))
                $datas_create_by[$result['create_by']] = [];
            $target = &$datas_create_by[$result['create_by']];
            //累加同类型的视频时长
            if(!isset($target[$rule[$result['content_type']]]))
                $target[$rule[$result['content_type']]] = 0;
            $target[$rule[$result['content_type']]] += $result['production_video_length'] * MultimediaConvertRule::getInstance()->getRuleProportion($result['content_type']);
            
            //重组制作人数据 [user1:[type1:xx,type2:xx,type3:xx],user2...]
            if(!isset($datas_producer[$result['producer']]))
                $datas_producer[$result['producer']] = [];
            $target = &$datas_producer[$result['producer']];
            
            //累加同类型的视频时长
            if(!isset($target[$rule[$result['content_type']]]))
                $target[$rule[$result['content_type']]] = 0;
            $target[$rule[$result['content_type']]] += $result['production_video_length'] * MultimediaConvertRule::getInstance()->getRuleProportion($result['content_type']);
        }
        /* @var $multimedia MultimediaTool */
        $multimedia = \Yii::$app->get('multimedia');
        return $this->render('index',[
            'multimedia'=>$multimedia,
            'rules' => $rule,
            'datas_create_by'=>$datas_create_by,
            'datas_producer'=>$datas_producer,
            ]);
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
                    'FORMAT(Task.production_video_length/60,1) AS production_video_length',
                    'Task.content_type',
                    'CreateUser.nickname AS create_by',
                    'ProducerUser.nickname AS producer'])
                ->from(['Task'=>  MultimediaTask::tableName()])
                ->leftJoin(['Producer'=>  MultimediaProducer::tableName()], 'Producer.task_id = Task.id')
                ->leftJoin(['CreateUser'=> User::tableName()], 'CreateUser.id = Task.create_by')
                ->leftJoin(['ProducerUser'=>  User::tableName()], 'ProducerUser.id = Producer.u_id')
                ->where(['Task.status' => MultimediaTask::STATUS_COMPLETED]);
        return $query->all(Yii::$app->db);
    }
}
