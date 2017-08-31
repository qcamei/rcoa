<?php

namespace frontend\modules\worksystem\utils;

use common\models\team\TeamMember;
use common\models\worksystem\WorksystemAddAttributes;
use common\models\worksystem\WorksystemAttributes;
use common\models\worksystem\WorksystemTaskProducer;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;



class WorksystemTool 
{
    private static $instance = null;
    
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
     * 获取是否为团队指派人
     * @param integer $teamId                   团队ID
     * @return boolean                          true为是
     */
    public function getIsHaveAssign($teamId)
    {
        $teamMembers = [];
        $_tmTool = TeamMemberTool::getInstance();
        $teamMembers = $_tmTool->getTeamMembersByTeamId($teamId);
        $userIds = ArrayHelper::getColumn($teamMembers, 'u_id');
        if(in_array(Yii::$app->user->id, $userIds))
            return true;
        return false;
    }
    
    /**
     * 获取是否为制作人
     * @param integer $taskId                       工作系统任务id
     * @return boolean                              true为是
     */
    public function getIsHaveMake($taskId)
    {
        $producers = (new Query())
                ->select(['Team_member.u_id'])
                ->from(['Producer' => WorksystemTaskProducer::tableName()])
                ->leftJoin(['Team_member' => TeamMember::tableName()], 'Team_member.id = Producer.team_member_id')
                ->where(['Producer.worksystem_task_id' => $taskId])
                ->all();
        $results = ArrayHelper::getColumn($producers, 'u_id');
        if(in_array(\Yii::$app->user->id, $results))
            return true;
        
        return false;
    }
}
