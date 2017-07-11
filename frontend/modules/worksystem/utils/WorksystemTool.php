<?php

namespace frontend\modules\worksystem\utils;

use common\models\demand\DemandTask;
use common\models\team\TeamCategory;
use common\models\team\TeamMember;
use common\models\User;
use common\models\worksystem\WorksystemAddAttributes;
use common\models\worksystem\WorksystemAssignTeam;
use common\models\worksystem\WorksystemAttributes;
use common\models\worksystem\WorksystemContentinfo;
use common\models\worksystem\WorksystemOperation;
use common\models\worksystem\WorksystemOperationUser;
use common\models\worksystem\WorksystemTask;
use common\models\worksystem\WorksystemTaskProducer;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\db\ActiveQuery;
use yii\db\Query;
use yii\helpers\ArrayHelper;



class WorksystemTool 
{
    private static $instance = null;
   
    /**
     * 获取工作系统任务条件选择结果
     * @param WorksystemQuery $_wsQuery                  
     * @param ActiveQuery $results                  
     * @param array $params                         参数                  
     * @return Query                                返回查询结果对象
     */
    public function getWorksystemTaskResult($params)
    {
        $_wsQuery = WorksystemQuery::getInstance();
        $results = $_wsQuery->findWorksystemTaskTable();
        
        $results->andFilterWhere(['or',[ArrayHelper::getValue($params, 'mark') == false ? 'or' : 'and', 
           ['Worksystem_task.create_by' => ArrayHelper::getValue($params, 'create_by')], ['TeamMember.u_id' => ArrayHelper::getValue($params, 'producer')]], 
           ['or', ['Create_team.user_id' => ArrayHelper::getValue($params, 'assign_people')], ['External_team.user_id' => ArrayHelper::getValue($params, 'assign_people')]]
        ]);
        
        $results->andFilterWhere([
            'Worksystem_task.id' => null,
            'Worksystem_task.item_type_id' => ArrayHelper::getValue($params, 'item_type_id'),
            'Worksystem_task.item_id' => ArrayHelper::getValue($params, 'item_id'),
            'Worksystem_task.item_child_id' => ArrayHelper::getValue($params, 'item_child_id'),
            'Worksystem_task.course_id' => ArrayHelper::getValue($params, 'course_id'),
            'Worksystem_task.task_type_id' => ArrayHelper::getValue($params, 'task_type_id'),
            'Worksystem_task.create_team' => ArrayHelper::getValue($params, 'create_team'),
            'Worksystem_task.external_team' => ArrayHelper::getValue($params, 'external_team'),
        ]);
        
        $results->andFilterWhere(['IN', 'Worksystem_task.status', 
            (ArrayHelper::getValue($params, 'status') == WorksystemTask::STATUS_DEFAULT ? WorksystemTask::$defaultStatus : ArrayHelper::getValue($params, 'status'))
        ]);
        
        if(ArrayHelper::getValue($params, 'time') != null){
            $time = explode(" - ", ArrayHelper::getValue($params, 'time'));
            if(ArrayHelper::getValue($params, 'status') == WorksystemTask::STATUS_DEFAULT)
                $results->andFilterWhere(['<=', 'Worksystem_task.created_at', strtotime($time[1])]);
            else if(ArrayHelper::getValue($params, 'status') == WorksystemTask::STATUS_COMPLETED)
                $results->andFilterWhere(['between', 'Worksystem_task.plan_end_time', $time[0],$time[1]]);
            else if(ArrayHelper::getValue($params, 'status') == WorksystemTask::STATUS_CANCEL)
                $results->andFilterWhere(['between', 'Worksystem_task.created_at', strtotime($time[0]),strtotime($time[1])]);
            else
                $results->andFilterWhere(['or', 
                    ['between', 'Worksystem_task.created_at', strtotime($time[0]),strtotime($time[1])], 
                    ['between', 'Worksystem_task.plan_end_time', $time[0],$time[1]]
                ]);
        }
        
        $results->andFilterWhere(['or',
            ['like', 'Worksystem_task.name', ArrayHelper::getValue($params, 'keyword')],
            ['like', 'Fw_item_type.name', ArrayHelper::getValue($params, 'keyword')],
            ['like', 'Fw_item.name', ArrayHelper::getValue($params, 'keyword')],
            ['like', 'Fw_item_child.name', ArrayHelper::getValue($params, 'keyword')],
            ['like', 'Fw_item_course.name', ArrayHelper::getValue($params, 'keyword')]
        ]);
        
        return $results;
    }
       
