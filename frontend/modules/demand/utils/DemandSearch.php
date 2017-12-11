<?php
namespace frontend\modules\demand\utils;

use common\models\demand\DemandOperation;
use common\models\demand\DemandOperationUser;
use common\models\demand\DemandTask;
use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\User;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * DemandTaskSearch represents the model behind the search form about `common\models\worksystem\DemandTask`.
 */
class DemandSearch
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
        $undertaker = ArrayHelper::getValue($params, 'undertaker', !$mark ? Yii::$app->user->id : null);        //承接人
        $auditor = ArrayHelper::getValue($params, 'auditor', !$mark ? Yii::$app->user->id : null);              //审核人
        $status = ArrayHelper::getValue($params, 'status', !$mark ? DemandTask::STATUS_DEFAULT : null);         //状态
        $itemTypeId = ArrayHelper::getValue($params, 'item_type_id');                                           //行业
        $itemId = ArrayHelper::getValue($params, 'item_id');                                                    //层次/类型
        $itemChildId = ArrayHelper::getValue($params, 'item_child_id');                                         //专业/工种
        $courseId = ArrayHelper::getValue($params, 'course_id');                                                //课程
        $developTeam = ArrayHelper::getValue($params, 'develop_Team');                                          //开发团队
        $time = ArrayHelper::getValue($params, 'time');                                                         //时间段
        $keywords = ArrayHelper::getValue($params, 'keyword');                                                  //关键字
        //查询DemandTask
        $query = (new Query())
            ->select(['DemandTask.id'])
            ->from(['DemandTask' => DemandTask::tableName()]);
        $queryCopy = clone $query;        //复制对象
        //关联查询创建者
        $query->leftJoin(['CreateBy' => User::tableName()], 'CreateBy.id = DemandTask.create_by');
        //关联查询承接人
        $query->leftJoin(['Undertaker' => TeamMember::tableName()], 
            "(IF(DemandTask.status>=".DemandTask::STATUS_UNDERTAKE.
            ",Undertaker.team_id=DemandTask.team_id AND Undertaker.is_leader='".TeamMember::TEAMLEADER.
            "' AND Undertaker.is_delete ='".TeamMember::CANCEL_DELETE."',NULL))"
        )
            ->leftJoin(['UndertakerUser' => User::tableName()], 'UndertakerUser.id = Undertaker.u_id');
        //关联查询审核人
        $query->leftJoin(['Auditor' => TeamMember::tableName()], 
            "(IF(DemandTask.status>=".DemandTask::STATUS_CHECK." AND DemandTask.status<=".DemandTask::STATUS_CHECKING.",Auditor.team_id = DemandTask.create_team AND Auditor.is_leader='".TeamMember::TEAMLEADER ."',NULL))"
        );
        //关联查询团队
        $query->leftJoin(['DevelopTeam' => Team::tableName()], 'DevelopTeam.id = DemandTask.team_id');   
        //关联查询基础课程
        $query ->leftJoin(['ItemType' => ItemType::tableName()], 'ItemType.id = DemandTask.item_type_id')
            ->leftJoin(['Item' => Item::tableName()], 'Item.id = DemandTask.item_id')
            ->leftJoin(['ItemChild' => Item::tableName()], 'ItemChild.id = DemandTask.item_child_id')
            ->leftJoin(['ItemCourse' => Item::tableName()], 'ItemCourse.id = DemandTask.course_id');
        //查询条件
        if($mark){
            $query->andFilterWhere(['DemandTask.create_by' => $createBy]);
            $query->andFilterWhere(['DemandTask.undertake_person' => $undertaker]);
        }else{
            $query->orFilterWhere(['DemandTask.create_by' => $createBy]);
            $query->orFilterWhere(['DemandTask.undertake_person' => $undertaker]);
            $query->orFilterWhere(['Auditor.u_id' => $auditor]);
            /* @var $rbacManager RbacManager */
            $rbacManager = Yii::$app->authManager;
            if($rbacManager->isRole(RbacName::ROLE_COMMON_COURSE_DEV_MANAGER, Yii::$app->user->id))
                $query->orFilterWhere(['or', "IF(DemandTask.status=".DemandTask::STATUS_UNDERTAKE.",DemandTask.undertake_person IS NULL,NULL)"]);
            
        }
        //按状态搜索
        $query->andFilterWhere(['DemandTask.status' => $status == DemandTask::STATUS_DEFAULT ? DemandTask::$defaultStatus : $status]);
        //按字段id搜索
        $query->andFilterWhere([
            'DemandTask.item_type_id' => $itemTypeId,
            'DemandTask.item_id' => $itemId,
            'DemandTask.item_child_id' => $itemChildId,
            'DemandTask.course_id' => $courseId,
            'DemandTask.team_id' => $developTeam,
        ]);
        //按时间段搜索
        if($time != null){
            $time = explode(" - ",$time);
            if(is_array($status))
                $results->andFilterWhere(['<=', 'DemandTask.plan_check_harvest_time', $time[1]]);
            else if($status == DemandTask::STATUS_COMPLETED)
                $results->andFilterWhere(['between', 'DemandTask.reality_check_harvest_time', $time[0],$time[1]]);
            else if($status == DemandTask::STATUS_CANCEL)
                $results->andFilterWhere(['between', 'DemandTask.plan_check_harvest_time', $time[0],$time[1]]);
            else
                $results->andFilterWhere(['or', 
                    ['between', 'DemandTask.plan_check_harvest_time', $time[0],$time[1]], 
                    ['between', 'DemandTask.reality_check_harvest_time', $time[0],$time[1]]
                ]);
        }
        //按关键字模糊搜索
        $query->andFilterWhere(['or',
            ['like', 'ItemType.name', $keywords],
            ['like', 'Item.name', $keywords],
            ['like', 'ItemChild.name', $keywords],
            ['like', 'ItemCourse.name', $keywords]
        ]);
        //复制对象计算总数
        $pageCopy = clone $query;     
        $totalCount = count(array_flip(ArrayHelper::getColumn($pageCopy->all(), 'id')));
        //字段
        $query->addSelect([
            'ItemType.name AS item_type_name', 'Item.name AS item_name', 'ItemChild.name AS item_child_name', 'ItemCourse.name AS item_course_name',
            'DemandTask.mode', 'DemandTask.budget_cost', 'DemandTask.external_budget_cost', 'DemandTask.bonus_proportion', 'DemandTask.cost', 'DemandTask.external_reality_cost',
            'DemandTask.plan_check_harvest_time',  'DevelopTeam.name AS develop_team_name', 
            'DemandTask.status', 'DemandTask.progress', 'CreateBy.nickname AS create_by','UndertakerUser.nickname AS undertaker'
        ]);
        //分组、排序、截取
        $query->groupBy(['DemandTask.id'])
            ->orderBy([new Expression("FIELD(DemandTask.`status`,".implode(',',DemandTask::$orderBy).")"), 'DemandTask.id' => SORT_DESC]);
        //查询结果
        $results = $query->limit(20)->offset(($page-1) * 20)->all();
        //查找是否属于自己的操作
        $queryCopy->addSelect(['DemandTask.`status`']);
        $belongResults = (new Query())
            ->select(['Operation.task_id AS id', 
                "IF(DemandTaskCopy.`status`=".DemandTask::STATUS_CANCEL.",".DemandTask::STATUS_CANCEL.",IF(DemandTaskCopy.`status`=".DemandTask::STATUS_COMPLETED.",".DemandTask::STATUS_COMPLETED.",IF(DemandTaskCopy.`status`=".DemandTask::STATUS_DEVELOPING.",".DemandTask::STATUS_DEVELOPING.",IF(DemandTaskCopy.`status`=Operation.task_status, 1, 0)))) AS `status`",
                'OperationUser.u_id AS user_id'
            ])
            ->from(['DemandTaskCopy' => $queryCopy])
            ->leftJoin(['Operation' => DemandOperation::tableName()], '(Operation.task_id = DemandTaskCopy.id AND Operation.task_status = DemandTaskCopy.`status`)')
            ->leftJoin(['OperationUser' => DemandOperationUser::tableName()], 'OperationUser.operation_id = Operation.id')
            ->groupBy(['CONCAT(OperationUser.operation_id, \'_\', OperationUser.u_id)'])
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
