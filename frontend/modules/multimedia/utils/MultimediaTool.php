<?php

namespace frontend\modules\multimedia\utils;

use common\config\AppGlobalVariables;
use common\models\multimedia\MultimediaAssignTeam;
use common\models\multimedia\MultimediaCheck;
use common\models\multimedia\MultimediaOperation;
use common\models\multimedia\MultimediaOperationUser;
use common\models\multimedia\MultimediaProducer;
use common\models\multimedia\MultimediaTask;
use common\models\team\TeamMember;
use common\wskeee\job\JobManager;
use frontend\modules\multimedia\utils\MultimediaNoticeTool;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class MultimediaTool {
    
    private static $instance = null;
    
    /**
     * 多媒体任务指派时
     * @param MultimediaTask $model
     * @param type $post
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
                $jobManager->removeNotification(AppGlobalVariables::getSystemId(), $model->id, $teamUid);
                if(in_array($model->create_by, $teamUid)){
                    $jobManager->addNotification (AppGlobalVariables::getSystemId(), $model->id, $model->create_by);
                    $jobManager->setNotificationHasReady(AppGlobalVariables::getSystemId(), $model->create_by, $model->id);
                }
                $producer = ArrayHelper::getValue($multimediaNotice->getProducer($model->id), 'u_id');
                $this->saveMultimediaOperation($model->id, $model->status);
                $this->saveOperationUser($model->id, $producer);
                $multimediaNotice->setAssignNotification($model, $producer);
                $multimediaNotice->sendProducerNotification($model, $model->id, '新任务', 'multimedia/AssignProducer-htm');
                $multimediaNotice->sendCreateByNotification($model, '任务已指派', 'multimedia/AssignCreateBy-html');
            }else 
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            throw new NotFoundHttpException("操作失败！".$ex->getMessage());
        }
    }
    
    /**
     * 多媒体任务创建时
     * @param MultimediaTask $model
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
            }else 
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            throw new NotFoundHttpException("操作失败！".$ex->getMessage());
        }
    }
    
    /**
     * 多媒体任务更新时
     * @param MultimediaTask $model
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
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['subject' => $model->name]);
            }else 
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            throw new NotFoundHttpException("操作失败！".$ex->getMessage());
        }
    }
    
    /**
     * 多媒体任务寻求支撑时
     * @param MultimediaTask $model
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
                $jobManager->addNotification(AppGlobalVariables::getSystemId(), $model->id, $assignPersonId);      //添加通知
                $multimediaNotice->sendAssignPersonNotification($model, $model->make_team, '支撑请求', 'multimedia/SeekBrace-html');
            }else 
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 多媒体任务取消支撑时
     * @param MultimediaTask $model
     * @param type $oldMakeTeam             旧的制作团队
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
                $jobManager->removeNotification(AppGlobalVariables::getSystemId(), $model->id, $assignPersonId);
                $multimediaNotice->sendAssignPersonNotification ($model, $oldMakeTeam, '取消支撑', 'multimedia/CancelBrace-html');
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 多媒体任务开始制作时
     * @param MultimediaTask $model
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
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status'=>$model->getStatusName()]);
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 多媒体任务提交制作时
     * @param MultimediaTask $model
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
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->progress, 'status'=>$model->getStatusName()]);
                $multimediaNotice->sendCreateByNotification ($model, '任务提交', 'multimedia/SubmitMake-html');
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 多媒体任务完成制作时
     * @param MultimediaTask $model
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
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->getStatusProgress(), 'status'=>$model->getStatusName()]);
                $jobManager->cancelNotification(AppGlobalVariables::getSystemId(), $model->id, $model->create_by);
            }else{
                foreach($model->getErrors() as $error){
                    foreach($error as $name=>$value)
                        $errors[] = $value;
                }
                throw new \Exception(implode(',', $errors));
            }
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 多媒体任务恢复制作时
     * @param MultimediaTask $model
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
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->id, ['progress'=> $model->getStatusProgress(), 'status'=> $model->getStatusName()]);
                $jobManager->removeNotification(AppGlobalVariables::getSystemId(), $model->id, $producer);
                $jobManager->addNotification (AppGlobalVariables::getSystemId(), $model->id, $producer);
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
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
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }

    /**
     * 多媒体任务创建审核时
     * @param MultimediaCheck $model
     */
    public function saveCreateCheckTask($model)
    {
        /* @var $multimediaNotice MultimediaNoticeTool */
        $multimediaNotice = MultimediaNoticeTool::getInstance();
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        $producer = ArrayHelper::getValue($multimediaNotice->getProducer($model->task_id), 'u_id');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            $number = MultimediaTask::updateAll(['status'=>  MultimediaTask::STATUS_UPDATEING], ['id' => $model->task_id]);
            if($model->save() && $number > 0){
                $this->saveMultimediaOperation($model->task_id, MultimediaTask::STATUS_UPDATEING);
                $this->saveOperationUser($model->task_id, $producer);
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->task_id, ['status'=> MultimediaTask::$statusNmae[MultimediaTask::STATUS_UPDATEING]]);
                $multimediaNotice->sendProducerNotification ($model, $model->task_id, '审核意见', 'multimedia/CreateCheck-html');
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 多媒体任务提交审核时
     * @param MultimediaCheck $model
     */
    public function saveSubmitCheckTask($model)
    {
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
                $jobManager->updateJob(AppGlobalVariables::getSystemId(), $model->task_id, ['status'=> MultimediaTask::$statusNmae[MultimediaTask::STATUS_CHECKING]]);
                $multimediaNotice->sendCreateByNotification ($model, '审核提交', 'multimedia/SubmitCheck-html');
            }else
                throw new \Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 获取多媒体任务结果
     * @param MultimediaQuery $multimediaQuery                  
     * @param ActiveQuery $results                  
     * @param string $createBy                      创建者
     * @param string $producer                      制作人
     * @param string $assignPerson                  指派人
     * @param integer $createTeam                   创建团队
     * @param integer $makeTeam                     制作团队
     * @param integer $contentType                  类型
     * @param integer $itemTypeId                   行业
     * @param integer $itemId                       层次/类型
     * @param integer $itemChildId                  专业/工种
     * @param integer $courseId                     课程
     * @param integer $status                       状态
     * @param string $time                          时间段
     * @param string $keyword                       关键字
     * @param integer $mark                         标识
     * @return Query                                返回查询结果对象
     */
    public function getMultimediaTask($createBy = null, $producer = null, $assignPerson = null, $createTeam = null, 
        $makeTeam = null, $contentType = null, $itemTypeId = null, $itemId = null, $itemChildId = null, $courseId = null,
        $status = null, $time = null, $keyword = null, $mark = null)
    {
        /* @var $multimediaQuery MultimediaQuery */
        $multimediaQuery = MultimediaQuery::getInstance();
        /* @var $results ActiveQuery */
        $results = $multimediaQuery->getMultimediaTaskTable();
        
        $results->andFilterWhere(['or',[$mark == null ? 'or' : 'and', 
            ['Multimedia_task.create_by' => $createBy], ['TeamMember.u_id' => $producer]], 
            ['or', ['Assign_make_team.u_id' => $assignPerson], ['Assign_create_team.u_id' => $assignPerson]]
        ]);
        $results->andFilterWhere([
            'Multimedia_task.id' => null,
            'Multimedia_task.create_team' => $createTeam,
            'Multimedia_task.make_team' => $makeTeam,
            'Multimedia_task.content_type' => $contentType,
            'Multimedia_task.item_type_id' => $itemTypeId,
            'Multimedia_task.item_id' => $itemId,
            'Multimedia_task.item_child_id' => $itemChildId,
            'Multimedia_task.course_id' => $courseId,
        ]);
        $results->andFilterWhere(['IN', 'Multimedia_task.status', 
            ($status == MultimediaTask::STATUS_DEFAULT ? MultimediaTask::$defaultStatus : $status)
        ]);
        
        if($time != null){
            $time = explode(" - ",$time);
            if($status == MultimediaTask::STATUS_DEFAULT)
                $results->andFilterWhere(['<=', 'Multimedia_task.created_at', strtotime($time[1])]);
            else if($status == MultimediaTask::STATUS_COMPLETED)
                $results->andFilterWhere(['between', 'Multimedia_task.real_carry_out', $time[0],$time[1]]);
            else if($status == MultimediaTask::STATUS_CANCEL)
                $results->andFilterWhere(['between', 'Multimedia_task.created_at', strtotime($time[0]),strtotime($time[1])]);
            else
                $results->andFilterWhere(['or', 
                    ['between', 'Multimedia_task.created_at', strtotime($time[0]),strtotime($time[1])], 
                    ['between', 'Multimedia_task.real_carry_out', $time[0],$time[1]]
                ]);
        }
        $results->andFilterWhere(['or',
            ['like', 'Multimedia_task.name', $keyword],
            ['like', 'Fm_item_type.name', $keyword],
            ['like', 'Fm_item.name', $keyword],
            ['like', 'Fm_item_child.name', $keyword],
            ['like', 'Fm_course.name', $keyword]
        ]);
        
        return $results;
    }

    /**
     * 获取当前用户所在的团队
     * @param type $uId         用户ID
     * @return type
     */
    public function getHotelTeam($uId){
        $teamMember = TeamMember::find()
                      ->where(['u_id' => $uId, 'is_delete' => 'N'])
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
        if(!empty($assign)){
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
        if(!empty($producers)){
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
        if(!empty($check)){
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
     * @return boolean                               true为是
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