    /**
     * 获取团队指派人
     * @param integer $teamId                         团队Id
     * @return array
     */
    public function getTeamAssignPeople($teamId)
    {
        $assigns = (new Query())
                ->select(['Ws_assign_team.user_id', 'User.nickname', 'User.ee', 'User.email'])
                ->from(['Ws_assign_team' => WorksystemAssignTeam::tableName()])
                ->leftJoin(['User' => User::tableName()], 'User.id = Ws_assign_team.user_id')
                ->where(['team_id' => $teamId])
                ->all();
        
        $user = [
            'user_id' => ArrayHelper::getColumn($assigns, 'user_id'),
            'nickname' => ArrayHelper::getColumn($assigns, 'nickname'),
            'ee' => ArrayHelper::getColumn($assigns, 'ee'),
            'email' => ArrayHelper::getColumn($assigns, 'email'),
        ];
        
        return $user;
    }
    
    /**
     * 获取所有团队开发经理
     * @param TeamMemberTool $_tmTool                         
     * @return array
     */
    public function getTeamMembersUserLeaders()
    {
        $_tmTool = TeamMemberTool::getInstance();
        $leaders = $_tmTool->getTeamMembersUserLeaders(TeamCategory::TYPE_CCOA_DEV_TEAM);
        $leaderUsers = [];
        foreach ($leaders as $leader){
            if($leader['u_id'] != Yii::$app->user->id)
                $leaderUsers[] = $leader;
        }
        
        $user = [
            'user_id' => ArrayHelper::getColumn($leaderUsers, 'u_id'),
            'nickname' => ArrayHelper::getColumn($leaderUsers, 'nickname'),
            'ee' => ArrayHelper::getColumn($leaderUsers, 'ee'),
            'email' => ArrayHelper::getColumn($leaderUsers, 'email'),
        ];
        
        return $user;
    }
    
    /**
     * 获取所有外包团队成员
     * @param TeamMemberTool $_tmTool                         
     * @param boolean $is_cancel                    是否是取消操作：默认为false               
     * @return array
     */
    public function getEpibolyTeamMembers($is_cancel = false)
    {
        $epibolyUsers = [];
        $_tmTool = TeamMemberTool::getInstance();
        $teamCategory = $_tmTool->getTeamsByCategoryId(TeamCategory::TYPE_EPIBOLY_TEAM);
        $epibolys = $_tmTool->getTeamMembersByTeamId(ArrayHelper::getColumn($teamCategory, 'id'));
        if($is_cancel){
           foreach ($epibolys as $item) {
               if($item['u_id'] != Yii::$app->user->id)
                   $epibolyUsers[] = $item;
           }
        }
        $epibolyUsers = !$is_cancel ? $epibolys : $epibolyUsers;
        
        $user = [
            'user_id' => ArrayHelper::getColumn($epibolyUsers, 'u_id'),
            'nickname' => ArrayHelper::getColumn($epibolyUsers, 'nickname'),
            'ee' => ArrayHelper::getColumn($epibolyUsers, 'ee'),
            'email' => ArrayHelper::getColumn($epibolyUsers, 'email'),
        ];
        
        return $user;
    }

    /**
     * 获取工作系统任务制作人
     * @param integer $taskId                         工作系统任务id
     * @return array
     */
    public function getWorksystemTaskProducer($taskId)
    {
        $producers = (new Query())
                ->select(['Ws_task_producer.worksystem_task_id', 'Team_member.u_id', 'User.nickname', 'User.ee', 'User.email'])
                ->from(['Ws_task_producer' => WorksystemTaskProducer::tableName()])
                ->leftJoin(['Team_member' => TeamMember::tableName()], 'Team_member.id = Ws_task_producer.team_member_id')
                ->leftJoin(['User' => User::tableName()], 'User.id = Team_member.u_id')
                ->where(['Ws_task_producer.worksystem_task_id' => $taskId])
                ->all();
        
        $user = [
            'user_id' => ArrayHelper::map($producers, 'worksystem_task_id', 'u_id'),
            'nickname' => ArrayHelper::map($producers, 'worksystem_task_id', 'nickname'),
            'ee' => ArrayHelper::map($producers, 'worksystem_task_id', 'ee'),
            'email' => ArrayHelper::map($producers, 'worksystem_task_id', 'email'),
        ];
        
        return $user;
    }
    
