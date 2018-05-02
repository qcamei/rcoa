<?php

namespace frontend\modules\teamwork\utils;

use common\models\demand\DemandTask;
use common\models\team\Team;
use common\models\teamwork\CourseManage;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * TeamworSearch represents the model behind the search form about `common\models\teamwork\Teamwork`.
 */
class TeamworSearch
{
    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $nowTeam = TeamworkTool::getInstance()->getHotelTeam();
        $nowTeam = is_array($nowTeam) ? array_keys($nowTeam) : $nowTeam;
        $mark = ArrayHelper::getValue($params, 'mark');                             //标记
        $demand_task_id = ArrayHelper::getValue($params, 'demand_task_id');         //需求任务id
        $status = ArrayHelper::getValue($params, 'status' , CourseManage::STATUS_NORMAL);                         //状态
        $teamId = ArrayHelper::getValue($params, 'team_id', $nowTeam);              //团队id
        $itemTypeId = ArrayHelper::getValue($params, 'item_type_id');               //行业
        $itemId = ArrayHelper::getValue($params, 'item_id');                        //层次/类型
        $itemChildId = ArrayHelper::getValue($params, 'item_child_id');             //专业/工种
        $courseId = ArrayHelper::getValue($params, 'course_id');                    //课程
        $keyword = ArrayHelper::getValue($params, 'keyword');                       //关键字
        $time = ArrayHelper::getValue($params, 'time');                             //时间段
        $page = ArrayHelper::getValue($params, 'page');                             //时间段
        //查询开发课程数据
        $query = CourseManage::find()->select(['Tw_course.id'])->from(['Tw_course' => CourseManage::tableName()]);
        //关联需求任务
        $query->leftJoin(['Demand_task' => DemandTask::tableName()], 'Demand_task.id = Tw_course.demand_task_id');
        //关联团队
        $query->leftJoin(['Team' => Team::tableName()], 'Team.id = Tw_course.team_id');
        //关联需求任务的行业
        $query->leftJoin(['Fw_item_type' => ItemType::tableName()], 'Fw_item_type.id = Demand_task.item_type_id');
        //关联需求任务的层次/类型
        $query->leftJoin(['Fw_item' => Item::tableName()], 'Fw_item.id = Demand_task.item_id');
        //关联需求任务的专业/工种
        $query->leftJoin(['Fw_item_child' => Item::tableName()], 'Fw_item_child.id = Demand_task.item_child_id');
        //关联需求任务的课程
        $query->leftJoin(['Fw_item_course' => Item::tableName()], 'Fw_item_course.id = Demand_task.course_id');
        
        //按条件搜索    
        $query->andFilterWhere([
            'Demand_task.item_type_id' => $itemTypeId,
            'Demand_task.item_id' => $itemId,
            'Demand_task.item_child_id' => $itemChildId,
            'Demand_task.course_id' => $courseId,
            'Tw_course.demand_task_id' => $demand_task_id,
            'Tw_course.`status`'=> $status,
            'Tw_course.team_id'=> $teamId,
        ]);
        //根据时段搜索
        if($time != null){
            $time = explode(" - ",$time);
            if($status == CourseManage::STATUS_WAIT_START){
                $query->andFilterWhere(['<=','Tw_course.created_at', strtotime($time[1])]);
            }
            else if($status == CourseManage::STATUS_NORMAL){
                $query->andFilterWhere(['<=','Tw_course.real_start_time',$time[1]]);
            }
            else if($status == CourseManage::STATUS_CARRY_OUT){
                $query->andFilterWhere(['between','Tw_course.real_carry_out',$time[0],$time[1]]);
            }
            else{
                $query->andFilterWhere([
                    'or', ['and',"Tw_course.status=".CourseManage::STATUS_WAIT_START,  ['<=','Tw_course.created_at', strtotime($time[1])]], 
                    ['and',"Tw_course.status=".CourseManage::STATUS_NORMAL, ['<=','Tw_course.real_start_time', $time[1]]],
                    ['and',"Tw_course.status=".CourseManage::STATUS_CARRY_OUT,   ['between','Tw_course.real_carry_out',$time[0],$time[1]]]
                ]);
            }
        }
        //关键字搜索
        $query->andFilterWhere(['or',
            ['like', 'Fw_item_type.name', $keyword],
            ['like', 'Fw_item.name', $keyword],
            ['like', 'Fw_item_child.name', $keyword],
            ['like', 'Fw_item_course.name', $keyword],
            ['like', 'Team.name', $keyword]
        ]);
        
        //复制对象计算总数
        $pageCopy = clone $query;     
        $totalCount = count(array_flip (ArrayHelper::getColumn($pageCopy->all(), 'id')));
        //字段
        $query->addSelect([
            'Tw_course.demand_task_id', 'Demand_task.course_id', 
            'Tw_course.status', 'Tw_course.team_id', 'Demand_task.mode',
            'Demand_task.item_type_id', 'Demand_task.item_id', 'Demand_task.item_child_id',
            'Team.`name` AS team_name', 'Fw_item_type.`name` AS item_type_name',
            'Fw_item.`name` AS item_name','Fw_item_child.`name` AS item_child_name',
            'Fw_item_course.`name` AS item_course_name'
        ]);
        
        //分组
        $query->groupBy(['Tw_course.id']);
        //查询结果
        $results = $query->limit(20)->offset(($page-1) * 20)->all();
        
        return [
            'param' => $params,
            'totalCount' => $totalCount,
            'result' => $query->all(),
        ];
    }
}
