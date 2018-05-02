<?php

namespace frontend\modules\worksystem\utils;

use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\User;
use common\models\worksystem\WorksystemOperation;
use common\models\worksystem\WorksystemOperationUser;
use common\models\worksystem\WorksystemTask;
use common\models\worksystem\WorksystemTaskProducer;
use common\models\worksystem\WorksystemTaskType;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * WorksystemTaskSearch represents the model behind the search form about `common\models\worksystem\WorksystemTask`.
 */
class WorksystemSearch
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
        $mark = ArrayHelper::getValue($params, 'mark', 0);                                                      //标记
        $page = ArrayHelper::getValue($params, 'page', 1);                                                      //分页
        $createBy = ArrayHelper::getValue($params, 'create_by', !$mark ? Yii::$app->user->id : null);           //创建者
        $producer = ArrayHelper::getValue($params, 'producer', !$mark ? Yii::$app->user->id : null);            //制作人
        $assignPeople = ArrayHelper::getValue($params, 'assign_people', !$mark ? Yii::$app->user->id : null);   //指派人
        $status = ArrayHelper::getValue($params, 'status', !$mark ? WorksystemTask::STATUS_DEFAULT : null);     //状态
        $itemTypeId = ArrayHelper::getValue($params, 'item_type_id');                                           //行业
        $itemId = ArrayHelper::getValue($params, 'item_id');                                                    //层次/类型
        $itemChildId = ArrayHelper::getValue($params, 'item_child_id');                                         //专业/工种
        $courseId = ArrayHelper::getValue($params, 'course_id');                                                //课程
        $taskTypeId = ArrayHelper::getValue($params, 'task_type_id');                                           //任务类型
        $createTeam = ArrayHelper::getValue($params, 'create_team');                                            //创建团队
        $externalTeam = ArrayHelper::getValue($params, 'external_team');                                        //外部团队
        $time = ArrayHelper::getValue($params, 'time');                                                         //时间段
        $keywords = ArrayHelper::getValue($params, 'keyword');                                                  //关键字
        
        //查询WorksystemTask
        $query = (new Query())
            ->select(['WorksystemTask.id'])
            ->from(['WorksystemTask' => WorksystemTask::tableName()]);
        $queryCopy = clone $query;        //复制对象
        //关联查询创建者
        $query->leftJoin(['CreateBy' => User::tableName()], 'CreateBy.id = WorksystemTask.create_by');
        //关联查询制作人
        $query->leftJoin(['Producer' => WorksystemTaskProducer::tableName()], 'Producer.worksystem_task_id = WorksystemTask.id')
              ->leftJoin(['TeamMember' => TeamMember::tableName()], 'TeamMember.id = Producer.team_member_id')
              ->leftJoin(['ProducerUser' => User::tableName()], 'ProducerUser.id = TeamMember. u_id');
        //关联查询指派人
        $query->leftJoin(['AssignPeople' => TeamMember::tableName()], 
            "(IF(WorksystemTask.is_brace=".WorksystemTask::SEEK_BRACE_MARK.
            " AND WorksystemTask.external_team=0, AssignPeople.is_leader='".TeamMember::TEAMLEADER."' AND AssignPeople.is_delete ='".TeamMember::CANCEL_DELETE."',".
            "IF(WorksystemTask.`status`>=".WorksystemTask::STATUS_WAITCHECK.
            " AND WorksystemTask.`status`<=".WorksystemTask::STATUS_WAITUNDERTAKE.",AssignPeople.team_id=WorksystemTask.create_team".
            " AND AssignPeople.is_leader='".TeamMember::TEAMLEADER."' AND AssignPeople.is_delete ='".TeamMember::CANCEL_DELETE."',NUll)))"
        );
        //关联查询团队
        $query->leftJoin(['ExternalTeam' => Team::tableName()], 'ExternalTeam.id = WorksystemTask.external_team')      
              ->leftJoin(['CreateTeam' => Team::tableName()], 'CreateTeam.id = WorksystemTask.create_team');   
        //关联查询基础课程
        $query ->leftJoin(['ItemType' => ItemType::tableName()], 'ItemType.id = WorksystemTask.item_type_id')
            ->leftJoin(['Item' => Item::tableName()], 'Item.id = WorksystemTask.item_id')
            ->leftJoin(['ItemChild' => Item::tableName()], 'ItemChild.id = WorksystemTask.item_child_id')
            ->leftJoin(['ItemCourse' => Item::tableName()], 'ItemCourse.id = WorksystemTask.course_id');
        //关联查询任务类型
        $query->leftJoin(['TaskType' => WorksystemTaskType::tableName()], 'TaskType.id = WorksystemTask.task_type_id');
        //查询条件
        if($mark){
            $query->andFilterWhere(['WorksystemTask.create_by' => $createBy]);
            $query->andFilterWhere(['TeamMember.u_id' => $producer]);
            $query->andFilterWhere(['WorksystemTask.create_team' => $createTeam]);
            $query->andFilterWhere(['WorksystemTask.external_team' => $externalTeam]);
        }else{
            $query->orFilterWhere(['WorksystemTask.create_by' => $createBy]);
            $query->orFilterWhere(['TeamMember.u_id' => $producer]);
            $query->orFilterWhere(['AssignPeople.u_id' => $assignPeople]);
            /* @var $rbacManager RbacManager */
            $rbacManager = Yii::$app->authManager;
            if($rbacManager->isRole(RbacName::ROLE_COMMON_EXTERNAL_WORKER, Yii::$app->user->id))
                $query->orFilterWhere(['WorksystemTask.is_epiboly' => WorksystemTask::SEEK_EPIBOLY_MARK]);
            //获取和自己相关的团队id
            $myTeams = TeamMemberTool::getInstance()->getUserTeamMembers(Yii::$app->user->id);
            //获取所属团队id
            $isLeader = false;
            $teamIds = [];
            foreach ($myTeams as  $member) {
                if($member['is_leader'] == TeamMember::TEAMLEADER) {
                    $isLeader = true;
                    $teamIds[] = $member['team_id'];
                }
            }
            //在默认搜索情况下如果是队长才加团队条件
            if($isLeader){
               $query->orFilterWhere(['WorksystemTask.create_team' => $teamIds]);
               $query->orFilterWhere(['WorksystemTask.external_team' => $teamIds]);
            }
        }
        //按状态搜索
        $query->andFilterWhere(['WorksystemTask.status' => $status == WorksystemTask::STATUS_DEFAULT ? WorksystemTask::$defaultStatus : $status]);
        //按字段id搜索
        $query->andFilterWhere([
            'WorksystemTask.item_type_id' => $itemTypeId,
            'WorksystemTask.item_id' => $itemId,
            'WorksystemTask.item_child_id' => $itemChildId,
            'WorksystemTask.course_id' => $courseId,
            'WorksystemTask.task_type_id' => $taskTypeId,
        ]);
        //按时间段搜索
        if($time != null){
            $times = explode(" - ", $time);
            if(is_array($status))
                $query->andFilterWhere(['<=', 'WorksystemTask.created_at', strtotime($times[1])]);
            else if($status == WorksystemTask::STATUS_COMPLETED)
                $query->andFilterWhere(['between', 'WorksystemTask.plan_end_time', $times[0], $times[1]]);
            else if($status == WorksystemTask::STATUS_CANCEL)
                $query->andFilterWhere(['between', 'WorksystemTask.created_at', strtotime($times[0]), strtotime($times[1])]);
            else
                $query->andFilterWhere(['or', 
                    ['between', 'WorksystemTask.created_at', strtotime($times[0]), strtotime($times[1])], 
                    ['between', 'WorksystemTask.plan_end_time', $times[0], $times[1]]
                ]);
        }
        //按关键字模糊搜索
        $query->andFilterWhere(['or',
            ['like', 'WorksystemTask.name', $keywords],
            ['like', 'ItemType.name', $keywords],
            ['like', 'Item.name', $keywords],
            ['like', 'ItemChild.name', $keywords],
            ['like', 'ItemCourse.name', $keywords]
        ]);
        //复制对象计算总数
        $pageCopy = clone $query;     
        $totalCount = count(array_flip (ArrayHelper::getColumn($pageCopy->all(), 'id')));
        //字段
        $query->addSelect([
            'Item.name AS item_name', 'ItemChild.name AS item_child_name', 'ItemCourse.name AS item_course_name',
            'TaskType.name AS task_type_name', 'WorksystemTask.name', 
            'WorksystemTask.level', 'WorksystemTask.is_brace', 'WorksystemTask.is_epiboly', 'WorksystemTask.plan_end_time', 
            'ExternalTeam.name AS external_team_name', 'CreateTeam.name AS create_team_name', 'WorksystemTask.status', 'WorksystemTask.progress',
            'CreateBy.nickname AS create_by','GROUP_CONCAT(DISTINCT ProducerUser.nickname SEPARATOR \',\') as producer_nickname'
        ]);
        //分组、排序、截取
        $query->groupBy(['WorksystemTask.id'])
            ->orderBy(['WorksystemTask.level' => SORT_DESC, 'WorksystemTask.id' => SORT_DESC]);
        //查询结果
        $results = $query->limit(20)->offset(($page-1) * 20)->all();
        //查找是否属于自己的操作
        $queryCopy->addSelect(['WorksystemTask.`status`']);
        $belongResults = (new Query())
            ->select(['Operation.worksystem_task_id AS id', 
                "IF(WorksystemTaskCopy.`status`=".WorksystemTask::STATUS_CANCEL.",".WorksystemTask::STATUS_CANCEL.",IF(WorksystemTaskCopy.`status`=".WorksystemTask::STATUS_COMPLETED.",".WorksystemTask::STATUS_COMPLETED.",IF(WorksystemTaskCopy.`status`=Operation.worksystem_task_status, 1, 0))) AS `status`",
                'OperationUser.user_id'
            ])
            ->from(['WorksystemTaskCopy' => $queryCopy])
            ->leftJoin(['Operation' => WorksystemOperation::tableName()], '(Operation.worksystem_task_id = WorksystemTaskCopy.id AND Operation.worksystem_task_status = WorksystemTaskCopy.`status`)')
            ->leftJoin(['OperationUser' => WorksystemOperationUser::tableName()], 'OperationUser.worksystem_operation_id = Operation.id')
            ->groupBy(['CONCAT(OperationUser.worksystem_operation_id, \'_\', OperationUser.user_id)'])
            ->all();
        $isBelong = [];
        foreach ($belongResults as $item){
            if($item['user_id'] != null)
                $isBelong[$item['user_id']][$item['id']] = $item['status'];
        }
        
        return [
            'param' => $params,
            'totalCount' => $totalCount,
            'result' => $results,
            'isBelong' => $isBelong,
        ];
    }
}