    /**
     * 获取所有工作系统任务附加属性
     * @param integer $taskId               工作系统任务id
     * @return array
     */
    public function getWorksystemTaskAddAttributes($taskId)
    {
        $attributes = (new Query())
                ->select([
                    'Ws_add_attributes.worksystem_attributes_id AS id', 'Ws_add_attributes.value',
                    'Ws_attributes.name', 'Ws_attributes.type', 'Ws_attributes.input_type',
                    'Ws_attributes.value_list', 'Ws_attributes.index', 'Ws_attributes.is_delete'
                ])
                ->from(['Ws_add_attributes' => WorksystemAddAttributes::tableName()])
                ->leftJoin(['Ws_attributes' => WorksystemAttributes::tableName()], 'Ws_attributes.id = Ws_add_attributes.worksystem_attributes_id')
                ->where(['worksystem_task_id' => $taskId])
                ->all();
           
        return $attributes;
    }
    
    /**
     * 工作系统基础附加属性格式化
     * @param array $items                  格式化对象
     * @return array
     */
    public function WorksystemAttributesFormat($items)
    {
        $itemResults = [];          //格式化后的结果数据
        $valueList = [];            //格式话的候选值
        if(!empty($items) && is_array($items)){
            foreach ($items as $element) {
                if($element['value_list'] != null){
                    $valueLists = explode("\r\n", $element['value_list']);
                    foreach ($valueLists as $value) {
                        $valueList[$value] = $value;
                    }
                    $element['value_list'] = $valueList;
                }
                if(isset($element['value'])){
                    if(strpos($element['value'] ,",")){
                        $element['value'] = explode(",", $element['value']);
                    }
                }else{   
                    $element['value'] = null;
                }
                $itemResults[] = $element;
            }
        }
       
        return $itemResults;
    }
    
    /**
     * 获取是否属于自己的操作
     * @param array $taskId                          工作系统任务id
     * @param array $status                          状态
     * @return boolean                               true为是
     */ 
    public function getIsBelongToOwnOperate($taskId, $status)
    {
        $operation = [];
        $isBelong = [];
        $operates = (new Query())
                ->select(['id', 'worksystem_task_id', 'worksystem_task_status'])
                ->from(WorksystemOperation::tableName())
                ->where(['worksystem_task_id' => $taskId])
                ->all();
        
        if(!empty($operates)){
            foreach ($operates as $value) {
                if(isset($status[$value['worksystem_task_id']]))
                    $_status = $value['worksystem_task_status'] == $status[$value['worksystem_task_id']];
                else
                    $_status = false;
                $operation[$value['worksystem_task_id']] = [
                    'id' => $value['id'],
                    'status' => $_status ? true : false,
                ];
            }
            
            $operationUsers = (new Query())
                    ->select([
                        'Ws_operation_user.id', 'Ws_operation_user.user_id', 'Ws_operation_user.worksystem_operation_id',
                        'Ws_operation.worksystem_task_id'
                    ])
                    ->from(['Ws_operation_user' => WorksystemOperationUser::tableName()])
                    ->leftJoin(['Ws_operation' => WorksystemOperation::tableName()], 'Ws_operation.id = Ws_operation_user.worksystem_operation_id')
                    ->where(['worksystem_operation_id' => ArrayHelper::getColumn($operation, 'id')])
                    ->all();
            
            $operations = ArrayHelper::map($operation, 'id', 'status');
            $operationUser = ArrayHelper::map($operationUsers, 'id', 'user_id', 'worksystem_operation_id');
            $taskIds = ArrayHelper::map($operationUsers, 'worksystem_operation_id', 'worksystem_task_id');
            
            if(!empty($operationUser)){
                foreach ($operationUser as $index => $element){
                    if(in_array(Yii::$app->user->id, $element) && $operations[$index])
                        $isBelong[$taskIds[$index]] = true;
                    else
                        $isBelong[$taskIds[$index]] = false;
                }
            }
        }
        
        return $isBelong;
    }
    
