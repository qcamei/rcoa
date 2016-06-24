<?php
namespace frontend\modules\shoot;

use common\models\shoot\ShootAppraiseTemplate;
use common\models\shoot\ShootAppraiseWork;
use common\models\shoot\ShootBookdetail;
use common\models\shoot\ShootBookdetailRoleName;
use common\models\shoot\ShootHistory;
use common\wskeee\job\JobManager;
use wskeee\rbac\RbacName;
use Yii;
use yii\db\Exception;
use yii\web\NotFoundHttpException;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class BookdetailTool{
    /**
     * 设置创建预约拍摄时默认属性
     * @param type $model
     * @param type $post
     */
    public function setNewBookdetailProperty($model, $post){
        $model->status = ShootBookdetail::STATUS_BOOKING;
        $model->u_booker = Yii::$app->user->id;
        $model->create_by = Yii::$app->user->id;

        !isset($post['site_id']) ? : $model->site_id = $post['site_id'];
        !isset($post['book_time']) ? : $model->book_time = $post['book_time'];
        !isset($post['index']) ? : $model->index = $post['index'];

        $model->setScenario(ShootBookdetail::SCENARIO_TEMP_CREATE);
        $model->save();
        $model->setScenario(ShootBookdetail::SCENARIO_DEFAULT);

        /** 设置上下晚预约的默认开始时间 */
        $model->index == $model::TIME_INDEX_MORNING ? $model->start_time = $model::START_TIME_MORNING :'';
        $model->index == $model::TIME_INDEX_AFTERNOON ? $model->start_time = $model::START_TIME_AFTERNOON : '';
        $model->index == $model::TIME_INDEX_NIGHT ? $model->start_time = $model::START_TIME_NIGHT : '';

    }
    
    /**
     * 保存数据到Bookdetail表里面
     * @param ShootBookdetail $model
     * @param  $post
     * @param  $isIntersection  是否存在交集
     */
     public function saveNewBookdetail($model, $post, $isIntersection = false)
    {
        /* @var $bdNoticeTool BookdetailNoticeTool */
        $bdNoticeTool = Yii::$app->get('bdNoticeTool');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if(!$isIntersection && $model->save()){
                //保存接洽人到ShootBookdetailRoleName表里 
                $this->saveShootBookdetailRoleName($model->id, RbacName::ROLE_CONTACT, $post); 
                //添加任务管理
                $bdNoticeTool->saveJobManager($model, $post);
                //创建--给所有摄影组长发送通知
                $bdNoticeTool->sendShootLeadersNotification($model, '新增', 'shoot\newShoot-html');
            }
            else{ throw new Exception(json_encode($model->getErrors()));}
               
            $work = new ShootAppraiseWork(['b_id'=>$model->id]);
            if(!$work->save(ShootAppraiseTemplate::find()->asArray()->all()))
                throw new Exception(json_encode($model->getErrors()));
            $trans->commit(); //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
            return true;
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            throw new NotFoundHttpException("保存任务失败，有接洽人存在被指派了！".$ex->getMessage()); 
            return false;
        }
    }

    /**
     * 拍摄任务指派时
     * @param type $model
     * @param type $oldRoleNmae  旧角色
     * @param type $assignedRoleNmae 已指派角色
     * @param type $post
     * @param type $isIntersection  是否有交集
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function saveAssignTask($model, $oldRoleNmae, $assignedRoleNmae, $post, $isIntersection = false){
        /* @var $bdNoticeTool BookdetailNoticeTool */
        $bdNoticeTool = Yii::$app->get('bdNoticeTool');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model->save() && $oldRoleNmae != null){
                //清空【已指派角色】数据
                $this->emptyShootBookdetailRoleName($model->id, RbacName::ROLE_SHOOT_MAN); 
                $isIntersection = false;
            }
            if(!$isIntersection) {
                //保存【已指派摄影师】
                $this->saveShootBookdetailRoleName($model->id, RbacName::ROLE_SHOOT_MAN, $post);
                //设置指派摄影师用户任务通知关联
                $bdNoticeTool->setAssignNotification($model, $oldRoleNmae, $assignedRoleNmae, $post); 
                //保存编辑信息
                $this->saveNewHistory($model);   
                /** 摄影师非null的时候为【更改指派】通知 */
                if($oldRoleNmae != null){
                    //更改指派--给接洽人发通知
                    $bdNoticeTool->sendContacterNotification($model, '更改指派', 'shoot\ShootEditAssign-u_contacter-html');   
                    //更改指派--给旧摄影师发通知
                    $bdNoticeTool->sendShootManNotification($model, '更改指派', 'shoot\ShootEditAssign-u_shoot_man-html', $oldRoleNmae);    
                    //更改指派--给新摄影师发通知
                    $bdNoticeTool->sendShootManNotification($model, '更改指派', 'shoot\ShootAssign-u_shoot_man-html');        
                }else{
                    //指派--给编导发通知
                    $bdNoticeTool->sendBookerNotification($model, '指派', 'shoot\ShootAssign-u_contacter-html');     
                    //指派--给接茬人发通知
                    $bdNoticeTool->sendContacterNotification($model, '指派', 'shoot\ShootAssign-u_contacter-html');  
                    //指派--给摄影师发通知
                    $bdNoticeTool->sendShootManNotification($model, '指派', 'shoot\ShootAssign-u_shoot_man-html');   
                    //指派--给老师发通知
                    $bdNoticeTool->sendTeacherNotification($model, '指派', 'shoot\ShootAssign-u_teacher-html');     
                }
            } else{ throw new Exception(json_encode($model->getErrors()));}
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！'); 
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            throw new NotFoundHttpException("保存任务失败，有摄影师存在被指派了！".$ex->getMessage());
        }
    }

    /**
     * 拍摄任务更新时
     * @param type $model
     * @param type $post
     * @param type $assignedRoleNmae 已指派角色
     * @param type $isIntersection 是否存在交集
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function saveUpdateTask($model, $post, $assignedRoleNmae, $isIntersection = false){
        /* @var $jobManager JobManager */
        $jobManager = Yii::$app->get('jobManager');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {
            if($model->save()){
                //清空【已指派角色】数据
                $num = $this->emptyShootBookdetailRoleName($model->id, RbacName::ROLE_CONTACT);
                if($num > 0) $isIntersection = false;
            }
            if(!$isIntersection) {
                //保存【已指派接洽人】
                $this->saveShootBookdetailRoleName($model->id, RbacName::ROLE_CONTACT, $post); 
                //更新任务通知表
                $jobManager->updateJob(2, $model->id, ['subject' => $model->fwCourse->name]);  
                //清空用户与任务通知关联
                $jobManager->removeNotification(2, $model->id, $assignedRoleNmae);  
                //添加用户与任务通知关联
                $jobManager->addNotification(2, $model->id, $post);  
                //保存编辑信息
                $this->saveNewHistory($model); 
            }else{ throw new Exception(json_encode($model->getErrors()));}
            $trans->commit(); //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        } catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            throw new NotFoundHttpException("保存任务失败，有接洽人存在被指派了！".$ex->getMessage()); 
        }
    }

    /**
     * 拍摄任务取消时
     * @param type $model
     * @param type $roleNmaeAll 所有角色
     */
    public function saveCancelTask($model, $roleNmaeAll){
        /* @var $bdNoticeTool BookdetailNoticeTool */
        $bdNoticeTool = Yii::$app->get('bdNoticeTool');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                //拍摄任务取消时修改iscancel字段
                ShootBookdetailRoleName::updateAll(['iscancel' => 'Y'],'b_id = '.$model->id); 
                //取消用户任务通知关联
                $bdNoticeTool->cancelJobManager($model, $roleNmaeAll); 
                 //保存编辑信息
                $this->saveNewHistory($model);  
                //取消--给所有摄影组长发通知
                $bdNoticeTool->sendShootLeadersNotification($model, '取消', 'shoot\CancelShoot-html'); 
                /** 非编导自己取消任务才发送 */
                if(!$model->u_booker)  
                    //取消--给编导发通知
                    $bdNoticeTool->sendBookerNotification($model, '取消', 'shoot\CancelShoot-html');
                /** 摄影师非空才发送 */
                if(!empty($model->u_shoot_man)){
                    //取消--给接洽人发通知
                    $bdNoticeTool->sendContacterNotification($model, '取消', 'shoot\CancelShoot-html');  
                    //取消--给摄影师发通知
                    $bdNoticeTool->sendShootManNotification($model, '取消', 'shoot\CancelShoot-html');  
                    //取消--给老师发通知
                    $bdNoticeTool->sendTeacherNotification($model, '取消', 'shoot\CancelShoot-u_teacher-html');  
                }
            }
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }

    /**
     * 保存数据到ShootBookdetailRoleName表里
     * @param type $b_id  任务id
     * @param type $roleName 角色
     * @param type $post 
     */
    public function saveShootBookdetailRoleName($b_id, $roleName, $post){
        $values = [];
        /** 重组提交的数据为$values数组 */
        foreach($post as $key => $value)
        {
            $values[] = [
                'b_id' => $b_id,
                'u_id' => $value,
                'role_name' => $roleName,
                'primary_foreign' => $key == 0 ? 1 : 0,
            ];
        }
        
        /** 添加$values数组到ShootBookdetailRoleName表里 */
        Yii::$app->db->createCommand()->batchInsert(ShootBookdetailRoleName::tableName(), 
        [
            'b_id',
            'u_id',
            'role_name',
            'primary_foreign'
        ], $values)->execute();
    }
    
    /**
     * 历史记录保存
     * @param type $model
     */
    public function saveNewHistory($model)
    {
        $post = Yii::$app->getRequest()->getBodyParams();
        $history = new ShootHistory();
        /**历史记录非空保存*/
        if(!empty($post['editreason'])){
            $history->b_id = $model->id;
            $history->u_id = Yii::$app->user->id;
            $history->history = $post['editreason'];
            $history->save();
        } 
    }
    
    /**
     * 清空【已指派角色】数据
     * @param type $b_id  任务ID
     * @param type $roleNmae 角色
     */
    public function emptyShootBookdetailRoleName($b_id, $roleName){
        return ShootBookdetailRoleName::deleteAll([
                'and', 
                'b_id ='.$b_id, 
                'role_name ="'.$roleName.'"'
            ]);
    }
    
}
