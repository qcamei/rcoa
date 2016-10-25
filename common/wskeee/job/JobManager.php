<?php

namespace common\wskeee\job;

use common\wskeee\job\models\Job;
use common\wskeee\job\models\JobNotification;
use Yii;
use yii\db\Exception;
use yii\web\NotFoundHttpException;


/*
 * 1、管理平台所有任务
 * 因不同模块都有独自任务，这在平台总任务无法管理，
 * 所以需要创建一个公共的任务，包括了其它模块任务的共性属性
 * 该类包括公共任务【创建】【修改】【删除】等操作
 * 
 * 2、管理平台所有通知，所有通知与任务相关联
 * 该类包括对通知的【添加】【删除】【获取未读】【获取所有】【设置已读】操作
 */



/**
 * Description of JobManager
 *
 * @author Administrator
 */

class JobManager {
    //---------------------------------------------------------------------------------
    //
    // 任务
    //
    //---------------------------------------------------------------------------------
    
    /**
     * 创建任务
     * @param int $systemId    系统id
     * @param int $relateId       关联任务id
     * @param string $subject     主题/名称
     * @param string $link        联接，默认连接为对应任务的详细页
     * @param string $status      状态
     * @param string $progress    进度 0~100
     * @param string $content     详细内容，默认 无
     * @return bool 成功/失败 true/false
     */
    public function createJob($systemId,$relateId,$subject,$link,$status,$progress=0,$content="")
    {
        if($progress<0)
            $progress = 0;
        if($progress>100)
            $progress == 100;
        
        $job = Job::findOne(['system_id'=>$systemId,'relate_id'=>$relateId]);
        if($job == null)
            $job = new Job();
        $job->system_id = $systemId;
        $job->relate_id = $relateId;
        $job->subject = $subject;
        $job->link = $link;
        $job->status = $status;
        $job->progress = $progress;
        $job->content = $content;
       
        return  $job->save();
    }
    
    /**
     * 更新任务
     * updateJob($systemId,$relateId,['subject'=>$subject, 'link'=>$link, 'content'=>$content, 'progress'=>$progress, 'status'=>$status])
     * @param string $systemId
     * @param int $relateId
     * @param array $params  =[subject, link, content, progress, status]
     */
    public function updateJob($systemId,$relateId,$params)
    {
        $job = Job::findOne(['system_id'=>$systemId,'relate_id'=>$relateId]);
        if($job == null)
        {
            throw new NotFoundHttpException("找不到对应任务！system_id：$systemId, relate_id：$relateId");
            return;
        }
        
        foreach($params as $key => $value)
            $job[$key] = $value;
        
        if($job->save())
            return true;
        else
            Yii::error(json_encode($job->getErrors()), __METHOD__);
        return false;
    }
    
    //---------------------------------------------------------------------------------
    //
    // 通知
    //
    //---------------------------------------------------------------------------------
   /**
    * 添加用户与任务通知关联，关联后可通过 getNotification() 获取相关通知信息
    * @param type $systemId     系统id
    * @param type $relateId        任务id
    * @param type $user         int|array    目标用户
    * @return bool 成功/失败
    * 
    * @see getNotification()
    * @see getUnReadyNotification()
    */
    public function addNotification($systemId,$relateId,$user)
    {
        /* @var $job Job */
        $job;
        try {
            $job = Job::findOne(['system_id' => $systemId,'relate_id'=> $relateId]);
            $rows = [];
            if($job)
            {
                if(!is_array($user))
                    $users = [$user];
                else if(is_array($user))
                    $users = $user;
                else
                    throw new Exception ("不能识别的user：systme_id:$systemId,relate_id:$relateId, user:$user");
                
                if(count($users)==0)
                    throw new Exception ("用户不能为空：systme_id:$systemId,relate_id:$relateId");
                else
                {
                    foreach(array_unique($users) as $user)
                        $rows [] = [$job->id, $user];
                    //echo json_encode($rows);
                }
                Yii::$app->db->createCommand()
                        ->batchInsert(JobNotification::tableName(), ['job_id','u_id'], $rows)
                        ->execute();
                
            }else
                throw new Exception ("找不到对应通知：systme_id:$systemId,relate_id:$relateId");
            return true;
        } catch (Exception $ex) {
            Yii::error("添加通知关联失败！<br/>".$ex->getMessage(), __METHOD__);
            return false;
        }
    }
    
