<?php

namespace mconline\modules\mcbs\utils;

use common\models\mconline\McbsActionLog;
use common\models\mconline\McbsCourseBlock;
use common\models\mconline\McbsCourseChapter;
use common\models\mconline\McbsCoursePhase;
use common\models\mconline\McbsCourseUser;
use common\models\User;
use mconline\modules\mcbs\utils\McbsAction;
use Yii;
use yii\db\Exception;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class McbsAction 
{
   
    /**
     * 初始化类变量
     * @var McbsAction 
     */
    private static $instance = null;
    
    /**
     * 获取单例
     * @return McbsAction
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new McbsAction();
        }
        return self::$instance;
    }
    
    /**
     * 添加协作人员操作
     * @param McbsCourseUser $model
     * @param post $post
     * @throws Exception
     */
    public function CreateHelpman($model, $post)
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            $results = $this->saveMcbsCourseUser($post);
            if($results != null){
                $this->saveMcbsActionLog([
                    'action'=>'增加','title'=>'协作人员',
                    'content'=>implode('、',$results['nickname']),
                    'course_id'=>$results['course_id']]);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 编辑协作人员操作
     * @param McbsCourseUser $model
     * @throws Exception
     */
    public function UpdateHelpman($model)
    {
        //旧权限
        $oldPrivilege = $model->getOldAttribute('privilege');
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->saveMcbsActionLog([
                    'action'=>'修改','title'=>'协作人员',
                    'content'=>'调整【'.$model->user->nickname.'】以下属性｛权限：【旧】'.McbsCourseUser::$privilegeName[$oldPrivilege].
                               ' >> 【新】'.McbsCourseUser::$privilegeName[$model->privilege].'｝',
                    'course_id'=>$model->course_id]);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }  
    
    /**
     * 编辑协作人员操作
     * @param McbsCourseUser $model
     * @throws Exception
     */
    public function DeleteHelpman($model)
    {
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->delete()){
                $this->saveMcbsActionLog([
                    'action'=>'删除','title'=>'协作人员',
                    'content'=>'删除【'.$model->user->nickname.'】的协作',
                    'course_id'=>$model->course_id]);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }   
    
    /**
     * 添加课程框架操作
     * @throws Exception
     */
    public function CreateCouFrame($model,$title,$course_id,$relative_id=null)
    {
        $is_add = !empty($model->value_percent) ? "（{$model->value_percent}分）" : null;
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->saveMcbsActionLog([
                    'action'=>'增加','title'=>"{$title}管理",
                    'content'=>"{$model->name}{$is_add}",
                    'course_id'=>$course_id,
                    'relative_id'=>$relative_id]);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 编辑课程框架操作
     * @throws Exception
     */
    public function UpdateCouFrame($model,$title,$course_id,$relative_id=null)
    {
        //获取所有旧属性值
        $oldAttr = $model->getOldAttributes();
        $is_show = isset($oldAttr['value_percent']) ? "（{$oldAttr['value_percent']}分）" : null;
        $is_empty = !empty($model->value_percent) ? "（{$model->value_percent}分）" : null;
        $is_add = $is_show != null && $is_empty != null ? 
                "占课程总分比例：【旧】{$oldAttr['value_percent']}% >> 【新】{$model->value_percent}%,\n\r" : null;
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->save()){
                $this->saveMcbsActionLog([
                    'action'=>'修改','title'=>"{$title}管理",
                    'content'=>"调整 【{$oldAttr['name']}{$is_show}】 以下属性：\n\r".
                                "名称：【旧】{$oldAttr['name']}{$is_show}>>【新】{$model->name}{$is_empty},\n\r".
                                "{$is_add}描述：【旧】{$oldAttr['des']} >> 【新】{$model->des}",
                    'course_id'=>$course_id,
                    'relative_id'=>$relative_id]);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 删除课程框架操作
     * @throws Exception
     */
    public function DeleteCouFrame($model,$title,$course_id,$relative_id=null)
    {
        $is_add = !empty($model->value_percent) ? "（{$model->value_percent}分）" : null;
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($model->delete()){
                $this->saveMcbsActionLog([
                    'action'=>'删除','title'=>"{$title}管理",
                    'content'=>"{$model->name}{$is_add}",
                    'course_id'=>$course_id,
                    'relative_id'=>$relative_id]);
            }else
                throw new Exception($model->getErrors());
            
            $trans->commit();  //提交事务
            Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 保存操作记录
     * $params[
     *   'action' => '动作',
     *   'title' => '标题',
     *   'content' => '内容',
     *   'created_by => '创建者',
     *   'course_id' => '课程id',
     *   'relative_id' => '相关id'
     * ]
     * @param array $params                                   
     */
    public function saveMcbsActionLog($params=null)
    {
        $action = ArrayHelper::getValue($params, 'action');                                 //动作
        $title = ArrayHelper::getValue($params, 'title');                                   //标题  
        $content = ArrayHelper::getValue($params, 'content');                               //内容
        $create_by = ArrayHelper::getValue($params, 'create_by', Yii::$app->user->id);    //创建者
        $course_id = ArrayHelper::getValue($params, 'course_id');                           //课程id
        $relative_id = ArrayHelper::getValue($params, 'relative_id');                       //相关id
        //values数组
        $values = [
            'action' => $action,'title' => $title,'content' => $content,
            'create_by' => $create_by,'course_id' => $course_id,'relative_id' => $relative_id,
            'created_at' => time(),'updated_at' => time(),
        ];
       
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->insert(McbsActionLog::tableName(), $values)->execute();
    }
}
