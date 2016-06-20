<?php
namespace wskeee\filemanage;

use wskeee\filemanage\models\FileManage;
use wskeee\filemanage\models\FileManageDetail;
use wskeee\filemanage\models\FileManageOwner;
use wskeee\rbac\RbacManager;
use Yii;
use yii\db\Exception;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class FileManageTool{
        
    /**
     * 递归获取所有目录
     * @param type $id   
     * @param array $fileManageArray  目录数组
     * @return type
     */
    public function getFileManageList($id = null, $fileManageArray = [])
    {
        $fileManages = FileManage::find()->where(['pid' => $id, 'type' => FileManage::FM_FOLDER])->all();
        foreach ($fileManages as $key => $value) {
            $fileManageArray[] = $value;
            $fileManageArray = $this->getFileManageList($value['id'], $fileManageArray);
        }
        return $fileManageArray;
    }
    
    /**
     * 面包屑导航
     * @param type $id
     * @return type
     */
    public function getFileManageBread($id = null)
    {
        $bread = FileManage::find()->where(['id' => $id])->one();
        $pid = $bread['id'];
        $breadArray = [];
        while($pid > 0){
                $breadOne = FileManage::find()->where(['id' => $pid])->one();
                $pid = $breadOne['pid'];
                $breadArray[] = $breadOne;
        }
        $breadArray = array_reverse($breadArray);	//array_reverse返回一个单元顺序相反的数组
        return $breadArray;
    }
    
    /**
     * 加载左边目录
     * @param type $id
     * @return type
     */
   public function getFileManageLeftList($id = null)
    {
        //读取下一级
        $leftListArray = FileManage::find()->where(['pid' => $id])->orderBy('type ASC')->all();
        //如果没有下一级 就读取平级
        if(empty($leftListArray)){
            $leftListOne = FileManage::find()->where(['id' => $id])->one();
            $leftListArray = FileManage::find()->where(['pid' => $leftListOne['pid']])->orderBy('type ASC')->all();
        }
        return $leftListArray;
    }
    
    /**
     * 创建操作
     * @param type $model
     * @param type $owners  所有者
     * @param type $content 内容
     */
    public function createTask($model, $owners, $content){
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {   
            if($model->save()){
                $this->saveFileManageOwner($model->id, $owners);
                if(!empty($content))
                    $this->saveFileManageDetail($model->id, $content);
            }
            $trans->commit();  //提交事务
            //Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }
    
    /**
     * 更新操作
     * @param type $model
     * @param type $owners  所有者
     * @param type $content 内容
     */
    public function updateTask($model, $owners, $content = null){
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {   
            if($model->save()){
                FileManageOwner::deleteAll(['fm_id' => $model->id]);
                $this->saveFileManageOwner($model->id, $owners);
                if(!empty($content))
                    FileManageDetail::updateAll(['content' => $content], ['fm_id' => $model->id]);
            }
            $trans->commit();  //提交事务
            //Yii::$app->getSession()->setFlash('success','操作成功！');
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
        }
    }

    /**
     * 保存数据到FileManageOwner表里
     * @param type $fm_id   文档管理ID
     * @param type $post    post数据
     */
    public function saveFileManageOwner($fm_id, $post){
        $owners = [];
        /** 重组提交的数据为$values数组 */
        foreach($post as $key => $value)
        {
            $owners[] = [
                'fm_id' => $fm_id,
                'owner' => $value,
            ];
        }
        
        /** 添加$owners数组到FileManageOwner表里 */
        Yii::$app->db->createCommand()->batchInsert(FileManageOwner::tableName(), 
        [
            'fm_id',
            'owner',
        ], $owners)->execute();
    }
    
    /**
     * 保存数据到FileManageDetail表里
     * @param type $fm_id   文档管理ID
     * @param type $post    post数据
     */
    public function saveFileManageDetail($fm_id, $post){
        $detail = new FileManageDetail();
        $detail->fm_id = $fm_id;
        $detail->content = $post;
        $detail->save();
    }
    
    /**
     * 当前用户是否属于该所有者
     * @param type $fm_id
     * @return boolean
     */
    public function isFmOwner($fm_id = null){
        /* @var $authManager RbacManager */
        $authManager = Yii::$app->authManager;
        $FmOwner = [];
        if($fm_id != null ){
            foreach (FileManageOwner::findAll($fm_id) as  $fmOwner)
               $FmOwner[] = $authManager->isRole($fmOwner->owner, \Yii::$app->user->id);
            foreach ($FmOwner as $value) {
                if($value == true)
                    return true;
            }
        }
        if($fm_id == null){
            $fmArray = FileManage::find()->where(['pid' => $fm_id])->all();
            foreach ($fmArray as $fmId){
                foreach (FileManageOwner::findAll($fmId->id) as  $fmOwner)
                    $FmOwner[] = $authManager->isRole($fmOwner->owner, \Yii::$app->user->id);
            }
            foreach ($FmOwner as $value) {
                if($value == true)
                    return true;
            }
        }
        return false;
    }
}