    /**
     * 获取是否为团队指派人
     * @param integer $teamId                   团队ID
     * @return boolean                          true为是
     */
    public function getIsAssignPeople($teamId)
    {
        $assigns = (new Query())
                ->select(['user_id'])
                ->from(WorksystemAssignTeam::tableName())
                ->where(['team_id' => $teamId])
                ->one();
       
        if(in_array(Yii::$app->user->id, $assigns))
            return true;
        else
            return false;
    }
    
    /**
     * 获取是否为制作人
     * @param integer $taskId                       工作系统任务id
     * @return boolean                              true为是
     */
    public function getIsProducer($taskId)
    {
        $producers = (new Query())
                ->select(['Team_member.u_id'])
                ->from(['Ws_task_producer' => WorksystemTaskProducer::tableName()])
                ->leftJoin(['Team_member' => TeamMember::tableName()], 'Team_member.id = Ws_task_producer.team_member_id')
                ->where(['Ws_task_producer.worksystem_task_id' => $taskId])
                ->all();
        $results = ArrayHelper::getColumn($producers, 'u_id');
        
        if(in_array(\Yii::$app->user->id, $results))
            return true;
        else
            return false;
    }
        
    /**
     * 保存工作系统任务附加属性
     * @param WorksystemTask $model
     * @param type $post
     */
    public function saveWorksystemAddAttributes($model, $post)
    {
        $attributes = ArrayHelper::getValue($post, 'WorksystemAddAttributes');
        
        $values = [];
        foreach ($attributes['value'] as $index => $items){
            $values[] = [
                'worksystem_task_id' => $model->id,
                'worksystem_attributes_id' => $index,
                'value' => !is_array($items) ? $items : implode(",", $items),
                'index' => $attributes['index'][$index],
                'is_delete' => $attributes['is_delete'][$index],
                'created_at' => time(),
                'updated_at' => time(),
            ];
        }
        
        if($values != null){
            Yii::$app->db->createCommand()->delete(WorksystemAddAttributes::tableName(), ['worksystem_task_id' => $model->id])->execute();
            /** 添加$values数组到表里 */
            Yii::$app->db->createCommand()->batchInsert(WorksystemAddAttributes::tableName(),[
                'worksystem_task_id', 'worksystem_attributes_id', 'value',  'index', 'is_delete', 'created_at', 'updated_at'
            ], $values)->execute();
        }
    }
    
    /**
     * 保存工作系统任务内容信息
     * @param WorksystemTask $model
     * @param WorksystemQuery $_wsQuery
     * @param DemandTask $results
     * @param type $post
     */
    public function saveWorksystemContentinfo($model, $post)
    {
        $_wsQuery = WorksystemQuery::getInstance();
        $results = $_wsQuery->findDemandTaskTable($model->course_id);
        $contents = ArrayHelper::getValue($post, 'WorksystemContentinfo');
        $budgetCost = ArrayHelper::getValue($post, 'WorksystemTask.budget_cost');
        $budgetBonus = $budgetCost * $results->score;
        
        $values = [];
        foreach ($contents as $index => $items){
            $items += [
                'worksystem_task_id' => $model->id,
                'created_at' => time(),
                'updated_at' => time(),
            ];
            
            $values[] = $items;
        }
        
        if($values != null){
            \Yii::$app->db->createCommand()->update(WorksystemTask::tableName(), ['budget_bonus' => $budgetBonus], ['id' => $model->id])->execute();
            Yii::$app->db->createCommand()->delete(WorksystemContentinfo::tableName(), ['worksystem_task_id' => $model->id])->execute();
            /** 添加$values数组到表里 */
            Yii::$app->db->createCommand()->batchInsert(WorksystemContentinfo::tableName(),[
                'worksystem_content_id', 'is_new',  'price', 'budget_number', 'budget_cost', 'worksystem_task_id', 'created_at', 'updated_at'
            ], $values)->execute();
            
        }
    }
    
    /**
     * 修改工作系统任务内容信息
     * @param WorksystemTask $model
     * @param WorksystemQuery $_wsQuery
     * @param DemandTask $results
     * @param type $post
     */
    public function updateWorksystemContentinfo($model, $post)
    {
        $_wsQuery = WorksystemQuery::getInstance();
        $results = $_wsQuery->findDemandTaskTable($model->course_id);
        $contents = ArrayHelper::getValue($post, 'WorksystemContentinfo');
        $realityCost = ArrayHelper::getValue($post, 'WorksystemTask.reality_cost');
        $realityBonus = $realityCost * $results->score;
        
        \Yii::$app->db->createCommand()->update(WorksystemTask::tableName(), ['reality_bonus' => $realityBonus], ['id' => $model->id])->execute();
        
        foreach ($contents as $index => $items){
            \Yii::$app->db->createCommand()->update(WorksystemContentinfo::tableName(), [
                'reality_number' => $items['reality_number'], 'reality_cost' => $items['reality_cost']
            ], ['id' => $index])->execute();
        }
    }
    
