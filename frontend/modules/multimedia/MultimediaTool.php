<?php

namespace frontend\modules\multimedia;

use common\models\multimedia\MultimediaAssignTeam;
use common\models\multimedia\MultimediaCheck;
use common\models\multimedia\MultimediaProducer;
use common\models\multimedia\MultimediaTask;
use common\models\team\TeamMember;
use Yii;
use yii\db\Exception;
use yii\web\NotFoundHttpException;

class MultimediaTool {
    
     /**
     * 多媒体任务指派时
     * @param type $model
     * @param type $post
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function saveAssignTask($model, $post)
    {
        /* @var $model MultimediaTask */
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model->save(true, ['status', 'progress'])){
                $this->emptyMultimediaProducer($model->id);
                $this->saveMultimediaProducer($model->id, $post['producer']);
            }else {
                throw new Exception(json_encode($model->getErrors()));
            }
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            throw new NotFoundHttpException('保存任务失败！');//.$ex->getMessage());
        }
    }
    
    public function getMultimediaTask($createBy = null, $producer = null, $assignPerson = null, 
        $makeTeam = null, $createTeam = null)
    {
        $result = MultimediaTask::find()
                ->select(['Task.id', 'Task.item_type_id', 'Task.item_id', 'Task.item_child_id', 'Task.course_id',
                    'Task.name', 'Task.progress', 'Task.content_type', 'Task.carry_out_time', 'Task.level',
                    'Task.make_team', 'Task.status', 'Task.create_team', 'Task.create_by', 'AssignTeam.u_id',
                    'Producer.u_id AS producer'
                ])
                ->from(['Task' => MultimediaTask::tableName()])
                ->leftJoin(['AssignTeam' => MultimediaAssignTeam::tableName()], 
                    '(Task.make_team = AssignTeam.team_id OR Task.create_team = AssignTeam.team_id)'
                )
                ->leftJoin(['Producer' => MultimediaProducer::tableName()], 'Task.id = Producer.task_id')
                ->orFilterWhere(['Task.create_by' => $createBy])
                ->orFilterWhere(['Producer.u_id' => $producer])
                ->orFilterWhere(['AssignTeam.u_id' => $assignPerson])
                ->orFilterWhere(['Task.make_team' => $makeTeam])
                ->orFilterWhere(['Task.create_team' => $createTeam])
                ->orderBy('Task.level desc')
                ->with('contentType')
                ->with('createTeam')
                ->with('itemChild')
                ->with('item')
                ->with('itemType')
                ->with('makeTeam')
                ->with('course')
                ->with('createBy')
                ->with('multimediaChecks')
                ->with('producers')
                ->with('teamMember')
                ->all();
        return $result;
    }

    /**
     * 获取当前用户所在的团队
     * @param type $uId         用户ID
     * @return type
     */
    public function getHotelTeam($uId){
        $team = TeamMember::findOne(['u_id' => $uId]);
        if(!empty($team))
            return $team->team_id;
        else 
            return null;
    }
    
    /**
     * 获取是否为团队指派人
     * @param type $teamId        团队ID
     * @return boolean            true为是
     */
    public function getIsAssignPerson($teamId)
    {
        $assignPerson = MultimediaAssignTeam::findOne(['team_id' => $teamId]);
        if(!empty($assignPerson) && isset($assignPerson)){
            if(Yii::$app->user->id == $assignPerson->u_id)
                return true;
        }
        return false;
    }
    
    /**
     * 获取是否为制作人
     * @param type $taskId      任务ID
     * @return boolean          true为是
     */
    public function getIsProducer($taskId)
    {
        $producer = MultimediaProducer::findAll(['task_id' => $taskId]);
        if(!empty($producer) && isset($producer)){
            foreach ($producer as $value) {
                if($value->u_id == \Yii::$app->user->id)
                    return true;
            }
        }
        return false;
    }
    
    /**
     * 获取已存在的审核记录是否有未完成
     * @param type $taskId      任务
     * @return boolean          true 为是      
     */
    public function getIsCheckStatus ($taskId)
    {
        $status = MultimediaCheck::findAll(['task_id' => $taskId]);
        if(!empty($status) || isset($status)){
            foreach ($status as $value) {
                if($value->status == MultimediaCheck::STATUS_NOTCOMPLETE)
                    return true;
            }
        }
        return false;
    }

    /**
     * 保存制作人到表里
     * @param type $taskId    任务ID
     * @param type $post 
     */
    public function saveMultimediaProducer($taskId, $post){
        $values = [];
        /** 重组提交的数据为$values数组 */
        foreach($post as $key => $value)
        {
            $values[] = [
                'task_id' => $taskId,
                'u_id' => $value,
            ];
        }
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(MultimediaProducer::tableName(), 
        ['task_id', 'u_id',], $values)->execute();
    }
    
    /**
     * 清空所有人已存在的制作人
     * @param type $taskId      任务ID
     * @return type
     */
    public function emptyMultimediaProducer($taskId){
        return MultimediaProducer::deleteAll(['task_id' => $taskId]);
    }
}