     //---------------------------------------------------------------------------------
    //
    // 通知
    //
    //---------------------------------------------------------------------------------
   /**
    * 清空用户与任务通知关联
    * @param type $systemId     系统id
    * @param type $relateId        任务id
    * @param type $user         int|array    目标用户
    * @return bool 成功/失败
    * 
    * @see getNotification()
    * @see getUnReadyNotification()
    */
    public function removeNotification($systemId,$relateId,$user)
    {
        /* @var $job Job */
        $job;
        try {
            $job = Job::findOne(['system_id' => $systemId,'relate_id'=> $relateId]);
            
            if($job)
            {
                if(!is_array($user))
                    $users = [$user];
                else if(is_array($user))
                    $users = $user;
                else
                    throw new Exception ("不能识别的user：systme_id:$systemId,relate_id:$relateId, user:$user");
                
                if(count($users)==0)
                    throw new Exception ("用户不能为空：systme_id:$systemId,relate_id:$relateId");
                else
                {
                    foreach($users as $user)
                       JobNotification::deleteAll(['and','job_id='.$job->id,'u_id="'.$user.'"']);
                    //echo json_encode($rows);
                }
            }else
                throw new Exception ("找不到对应通知：systme_id:$systemId,relate_id:$relateId");
            return true;
        } catch (Exception $ex) {
            Yii::error("删除通知关联失败！<br/>".$ex->getMessage(), __METHOD__);
            return false;
        }
    }
    
    /**
     * 取消用户与任务通知的关联
     * @param type $systemId       系统id
     * @param type $relateId       关联任务id
     * @param type $user        int|array    单个目标用户、多个目标用户，传空值删除该任务的所有通知关联
     * @return bool 成功/失败
     */
    public function cancelNotification($systemId,$relateId,$user=null)
    {
        /* @var $job Job */
        $job;
      
        try {
            $job = Job::findOne(['system_id' => $systemId,'relate_id'=> $relateId]);
            $jobNotification = JobNotification::findOne(['job_id'=>$job->id]);
            $users;
            $conditions = ['job_id'=>$job->id];
          
            if($job)
            {
                if($user!=null)
                    $users = [$user];
                if(isset($users) && count($users)>0){
                    foreach ($users as $key => $value) 
                        $conditions['u_id'] = $value;
                }
                else
                    throw new Exception ("$user 不能为空！");
                Yii::$app->db->createCommand()
                        ->update(JobNotification::tableName(), ['status'=>  JobNotification::STATUS_END], $conditions)
                        ->execute();
                return true;
            }else
                throw new Exception ("找不到对应通知：systme_id:$systemId,relate_id:$relateId, user:$user");
            
        } catch (Exception $ex) {
            Yii::error("取消通知关联失败！<br/>".$ex->getMessage(), __METHOD__);
            return false;
        }
    }
    
    /**
     * 设置用户对通知已读
     * @param type $systemId     系统id  
     * @param type $user         string    目标用户
     * @param type $relateId        任务id     可为空，为空时设置该用户所有通知已读
     */
    public function setNotificationHasReady($systemId,$user,$relateId=null)
    {
        /* @var $job Job */
        $job;
        try {
            if(!isset($user))
                throw new Exception ("user 不能为null：systme_id:$systemId,relate_id:$relateId, user:$user");
            
            $job = $relateId == null ? null : Job::findOne(['system_id' => $systemId,'relate_id'=> $relateId]);
          
            if($job == null)
                $conditions = ['u_id'=>$user];
            else
                $conditions = ['job_id'=>$job->id,'u_id'=>$user];
            
            
            Yii::$app->db->createCommand()
                ->update(JobNotification::tableName(), ['status'=>  JobNotification::STATUS_NORMAL], $conditions)
                ->execute();
          
            
        } catch (Exception $ex) {
            Yii::error("删除通知关联失败！<br/>".$ex->getMessage(), __METHOD__);
            return false;
        }
    }
    
    /**
     * 获取用户未读通知
     * @param type $user        string    目标用户
     */
    public function getUnReadyNotification($user)
    {
        $notification = JobNotification::find()
                        ->where(['u_id'=>$user,'status'=> JobNotification::STATUS_INIT])
                        ->all();
        return $notification;
    }
    
    /**
     * 获取用户所有通知
     * @param type $user        
     */
    public function getNotification($user)
    {
        $notification = JobNotification::find()
                        ->where(['u_id'=>$user,'status'=> JobNotification::STATUS_NORMAL])
                        ->all();
        return $notification;
    }
    
    /**
     * 获取通知内容信息
     * @param type $jobId       通知id
     * @param type $systemId    系统id
     */
    public function getNotificationInfo($jobId, $systemId)
    {
        $haveReadNotice = Job::find()
            ->filterWhere(['id' => $jobId])
            ->andFilterWhere(['system_id' => $systemId])
            ->all();
        return $haveReadNotice;
    }
    
    /**
     * 获取未读通知内容
     * @param type $jobId       通知id
     * @param type $systemId    系统id
     
    public function getUnReadyNotice($jobId, $systemId)
    {
        $unReadyNotice = Job::find()
            ->where(['status'=>  Job::STATUS_ASSIGN])
            ->orWhere(['status'=> Job::STATUS_SHOOTING])
            ->andWhere(['id' => $jobId])
            ->andWhere($systemId)
            ->with('system')
            ->all();
        return $unReadyNotice;
    }*/
}