    /**
     * 保存工作系统操作
     * @param integer $taskId                   工作系统任务id
     * @param integer $status                   状态
     * @param string $title                     标题
     * @param string $content                   内容
     * @param string $des                       描述
     */
    public function saveWorksystemOperation($taskId, $status, $title = null, $content = null, $des = null)
    {
        $values[] = [
            'worksystem_task_id' => $taskId,
            'worksystem_task_status' => $status,
            'controller_action' => Yii::$app->controller->id.'/'.Yii::$app->controller->action->id,
            'title' => $title,
            'content' => $content,
            'des' => $des == null ? '无' : $des,
            'create_by' => Yii::$app->user->id,
            'created_at' => time(),
            'updated_at' => time(),
        ];
        if($values != null)
            /** 添加$values数组到表里 */
            Yii::$app->db->createCommand()->batchInsert(WorksystemOperation::tableName(), [
                'worksystem_task_id', 'worksystem_task_status', 'controller_action',
                'title', 'content', 'des',
                'create_by', 'created_at', 'updated_at'], $values)->execute();
    }
    
    /**
     * 保存工作系统操作用户
     * @param integer $taskId                   工作系统任务id
     * @param array $userIds                    用户id
     * @param boolean $brace                    支撑标识：0表示不支撑，1表示支撑
     * @param boolean $epiboly                  外包标识：0表示不外包，1表示外包
     */
    public function saveWorksystemOperationUser($taskId, $userIds, $brace = null, $epiboly = null)
    {
        $operation = (new Query()) 
                ->from(WorksystemOperation::tableName())
                ->where(['worksystem_task_id' => $taskId])
                ->orderBy('id desc')
                ->one();
        
        $values = [];
        /** 重组提交的数据为$values数组 */
        $userIds = !is_array($userIds) ? [$userIds] : $userIds;
        foreach($userIds as $key => $value)
        {
            $values[] = [
                'worksystem_operation_id' => $operation['id'],
                'user_id' => $value,
                'brace_mark' => $brace == null ? WorksystemTask::CANCEL_BRACE_MARK : $brace,
                'epiboly_mark' => $epiboly == null ? WorksystemTask::CANCEL_EPIBOLY_MARK : $epiboly,
                'created_at' => time(),
                'updated_at' => time(),
            ];
        }
        
        if($values != null)
            /** 添加$values数组到表里 */
            Yii::$app->db->createCommand()->batchInsert(WorksystemOperationUser::tableName(), 
            ['worksystem_operation_id', 'user_id', 'brace_mark', 'epiboly_mark', 'created_at', 'updated_at'], $values)->execute();
    }
    
    /**
     * 保存工作系统任务制作人
     * @param integer $taskId                               工作系统任务id
     * @param array $producers                              制作人
     */
    public function saveWorksystemTaskProducer($taskId, $producers)
    {
        $producers = !is_array($producers) ? [$producers] : $producers;
        $values = [];
        /** 重组提交的数据为$values数组 */
        foreach($producers as $key => $value)
        {
            $values[] = [
                'worksystem_task_id' => $taskId,
                'team_member_id' => $value,
                'created_at' => time(),
                'updated_at' => time(),
            ];
        }
        
        if($values != null){
            Yii::$app->db->createCommand()->delete(WorksystemTaskProducer::tableName(), ['worksystem_task_id' => $taskId])->execute();
            /** 添加$values数组到表里 */
            Yii::$app->db->createCommand()->batchInsert(WorksystemTaskProducer::tableName(), 
            ['worksystem_task_id', 'team_member_id', 'created_at', 'updated_at'], $values)->execute();
        }
    }

    

    /**
     * 获取单例
     * @return WorksystemTool
     */
    public static function getInstance() 
    {
        if (self::$instance == null) {
            self::$instance = new WorksystemTool();
        }
        return self::$instance;
    }
}
