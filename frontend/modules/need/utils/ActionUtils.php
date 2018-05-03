<?php

namespace frontend\modules\need\utils;

use common\models\need\NeedAttachments;
use common\models\need\NeedContent;
use common\models\need\NeedContentPsd;
use common\models\need\NeedTask;
use common\models\need\NeedTaskLog;
use common\models\need\NeedTaskUser;
use common\models\RecentContacts;
use common\models\team\TeamCategory;
use common\models\User;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\db\Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;



class ActionUtils 
{
   
    /**
     * 初始化类变量
     * @var ActionUtils 
     */
    private static $instance = null;
    
    /**
     * 获取单例
     * @return ActionUtils
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new ActionUtils();
        }
        return self::$instance;
    }
    
    /**
     * 添加需求任务
     * @param NeedTask $model
     * @param array $post
     * @throws Exception
     */
    public function CreateNeedTask($model, $post)
    {
        $model->status = NeedTask::STATUS_CREATEING;
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $model->updateAll(['is_del' => 1], ['status' => NeedTask::STATUS_DEFAULT]);
                $this->saveNeedAttachments($model->id, ArrayHelper::getValue($post, 'files'));
                $this->saveNeedTaskLog(['action'=>'增加', 'title'=> '任务管理', 'need_task_id' => $model->id]);
            }else{
                throw new Exception($model->getErrors());
            }
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            throw new NotFoundHttpException('操作失败' . $ex->getMessage()); 
        }
    }
    
    /**
     * 更新需求任务
     * @param NeedTask $model
     * @param array $post
     * @throws Exception
     */
    public function UpdateNeedTask($model, $post)
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->saveNeedAttachments($model->id, ArrayHelper::getValue($post, 'files'));
                $this->saveNeedTaskLog(['action'=>'修改', 'title'=> '任务管理', 'need_task_id' => $model->id]);
            }else{
                throw new Exception($model->getErrors());
            }
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            throw new NotFoundHttpException('操作失败' . $ex->getMessage()); 
        }
    }
    
    /**
     * 提交审核需求任务
     * @param NeedTask $model
     * @throws Exception
     */
    public function SubmitAuditNeedTask($model)
    {
        $model->status = NeedTask::STATUS_AUDITING;
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save(false, ['status'])){
                $this->saveNeedTaskLog(['action'=>'审核', 'title'=> '审核管理', 
                    'content' => '提交审核', 'need_task_id' => $model->id]);
                NoticeUtils::sendAuditByNotification($model, '审核申请', 'need/_audit_request');
            }else{
                throw new Exception($model->getErrors());
            }
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error', '操作失败！' . $ex->getMessage());
        }
    }
    
    /**
     * 取消审核需求任务
     * @param NeedTask $model
     * @throws Exception
     */
    public function CancelAuditNeedTask($model)
    {
        $model->status = NeedTask::STATUS_CREATEING;
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save(false, ['status'])){
                $this->saveNeedTaskLog(['action'=>'审核', 'title'=> '审核管理', 
                    'content' => '取消审核', 'need_task_id' => $model->id]);
            }else{
                throw new Exception($model->getErrors());
            }
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error', '操作失败！' . $ex->getMessage());
        }
    }
    
    /**
     * 发布需求任务
     * @param NeedTask $model
     * @throws Exception
     */
    public function PublishNeedTask($model)
    {
        $model->status = NeedTask::STATUS_WAITRECEIVE;
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save(false, ['status'])){
                $this->saveNeedTaskLog(['action'=>'发布', 'title'=> '任务管理', 
                    'content' => '发布任务', 'need_task_id' => $model->id]);
                NoticeUtils::sendReceiveByNotification($model, self::getHasReceiveToDeveloper(), '需求发布', 'need/_receive_request');
            }else{
                throw new Exception($model->getErrors());
            }
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error', '操作失败！' . $ex->getMessage());
        }
    }
    
    /**
     * 取消发布需求任务
     * @param NeedTask $model
     * @throws Exception
     */
    public function CancelPublishNeedTask($model)
    {
        $model->status = NeedTask::STATUS_CREATEING;
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save(false, ['status'])){
                $this->saveNeedTaskLog(['action'=>'发布', 'title'=> '任务管理', 
                    'content' => '取消发布', 'need_task_id' => $model->id]);
            }else{
                throw new Exception($model->getErrors());
            }
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error', '操作失败！' . $ex->getMessage());
        }
    }
    
    /**
     * 审核需求任务
     * @param NeedTask $model
     * @param array $post
     * @throws Exception
     */
    public function AuditNeedTask($model, $post)
    {
        $model->status = !$post['result'] ? NeedTask::STATUS_CHANGEAUDIT : NeedTask::STATUS_WAITRECEIVE;
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save(false, ['status'])){
                $this->saveNeedTaskLog(['action'=>'审核', 'title'=> '审核管理', 
                    'content' => $post['remarks'], 'need_task_id' => $model->id]);
                if(!$post['result']){
                    NoticeUtils::sendCreateByNotification($model, '审核反馈', 'need/_audit_result', $post);
                }else {
                    NoticeUtils::sendReceiveByNotification($model, self::getHasReceiveToDeveloper(), '需求发布', 'need/_receive_request');
                }
            }else{
                throw new Exception($model->getErrors());
            }
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error', '操作失败！' . $ex->getMessage());
        }
    }
    
    /**
     * 承接需求任务
     * @param NeedTask $model
     * @param array $post
     * @throws Exception
     */
    public function ReceiveNeedTask($model)
    {
        $model->status = NeedTask::STATUS_WAITSTART;
        $model->receive_by = \Yii::$app->user->id;
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save(false, ['status', 'receive_by'])){
                $userModel = new NeedTaskUser([
                    'user_id' => $model->receive_by, 'need_task_id' => $model->id,
                    'performance_percent' => 1, 'privilege' => NeedTaskUser::ALL
                ]);
                $userModel->save();
                $this->saveNeedTaskLog(['action'=>'承接', 'title'=> '开发管理', 
                    'content' => '承接人：'.$model->receiveBy->nickname, 'need_task_id' => $model->id]);
                
                NoticeUtils::sendCreateByNotification($model, '承接反馈', 'need/_receive_result');
            }else{
                throw new Exception($model->getErrors());
            }
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error', '操作失败！' . $ex->getMessage());
        }
    }
    
    /**
     * 开始制作需求任务
     * @param NeedTask $model
     * @throws Exception
     */
    public function StartMakeNeedTask($model)
    {
        $model->status = NeedTask::STATUS_DEVELOPING;
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save(false, ['status'])){
                $this->saveNeedTaskLog(['action'=>'开始', 'title'=> '开发管理', 
                    'content' => '开始制作需求任务', 'need_task_id' => $model->id]);
            }else{
                throw new Exception($model->getErrors());
            }
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error', '操作失败！' . $ex->getMessage());
        }
    }
    
    /**
     * 转让需求任务
     * @param NeedTask $model
     * @param array $post
     * @throws Exception
     */
    public function TransferNeedTask($model, $post)
    {
        //重组post数组
        foreach ($post as $value) {
            $post += ['NeedTaskUser' => [
                'need_task_id' => $model->id,
                'user_id' => [$model->receive_by],
                'privilege' => NeedTaskUser::ALL,
            ]];
        }
        //获取所有旧属性值
        $oldAttr = $model->getOldAttributes();
        $userModel = User::findOne($oldAttr['receive_by']);
        $taskUserModel = $this->findNeedTaskUserModel($oldAttr['receive_by'], $model->id);
        $taskUserModel->privilege = NeedTaskUser::READONLY;
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save(false, ['receive_by']) && $taskUserModel->update(false, ['privilege'])){
                $results = $this->saveNeedTaskUser($post);
                if($results == null){
                    $taskUserModel = $this->findNeedTaskUserModel($model->receive_by, $model->id);
                    $taskUserModel->privilege = NeedTaskUser::ALL;
                    $taskUserModel->update(false, ['privilege']);
                }
                $this->saveRecentContacts($post);
                $this->saveNeedTaskLog(['action'=>'转让', 'title'=> '开发管理', 
                    'content' => '承接人：【旧】'. $userModel->nickname .' >>【新】' . $model->receiveBy->nickname . '\n\r' .
                        $post['remarks'], 'need_task_id' => $model->id]);
            }else{
                throw new Exception($model->getErrors());
            }
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error', '操作失败！' . $ex->getMessage());
        }
    }
    
    /**
     * 提交验收需求任务
     * @param NeedTask $model
     * @throws Exception
     */
    public function SubmitCheckNeedTask($model)
    {
        $model->load(Yii::$app->request->post());
        $model->status = NeedTask::STATUS_CHECKING;
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save(false, ['status', 'save_path', 'reality_content_cost', 'reality_outsourcing_cost', 'updated_at'])){
                $this->saveNeedTaskLog(['action'=>'验收', 'title'=> '验收管理', 
                    'content' => '提交验收', 'need_task_id' => $model->id]);
                NoticeUtils::sendCreateByNotification($model, '验收申请', 'need/_check_request');
            }else{
                throw new Exception($model->getErrors());
            }
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error', '操作失败！' . $ex->getMessage());
        }
    }
    
    /**
     * 取消验收需求任务
     * @param NeedTask $model
     * @throws Exception
     */
    public function CancelCheckNeedTask($model)
    {
        $model->status = NeedTask::STATUS_DEVELOPING;
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save(false, ['status'])){
                $this->saveNeedTaskLog(['action'=>'验收', 'title'=> '验收管理', 
                    'content' => '取消验收', 'need_task_id' => $model->id]);
            }else{
                throw new Exception($model->getErrors());
            }
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error', '操作失败！' . $ex->getMessage());
        }
    }
    
    /**
     * 验收需求任务
     * @param NeedTask $model
     * @param array $post
     * @throws Exception
     */
    public function CheckNeedTask($model, $post)
    {
        if(!$post['result']){
            $model->status = NeedTask::STATUS_CHANGECHECK;
        }else {
            $model->status = NeedTask::STATUS_FINISHED;
            $model->finish_time = time();
        }
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save(false, ['status', 'finish_time'])){
                $this->saveNeedTaskLog(['action'=> '验收', 'title'=> '验收管理', 
                    'content' => $post['remarks'], 'need_task_id' => $model->id]);
                if($post['result']){
                    $this->saveNeedTaskLog(['action'=> '完成', 'title'=> '任务管理', 
                        'content' => '验收已通过，任务结束', 'need_task_id' => $model->id]);
                }else{
                    NoticeUtils::sendReceiveByNotification($model, ['guid' => $model->receiveBy->guid], '验收反馈', 'need/_check_result', $post);
                }
            }else{
                throw new Exception($model->getErrors());
            }
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error', '操作失败！' . $ex->getMessage());
        }
    }
    
    /**
     * 删除需求任务
     * @param NeedTask $model
     * @param array $post
     * @throws Exception
     */
    public function DeleteNeedTask($model, $post)
    {
        $model->is_del = 1;
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save(false, ['is_del'])){
                $this->saveNeedTaskLog(['action' => '删除', 'title'=> '任务管理', 
                    'content' => $post['remarks'], 'need_task_id' => $model->id]);
                if($model->status < NeedTask::STATUS_WAITRECEIVE && !empty($model->audit_by)){
                    NoticeUtils::sendAuditByNotification($model, '需求取消', 'need/_cancel_task', $post);
                }
                if($model->status > NeedTask::STATUS_WAITRECEIVE){
                    NoticeUtils::sendCreateByNotification($model, '需求取消', 'need/_cancel_task', $post);
                }
            }else{
                throw new Exception($model->getErrors());
            }
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            throw new NotFoundHttpException('操作失败' . $ex->getMessage()); 
        }
    }
    
    /**
     * 添加开发内容
     * @param NeedContent $model
     * @param array $post
     * @param array $workitemIds
     * @throws Exception
     */
    public function CreateNeedContent($model, $post, $workitemIds)
    {
        $numbers = ArrayHelper::getValue($post, 'NeedContent.number');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            $this->saveNeedContent($model->need_task_id, $numbers, $workitemIds, 'plan_num');
            
            $trans->commit();  //提交事务
            return [
                'code' => 200,
                'data' => '',
                'message' => '操作成功！'
            ];
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            return [
                'code' => 404,
                'data' => '',
                'message' => '操作失败！' . $ex->getMessage()
            ];
        }
    }
    
    /**
     * 修改开发内容
     * @param NeedContent $model
     * @param array $post
     * @param array $workitemIds
     * @throws Exception
     */
    public function UpdateNeedContent($model, $post, $workitemIds)
    {
        $numbers = ArrayHelper::getValue($post, 'NeedContent.number');
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            $this->saveNeedContent($model->need_task_id, $numbers, $workitemIds, 'reality_num');
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error', '操作失败！' . $ex->getMessage());
        }
    }
    
    /**
     * 添加协作人员操作
     * @param NeedTaskUser $model
     * @param array $post
     * @throws Exception
     */
    public function CreateNeedTaskUser($model, $post)
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            $results = $this->saveNeedTaskUser($post);
            if($results != null){
                $this->saveRecentContacts($post);
                $this->saveNeedTaskLog(['action' => '增加', 'title' => '开发人员',
                    'content'=>implode('、',$results['nickname']), 'need_task_id' => $results['need_task_id']
                ]);
            }else{
                throw new Exception($model->getErrors());
            }
            
            $trans->commit();  //提交事务
            return [
                'code' => 200,
                'data' => '',
                'message' => '操作成功！'
            ];
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            return [
                'code' => 404,
                'data' => '',
                'message' => '操作失败！' . $ex->getMessage()
            ];
        }
    }
    
    /**
     * 修改协作人员操作
     * @param NeedTaskUser $model
     * @param array $post
     * @throws Exception
     */
    public function UpdateNeedTaskUser($model, $post)
    {
        //获取所有新属性值
        $newAttr = $model->getDirtyAttributes();
        //获取所有旧属性值
        $oldAttr = $model->getOldAttributes();
        //获取主开发人
        $mainDeveloper = $this->findNeedTaskUserModel($model->needTask->receive_by, $model->need_task_id);
        
        $model->performance_percent = ArrayHelper::getValue($post, 'performance_percent');
        $mainDeveloper->performance_percent = $mainDeveloper->performance_percent + $oldAttr['performance_percent'] - $model->performance_percent;
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($mainDeveloper->performance_percent >= 0){
                if($model->update() && $mainDeveloper->update()){
                    $this->saveNeedTaskLog(['action' => '修改', 'title' => '开发人员',
                        'content' => "修改【". $model->user->nickname ."】以下属性：\n\r" .
                            ($oldAttr['performance_percent'] != $model->performance_percent 
                                ? '绩效比值：【旧】'. $oldAttr['performance_percent'] * 100  . '% >>【新】' . $model->performance_percent * 100 . '%' : null), 
                        'need_task_id' => $model->need_task_id
                    ]);
                }else{
                    throw new Exception($model->getErrors());
                }
            }
            $trans->commit();  //提交事务
            return [
                'code' => 200,
                'data' => '',
                'message' => '操作成功！'
            ];
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            return [
                'code' => 404,
                'data' => '',
                'message' => '操作失败！' . $ex->getMessage()
            ];
        }
    }
    
    /**
     * 删除协作人员操作
     * @param NeedTaskUser $model
     * @throws Exception
     */
    public function DeleteNeedTaskUser($model)
    {
        $model->is_del = 1;
        //获取所有旧属性值
        $oldAttr = $model->getOldAttributes();
        //获取主开发人
        $mainDeveloper = $this->findNeedTaskUserModel($model->needTask->receive_by, $model->need_task_id);
        $mainDeveloper->performance_percent = $mainDeveloper->performance_percent + $oldAttr['performance_percent'];
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->update() && $mainDeveloper->update()){
                $this->saveNeedTaskLog(['action' => '删除', 'title' => '开发人员',
                    'content' => '删除【'. $model->user->nickname .'】的协作',  
                    'need_task_id' => $model->need_task_id
                ]);
            }else{
                throw new Exception($model->getErrors());
            }
            
            $trans->commit();  //提交事务
            return [
                'code' => 200,
                'data' => '',
                'message' => '操作成功！'
            ];
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            return [
                'code' => 404,
                'data' => '',
                'message' => '操作失败！' . $ex->getMessage()
            ];
        }
    }
    
    /**
     * 保存创建开发内容
     * @param string $needTaskId
     * @param array $numbers
     */
    private function saveCreateNeedContent($needTaskId, $numbers)
    {
        $psds = ArrayHelper::index(NeedContentPsd::find()->all(), 'workitem_id');
        
        $conetent = [];
        foreach ($numbers as $id => $numArray) {
            foreach ($numArray as $key => $value){
                if($value > 0){
                    $conetent[] = [
                        'need_task_id' => $needTaskId,  'workitem_type_id' => $psds[$id]['workitem_type_id'],
                        'workitem_id' => $id, 'is_new' => $key, 'price' => !$key ? $psds[$id]['price_new'] : $psds[$id]['price_remould'],
                        'plan_num' => $value, 'sort_order' => $psds[$id]['sort_order'],
                        'created_by' => \Yii::$app->user->id,
                        'created_at' => time(), 'updated_at' => time(),
                    ];
                }
            }
        }
        
        //添加
        Yii::$app->db->createCommand()->batchInsert(NeedContent::tableName(),
            isset($conetent[0]) ? array_keys($conetent[0]) : [], $conetent)->execute();
    }
    
    /**
     * 保存开发内容
     * @param string $needTaskId
     * @param array $numbers
     * @param array $workitemIds
     * @param string $numberName
     */
    private function saveNeedContent($needTaskId, $numbers, $workitemIds, $numberName)
    {
        $psds = ArrayHelper::index(NeedContentPsd::find()->all(), 'workitem_id');
        $conetent = [];
        foreach ($numbers as $id => $numArray) {
            foreach ($numArray as $key => $value){
                $id_key = $id . '_' . $key;
                if($value > 0){
                    if(!in_array($id_key, $workitemIds)){
                        $conetent[] = [
                            'need_task_id' => $needTaskId,  'workitem_type_id' => $psds[$id]['workitem_type_id'],
                            'workitem_id' => $id, 'is_new' => $key, 'price' => !$key ? $psds[$id]['price_new'] : $psds[$id]['price_remould'],
                            $numberName => $value, 'sort_order' => $psds[$id]['sort_order'],
                            'created_by' => \Yii::$app->user->id,
                            'created_at' => time(), 'updated_at' => time(),
                        ];
                    }
                }
                Yii::$app->db->createCommand()->update(NeedContent::tableName(), [$numberName => $value], [
                    'need_task_id' => $needTaskId, 'workitem_type_id' => $psds[$id]['workitem_type_id'], 
                    'workitem_id' => $id, 'is_new' => $key,
                ])->execute();
            }
        }
        
        //添加
        Yii::$app->db->createCommand()->batchInsert(NeedContent::tableName(),
            isset($conetent[0]) ? array_keys($conetent[0]) : [], $conetent)->execute();
    }
    
    /**
     * 保存需求附件
     * @param string $needTaskId
     * @param array $files
     */
    private function saveNeedAttachments($needTaskId, $files)
    {
        //删除所有已存在的附件
        Yii::$app->db->createCommand()->update(NeedAttachments::tableName(), ['is_del' => 1], [
            'need_task_id' => $needTaskId])->execute();
        //循环组装保存新的附件
        $attrFiles = [];
        if(!empty($files)){
            foreach ($files as $index => $file_id) {
                $attrFiles[] = [
                    'need_task_id' => $needTaskId,  'upload_file_id' => $file_id,
                ];
            }
        }
        //添加
        Yii::$app->db->createCommand()->batchInsert(NeedAttachments::tableName(),
            isset($attrFiles[0]) ? array_keys($attrFiles[0]) : [], $attrFiles)->execute();
    }
    
    /**
     * 保存协作人员
     * @param array $post
     * @return array
     */
    private function saveNeedTaskUser($post)
    {
        $latelyUsers = [];
        $need_task_id = ArrayHelper::getValue($post, 'NeedTaskUser.need_task_id');  //需求任务id
        $user_ids = ArrayHelper::getValue($post, 'NeedTaskUser.user_id'); //用户id
        $privilege = ArrayHelper::getValue($post, 'NeedTaskUser.privilege', NeedTaskUser::READONLY);  //权限
        //过滤已经添加的协作人
        $needTaskUsers = (new Query())->select(['user_id'])->from(NeedTaskUser::tableName())
            ->where(['need_task_id'=>$need_task_id, 'is_del' => 0])->all();
        $userIds = ArrayHelper::getColumn($needTaskUsers, 'user_id');
        //组装保存数组
        foreach ($user_ids as $user_id) {
            if(!in_array($user_id, $userIds)){
                $latelyUsers[] = ['need_task_id' => $need_task_id, 'user_id' => $user_id,
                    'privilege' => $privilege, 'created_at' => time(), 'updated_at' => time(),
                ];
            }
        }
        
        /** 添加$latelyUsers数组到表里 */
        $number = Yii::$app->db->createCommand()->batchInsert(NeedTaskUser::tableName(), 
            isset($latelyUsers[0]) ? array_keys($latelyUsers[0]) : [], $latelyUsers)->execute();
       
        if($number > 0){
            $users = (new Query())->select(['nickname'])->from(User::tableName())
                ->where(['id' => $user_ids])->all();
            return  [
                'need_task_id' => $need_task_id,
                'nickname' => ArrayHelper::getColumn($users, 'nickname')
            ];
        } else {
            return [];
        }
    }
    
    /**
     * 保存最近联系人
     * @param array $post
     */
    private function saveRecentContacts($post)
    {
        $userContacts = [];
        $user_ids = ArrayHelper::getValue($post, 'NeedTaskUser.user_id'); //用户id
        //查询过滤已经和自己相关的人
        $contacts = (new Query())->select(['contacts_id'])->from(RecentContacts::tableName())
            ->where(['user_id' => \Yii::$app->user->id])->all();
        $contactsIds = ArrayHelper::getColumn($contacts, 'contacts_id');
        //组装保存数组
        foreach ($user_ids as $user_id) {
            if(!in_array($user_id, $contactsIds)){
                $userContacts[] = [
                    'user_id' => \Yii::$app->user->id, 'contacts_id' => $user_id,
                    'created_at' => time(),'updated_at' => time(),
                ];
            }else {
                Yii::$app->db->createCommand()->update(RecentContacts::tableName(), ['updated_at' => time()], [
                    'user_id' => \Yii::$app->user->id, 'contacts_id' => $user_id])->execute();
            }
        }
        /** 添加$userContacts数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(RecentContacts::tableName(), 
            isset($userContacts[0]) ? array_keys($userContacts[0]) : [], $userContacts)->execute();
    }
    
    /**
     * 保存需求任务记录
     * $params
     * [
     *  'action' => '动作','title' => '标题','content' => '内容',
     *  'created_by' => '创建者','need_task_id' => '需求任务id'
     * ]
     * @param array $params                                   
     */
    private function saveNeedTaskLog($params=null)
    {
        $action = ArrayHelper::getValue($params, 'action'); //动作
        $title = ArrayHelper::getValue($params, 'title');   //标题  
        $content = ArrayHelper::getValue($params, 'content', '无');   //内容
        $created_by = ArrayHelper::getValue($params, 'created_by', \Yii::$app->user->id);    //创建者
        $needTaskId = ArrayHelper::getValue($params, 'need_task_id');   //需求任务id
        
        //$actLog数组
        $actLog = [
            'action' => $action, 'title' => $title, 'content' => $content,
            'created_by' => $created_by, 'need_task_id' => $needTaskId,
            'created_at' => time(), 'updated_at' => time(),
        ];
        
        /** 添加$actLog数组到表里 */
        Yii::$app->db->createCommand()->insert(NeedTaskLog::tableName(), $actLog)->execute();
    }
    
    /**
     * 获取拥有承接的开发人员
     * @return array
     */
    public static function getHasReceiveToDeveloper()
    {
        $members = TeamMemberTool::getInstance()
            ->getTeamMembersUserLeaders(TeamCategory::TYPE_CCOA_DEV_TEAM);
        
        $u_ids = ArrayHelper::getColumn($members, 'u_id');
        $guids = ArrayHelper::getColumn($members, 'guid');
        
        return ['u_id' => $u_ids, 'guid' => $guids];
    }
    
    /**
     * 根据其 user_id and need_task_id 值查找需求任务用户模型。
     * @param string $user_id
     * @param string $need_task_id
     * @return NeedTaskUser the loaded model
     * @throws NotFoundHttpException
     */
    private function findNeedTaskUserModel($user_id, $need_task_id)
    {
        $model = NeedTaskUser::findOne(['user_id' => $user_id, 'need_task_id' => $need_task_id, 'is_del' => 0]);
        if($model != null){
            return $model;
        }
        
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
