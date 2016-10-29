<?php

namespace frontend\modules\multimedia\utils;

use common\models\multimedia\MultimediaAssignTeam;
use common\models\multimedia\MultimediaCheck;
use common\models\multimedia\MultimediaOperation;
use common\models\multimedia\MultimediaOperationUser;
use common\models\multimedia\MultimediaProducer;
use common\models\multimedia\MultimediaTask;
use common\models\team\TeamMember;
use common\wskeee\job\JobManager;
use frontend\modules\multimedia\utils\MultimediaNoticeTool;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class MultimediaTool {
    
    private static $instance = null;
    
    /**
     * 多媒体任务指派时
     * @param MultimediaTask $model
     * @param type $post
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function saveAssignTask($model, $post)
    {
        /* @var $multimediaNotice MultimediaNoticeTool */
        $multimediaNotice = MultimediaNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        $postProducer = ArrayHelper::getValue($post, 'producer'); //获取传上来的制作人
        $teamUid = ArrayHelper::getValue($multimediaNotice->getAssignPerson([$model->create_team, $model->make_team]), 'u_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model->save(true, ['status', 'progress'])){
                $this->emptyMultimediaProducer($model->id);
                $this->saveMultimediaProducer($model->id, $postProducer);
                $jobManager->removeNotification(10, $model->id, $teamUid);
                if(in_array($model->create_by, $teamUid)){
                    $jobManager->addNotification (10, $model->id, $model->create_by);
                    $jobManager->setNotificationHasReady(10, $model->create_by, $model->id);
                }
                $producer = ArrayHelper::getValue($multimediaNotice->getProducer($model->id), 'u_id');
                $this->saveMultimediaOperation($model->id, $model->status);
                $this->saveOperationUser($model->id, $producer);
                $multimediaNotice->setAssignNotification($model, $producer);
                $multimediaNotice->sendProducerNotification($model, $model->id, '新任务', 'multimedia/AssignProducer-htm');
                $multimediaNotice->sendCreateByNotification($model, '任务已指派', 'multimedia/AssignCreateBy-html');
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
    
    /**
     * 多媒体任务创建时
     * @param MultimediaTask $model
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function saveCreateTask($model)
    {
        /* @var $multimediaNotice MultimediaNoticeTool */
        $multimediaNotice = MultimediaNoticeTool::getInstance();
        $user = ArrayHelper::getValue($multimediaNotice->getAssignPerson($model->create_team), 'u_id');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model->save()){
                $this->saveMultimediaOperation($model->id, $model->status);
                $this->saveOperationUser($model->id, $user);
                $multimediaNotice->saveJobManager($model);
                $multimediaNotice->sendAssignPersonNotification($model, $model->create_team, '新任务', 'multimedia/Create-html');
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
    
    /**
     * 多媒体任务更新时
     * @param MultimediaTask $model
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function saveUpdateTask($model)
    {
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model->save()){
                $jobManager->updateJob(10, $model->id, ['subject' => $model->name]);
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
    
    /**
     * 多媒体任务寻求支撑时
     * @param MultimediaTask $model
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function saveSeekBraceTask($model)
    {
        /* @var $multimediaNotice MultimediaNoticeTool */
        $multimediaNotice = MultimediaNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        $assignPerson = $multimediaNotice->getAssignPerson($model->make_team);
        $assignPersonId = ArrayHelper::getValue($assignPerson, 'u_id');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model->save(false, ['make_team', 'brace_mark'])){
                $this->saveMultimediaOperation($model->id, $model->status);
                $this->saveOperationUser($model->id, $assignPersonId, MultimediaTask::SEEK_BRACE_MARK);
                $jobManager->addNotification(10, $model->id, $assignPersonId);      //添加通知
                $multimediaNotice->sendAssignPersonNotification($model, $model->make_team, '支撑请求', 'multimedia/SeekBrace-html');
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
    
    /**
     * 多媒体任务取消支撑时
     * @param MultimediaTask $model
     * @param type $oldMakeTeam             旧的制作团队
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function saveCancelBraceTask($model, $oldMakeTeam)
    {
        /* @var $multimediaNotice MultimediaNoticeTool */
        $multimediaNotice = MultimediaNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        $assignPerson = $multimediaNotice->getAssignPerson($oldMakeTeam);
        $assignPersonId = ArrayHelper::getValue($assignPerson, 'u_id');
        $user = ArrayHelper::getValue($multimediaNotice->getAssignPerson($model->create_team), 'u_id');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model->save(false, ['brace_mark', 'make_team'])){
                $this->saveMultimediaOperation($model->id, $model->status);
                $this->saveOperationUser($model->id, $user);
                $jobManager->removeNotification(10, $model->id, $assignPersonId);
                $multimediaNotice->sendAssignPersonNotification ($model, $oldMakeTeam, '取消支撑', 'multimedia/CancelBrace-html');
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
    
    /**
     * 多媒体任务开始制作时
     * @param MultimediaTask $model
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function saveStartMakeTask($model)
    {
        /* @var $multimediaNotice MultimediaNoticeTool */
        $multimediaNotice = MultimediaNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model->save(false, ['status', 'progress'])){
                $jobManager->updateJob(10, $model->id, ['progress'=> $model->progress, 'status'=>$model->getStatusName()]);
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
    
    /**
     * 多媒体任务提交制作时
     * @param MultimediaTask $model
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function saveSubmitMakeTask($model)
    {
        /* @var $multimediaNotice MultimediaNoticeTool */
        $multimediaNotice = MultimediaNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model->save(false, ['status', 'progress'])){
                $this->saveMultimediaOperation($model->id, $model->status);
                $this->saveOperationUser($model->id, [$model->create_by]);
                $jobManager->updateJob(10, $model->id, ['progress'=> $model->progress, 'status'=>$model->getStatusName()]);
                $multimediaNotice->sendCreateByNotification ($model, '任务提交', 'multimedia/SubmitMake-html');
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
    
    /**
     * 多媒体任务完成制作时
     * @param MultimediaTask $model
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function saveCompleteTask($model)
    {
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if ($model->save() && $model->validate()){
                $jobManager->updateJob(10, $model->id, ['progress'=> $model->progress, 'status'=>$model->getStatusName()]);
                $jobManager->cancelNotification(10, $model->id, $model->create_by);
            }else {
                throw new Exception(json_encode($model->getErrors()));
            }
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            throw new NotFoundHttpException('保存任务失败！格式不正确，请按 00:00:00 格式录入！');//.$ex->getMessage());
        }
    }
    
    /**
     * 多媒体任务恢复制作时
     * @param MultimediaTask $model
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function saveRecoveryTask($model)
    {
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /* @var $multimediaNotice MultimediaNoticeTool */
        $multimediaNotice = MultimediaNoticeTool::getInstance();
        $producer = ArrayHelper::getValue($multimediaNotice->getProducer($model->id), 'u_id');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if ($model->save(false, ['progress', 'status'])){
                $jobManager->updateJob(10, $model->id, ['progress'=> $model->progress, 'status'=>$model->getStatusName()]);
                $jobManager->removeNotification(10, $model->id, $producer);
                $jobManager->addNotification (10, $model->id, $producer);
            }else {
                throw new Exception(json_encode($model->getErrors()));
            }
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
        }
    }
    
    /**
     * 多媒体任务取消时
     * @param MultimediaTask $model
     * @param type $cancel              临时变量
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function saveCancelTask($model, $cancel)
    {
        /* @var $model MultimediaTask */
        $team = [$model->create_team, $model->make_team];
        /* @var $multimediaNotice MultimediaNoticeTool */
        $multimediaNotice = MultimediaNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model->save(false, ['status'])){
                $multimediaNotice->cancelJobManager($model, $team);
                if(empty($model->producers))
                    $multimediaNotice->sendAssignPersonNotification ($model, $team,'任务取消', 'multimedia/Cancel-html', $cancel);
                else
                    $multimediaNotice->sendProducerNotification ($model, $model->id, '任务取消', 'multimedia/Cancel-html', $cancel);
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

    /**
     * 多媒体任务创建审核时
     * @param MultimediaCheck $model
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function saveCreateCheckTask($model)
    {
        /* @var $model MultimediaCheck */
        /* @var $multimediaNotice MultimediaNoticeTool */
        $multimediaNotice = MultimediaNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        $producer = ArrayHelper::getValue($multimediaNotice->getProducer($model->task_id), 'u_id');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            $number = Yii::$app->db->createCommand()
                  ->update(MultimediaTask::tableName(), ['status'=>  MultimediaTask::STATUS_UPDATEING], ['id' => $model->task_id])
                  ->execute();
            if($model->save() && $number > 0){
                $this->saveMultimediaOperation($model->task_id, MultimediaTask::STATUS_UPDATEING);
                $this->saveOperationUser($model->task_id, $producer);
                $jobManager->updateJob(10, $model->task_id, ['status'=> MultimediaTask::$statusNmae[MultimediaTask::STATUS_UPDATEING]]);
                $multimediaNotice->sendProducerNotification ($model, $model->task_id, '审核意见', 'multimedia/CreateCheck-html');
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
    
    /**
     * 多媒体任务提交审核时
     * @param MultimediaCheck $model
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function saveSubmitCheckTask($model)
    {
        /* @var $model MultimediaCheck */
        /* @var $multimediaNotice MultimediaNoticeTool */
        $multimediaNotice = MultimediaNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            $number = Yii::$app->db->createCommand()
                  ->update(MultimediaTask::tableName(), ['status'=>  MultimediaTask::STATUS_CHECKING], ['id' => $model->task_id])
                  ->execute();
            if($model->save(false, ['real_carry_out', 'status']) && $number > 0){
                $this->saveMultimediaOperation($model->task_id, MultimediaTask::STATUS_CHECKING);
                $this->saveOperationUser($model->task_id, [$model->task->create_by]);
                $jobManager->updateJob(10, $model->task_id, ['status'=> MultimediaTask::$statusNmae[MultimediaTask::STATUS_CHECKING]]);
                $multimediaNotice->sendCreateByNotification ($model, '审核提交', 'multimedia/SubmitCheck-html');
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
    
    /**
     * 查询所有任务
     * @param type $createBy        创建者
     * @param type $producer        制作人
     * @param type $assignPerson    指派人
     * @param type $createTeam      创建团队
     * @param type $makeTeam        制作团队
     * @param type $contentType     类型
     * @param type $itemTypeId      行业
     * @param type $itemId          层次/类型
     * @param type $itemChildId     专业/工种
     * @param type $courseId        课程
     * @param type $status          状态
     * @param type $time            时间段
     * @param type $keyword         关键字
     * @param type $mark            标识
     * @return type                 
     */
    public function getMultimediaTask($createBy = null, $producer = null, $assignPerson = null, $createTeam = null, 
        $makeTeam = null, $contentType = null, $itemTypeId = null, $itemId = null, $itemChildId = null, $courseId = null,
        $status = 1, $time = null, $keyword = null, $mark = null)
    {
        if($time != null)
            $time = explode(" - ",$time);
        
        $result = MultimediaTask::find()
                ->select(['Task.id', 'Task.item_type_id', 'Task.item_id', 'Task.item_child_id', 'Task.course_id',
                    'Task.name', 'Task.progress', 'Task.content_type', 'Task.plan_end_time', 'Task.level',
                    'Task.make_team', 'Task.status', 'Task.create_team', 'Task.create_by', 'AssignTeam.u_id',
                    'ItemType.name AS ItemTypeName','Item.name AS ItemName'
                ])
                ->from(['Task' => MultimediaTask::tableName()])
                ->leftJoin(['AssignTeam' => MultimediaAssignTeam::tableName()], 
                    '(Task.make_team = AssignTeam.team_id OR Task.create_team = AssignTeam.team_id)'
                )
                ->leftJoin(['Producer' => MultimediaProducer::tableName()], 'Task.id = Producer.task_id')
                ->leftJoin(['TeamMember' => TeamMember::tableName()], 'Producer.producer = TeamMember.id')
                ->leftJoin(['ItemType' => ItemType::tableName()], 'ItemType.id = Task.item_type_id')
                ->leftJoin(['Item' => Item::tableName()],
                  '(Item.id = Task.item_id OR Item.id = Task.item_child_id OR Item.id = Task.course_id)')
                ->andFilterWhere(['or',
                    [$mark == null ? 'or' : 'and', ['Task.create_by' => $createBy], ['TeamMember.u_id' => $producer]],
                    ['AssignTeam.u_id' => $assignPerson],
                ])
                ->andFilterWhere([
                    'Task.create_team' => $createTeam,
                    'Task.make_team' => $makeTeam,
                    'Task.content_type' => $contentType,
                    'Task.item_type_id' => $itemTypeId,
                    'Task.item_id' => $itemId,
                    'Task.item_child_id' => $itemChildId,
                    'Task.course_id' => $courseId,
                ])
                ->andFilterWhere(['IN', 'Task.status', 
                    ($status == 1 ? MultimediaTask::$defaultStatus : $status)
                ])
                ->andFilterWhere(
                    $time != null ? ($status == 1 ? ['<=', 'Task.created_at', strtotime($time[1])] : 
                        ($status == MultimediaTask::STATUS_COMPLETED ? ['between', 'Task.real_carry_out', $time[0],$time[1]] : 
                            ($status == MultimediaTask::STATUS_CANCEL ? ['between', 'Task.created_at', strtotime($time[0]),strtotime($time[1])] : 
                                ['or', ['between', 'Task.created_at', strtotime($time[0]),strtotime($time[1])], 
                                ['between', 'Task.real_carry_out', $time[0],$time[1]]]
                            )
                        )
                    ) : []
                )
                ->andFilterWhere(['or',
                    ['like', 'Task.name', $keyword],
                    ['like', 'ItemType.name', $keyword],
                    ['like', 'Item.name', $keyword]
                ])
                ->orderBy('Task.level desc, Task.id asc')
                ->with('contentType')
                ->with('createTeam')
                ->with('itemChild')
                ->with('item')
                ->with('itemType')
                ->with('makeTeam')
                ->with('course')
                ->with('createBy')
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
        $teamMember = TeamMember::find()
                      ->where(['u_id' => $uId])
                      ->with('team')
                      ->all();
        $team = ArrayHelper::getColumn($teamMember, 'team_id');
        if(!empty($team) && count($team) == 1)
            return $team[0];
        else
            return ArrayHelper::map($teamMember, 'team.id', 'team.name');
    }
    
    /**
     * 获取是否为团队指派人
     * @param type $teamId        团队ID
     * @return boolean            true为是
     */
    public function getIsAssignPerson($teamId)
    {
        $assign = MultimediaAssignTeam::findOne(['team_id' => $teamId]);
        if(!empty($assign) && isset($assign)){
            if(Yii::$app->user->id == $assign->u_id)
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
        $producers = MultimediaProducer::find()
                    ->where(['task_id' => $taskId])
                    ->with('multimediaProducer')
                    ->all();
        if(!empty($producers) && isset($producers)){
            $isProducer = ArrayHelper::getColumn($producers, 'multimediaProducer.u_id');
            if(in_array(\Yii::$app->user->id, $isProducer))
                return true;
        }
        return false;
    }
    
    /**
     * 获取已存在的审核记录是否有未完成
     * @param type $taskId      任务
     * @return boolean          true 为是      
     */
    public function getIsCompleteCheck ($taskId)
    {
        $check = MultimediaCheck::find()
                  ->where(['task_id' => $taskId])
                  ->all();
        if(!empty($check) || isset($check)){
            $isComplete = ArrayHelper::getColumn($check, 'status');
            if(in_array(MultimediaCheck::STATUS_NOTCOMPLETE, $isComplete))
                return true;  
        }
        return false;
    }
    
    /**
     * 获取是否属于自己操作
     * @param array $taskId                          任务ID
     * @param array $status                          状态
     * @return boolean                              true为是
     */ 
    public function getIsBelongToOwnOperate($taskId, $status)
    {
        $operation = [];
        $isBelong = [];
        $operates = MultimediaOperation::find()
                   ->where(['task_id' => $taskId])
                   ->all();
        if(!empty($operates)){
            /* @var $value MultimediaOperation */
            foreach ($operates as $value) {
                $operation[$value->task_id] = [
                    'id' => $value->id,
                    'status' => $value->task_statu == $status[$value->task_id] ? true : false,
                ];
            }
            $operationUser = MultimediaOperationUser::find()
                            ->where(['operation_id' => ArrayHelper::getColumn($operation, 'id')])
                            ->with('operation')
                            ->asArray()
                            ->all();
            $operations = ArrayHelper::map($operation, 'id', 'status');
            $operationUser = ArrayHelper::index($operationUser, 'operation_id');
            if(!empty($operationUser)){
                /* @var $value MultimediaOperationUser */
                foreach ($operationUser as $key => $value) {
                    $value['status'] = $operations[$key];
                    if((\Yii::$app->user->id == $value['u_id'] || MultimediaTask::SEEK_BRACE_MARK == $value['brace_mark'])
                       && $value['status'])
                    {
                        $isBelong[$value['operation']['task_id']] = true;
                    }else{
                        $isBelong[$value['operation']['task_id']] = false;
                    };
                    
                }
            }
        }
        return $isBelong;
    }


    /**
     * 保存操作到表里
     * @param type $taskId              任务ID
     * @param type $status              状态
     */
    public function saveMultimediaOperation($taskId, $status){
        $values[] = [
            'task_id' => $taskId,
            'task_statu' => $status,
            'action_id' => Yii::$app->controller->action->id,
            'create_by' => \Yii::$app->user->id,
            'created_at' => time(),
            'updated_at' => time(),
        ];
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(MultimediaOperation::tableName(), 
        ['task_id', 'task_statu', 'action_id', 'create_by', 'created_at', 'updated_at'], $values)->execute();
    }
    
    /**
     * 保存操作用户到表里
     * @param type $taskId            多媒体任务ID
     * @param array $uId              用户ID
     * @param type $brace             支撑标识
     */
    public function saveOperationUser($taskId, $uId, $brace = null){
        $operation = MultimediaOperation::find()
                     ->where(['task_id' => $taskId])
                     ->orderBy('id desc')
                     ->one();
        $values = [];
        /** 重组提交的数据为$values数组 */
        foreach($uId as $key => $value)
        {
            $values[] = [
                'operation_id' => $operation->id,
                'u_id' => $value,
                'brace_mark' => $brace == null ? MultimediaTask::CANCEL_BRACE_MARK : $brace
            ];
        }
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(MultimediaOperationUser::tableName(), 
        ['operation_id', 'u_id', 'brace_mark'], $values)->execute();
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
                'producer' => $value,
            ];
        }
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(MultimediaProducer::tableName(), 
        ['task_id', 'producer',], $values)->execute();
    }
    
    /**
     * 清空所有人已存在的制作人
     * @param type $taskId      任务ID
     * @return type
     */
    public function emptyMultimediaProducer($taskId){
        return MultimediaProducer::deleteAll(['task_id' => $taskId]);
    }
    
    /**
     * 获取单例
     * @return MultimediaTool
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new MultimediaTool();
        }
        return self::$instance;
    }
}
