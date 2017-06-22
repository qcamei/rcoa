<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandTask;
use common\models\team\Team;
use common\models\team\TeamCategory;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class StatisticsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            //access验证是否有登录
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        /* @var $request Request */
        $request = Yii::$app->getRequest();
        /** 行业 */
        $item_type_id = $request->getQueryParam('item_type_id');
        /** 层次/类型 */
        $item_id = $request->getQueryParam('item_id');
        /** 专业/工种 */
        $item_child_id = $request->getQueryParam('item_child_id');
        /** 状态 */
        $status = $request->getQueryParam('status');
        /** 团队 */
        $team = $request->getQueryParam('team');
        /** 统计类型 */
        $type = $request->getQueryParam('type');
        
        /* @var $query Query */
        $query = (new Query())
                ->andFilterWhere(['DemandTask.status'=>$status])
                ->andFilterWhere(['DemandTask.`create_team`'=>$team])
                ->andFilterWhere(['DemandTask.`item_type_id`'=>$item_type_id])
                ->andFilterWhere(['DemandTask.`item_id`'=>$item_id])
                ->andFilterWhere(['DemandTask.`item_child_id`'=>$item_child_id]);
        /* 当时间段参数不为空时 */
        if($dateRange = $request->getQueryParam('dateRange')){
            $dateRange_Arr = explode(" - ",$dateRange);
            //下面所有例子设置时间段为 2016-08-01 到 2016-08-31
            if($status <= DemandTask::STATUS_UNDERTAKE)
            {
                /*
                 * 状态=待开始 AND created_at(创建时间)<=指定时间段最大值
                 * 如：统计 到 2016-08-31 号还没有【开始】的课程
                 * 注：【去年建】的课程到 2016-08-31 还【未开始】也会统计在内。
                 */
                $query->andFilterWhere(['<=','DemandTask.created_at',strtotime($dateRange_Arr[1])]);
            }else if($status < DemandTask::STATUS_COMPLETED)
            {
                /*
                 * 状态=在建中 AND real_start_time(实际开始时间)<=指定时间段最大值
                 * 如：统计 到 2016-08-31 号还在【建设中】的课程
                 * 注：【去年开始】的课程到 2016-08-31 还【未完成】也会统计在内。
                 */
                //状态=在建中 AND created_at(实际开始时间)<=指定时间最大值，如：统计【指定最大值时间】还在【建设中】的课程
                $query->andFilterWhere(['<=','DemandTask.created_at',strtotime($dateRange_Arr[1])]);
            }else if($status == DemandTask::STATUS_COMPLETED)
            {
                /*
                 * 状态 = 已完成 AND 指定时间最小值 < reality_check_harvest_time(实际完成时间)<指定时间段最大值
                 * 如：统计 2016-08-01 到 2016-08-31 内完成的课程
                 */
                $query->andFilterWhere(['between','DemandTask.reality_check_harvest_time',$dateRange_Arr[0],$dateRange_Arr[1]]);
            }else{
                /**
                 * 状态为空时，每个条件都加上对应状态
                 * 条件为或者关系，只要满足其中一条规则即可统计在内
                 */
                $query->orFilterWhere(['and',"DemandTask.status<=".DemandTask::STATUS_UNDERTAKE,  ['<=','DemandTask.created_at',strtotime($dateRange_Arr[1])]]);
                $query->orFilterWhere(['and',"DemandTask.status<".DemandTask::STATUS_COMPLETED,      ['<=','DemandTask.created_at',strtotime($dateRange_Arr[1])]]);
                $query->orFilterWhere(['and',"DemandTask.status=".DemandTask::STATUS_COMPLETED,   ['between','DemandTask.reality_check_harvest_time',$dateRange_Arr[0],$dateRange_Arr[1]]]);
            }
        }
        
        $teams = $this->getStatisticsByTeam($query,$type);//按团队统计
        $allValues = array_sum(ArrayHelper::getColumn($teams, 'value'));//总学时 
        $allCourse = array_sum(ArrayHelper::getColumn($teams, 'total'));//总课程
       
        return $this->render('index',[
            'dateRange'     => $dateRange,
            'item_type_id'  => $item_type_id,
            'item_id'       => $item_id,
            'item_child_id' => $item_child_id,
            'team'          => $team,
            'status'        => $status,
            'allValues'     => $allValues,
            'allCourse'     => $allCourse,
            'type'          => $type,
            
            'itemTypes'     => $this->getStatisticsByItemType($query,$type),//按行业统计
            'items'         => $this->getStatisticsByItem($query,$type),//按项目统计
            'itemChilds'    => $this->getStatisticsByItemChild($query,$type),//按子项目统计
            'teams'         => $teams,
            'item_type_ids' => $this->getItemTyps(),
            'item_ids'      => $this->getItems(),
            'teamIds'       => $this->getTeamIds(),
        ]);
    }
    
    //--------------------------------------------------------------------------
    //
    // utils
    //
    //--------------------------------------------------------------------------
    /**
     * 获取 行业
     * @return Array [id=>name]
     */
    private function getItemTyps(){
        $itemTyps = ItemType::find()->all();
        return ArrayHelper::map($itemTyps, 'id', 'name');
    }
    /**
     * 获取 层次/类型
     * @return Array [id=>name]
     */
    private function getItems(){
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        $items = $fwManager->getColleges();
        return ArrayHelper::map($items, 'id', 'name');
    }
    
    /**
     * 获取团队ids
     * @return Array [id=>name]
     */
    private function getTeamIds(){
        $teamIds = TeamMemberTool::getInstance()->getTeamsByCategoryId(TeamCategory::TYPE_PRODUCT_CENTER);
        $teamIds = ArrayHelper::map($teamIds, 'id', 'name');
        return $teamIds;
    }
    /**
     * 按行业统计
     * @param Query $sourceQuery
     * @param int $type       类型：0学时，1花费
     * @return Array
     */
    private function getStatisticsByItemType($sourceQuery,$type){
        $selectName = $type == 1 ? 'SUM(COALESCE(DemandTask.cost,0) + COALESCE(DemandTask.cost,0) * DemandTask.bonus_proportion)' : 'SUM(DemandTask.lesson_time)';
        $itemTypQuery = clone $sourceQuery;
        $itemTypQuery->select(['ItemType.name',"{$selectName} AS value"])
                    ->from(['DemandTask'=>DemandTask::tableName()])
                    ->leftJoin(['ItemType'=>  ItemType::tableName()],'DemandTask.item_type_id = ItemType.id')
                    ->groupBy('DemandTask.item_type_id');
        return $itemTypQuery->all(Yii::$app->db);
    }
    /**
     * 按项目统计
     * @param Query $sourceQuery
     * @param strin $name       要查找的字段名
     * @param int $type       类型：0学时，1花费
     * @return Array
     */
    private function getStatisticsByItem($sourceQuery,$type){
        $selectName = $type == 1 ? 'SUM(COALESCE(DemandTask.cost,0) + COALESCE(DemandTask.cost,0) * DemandTask.bonus_proportion)' : 'SUM(DemandTask.lesson_time)';
        $itemQuery = clone $sourceQuery;
        $itemQuery->select(['FwItem.name',"{$selectName} AS value"])
                ->from(['DemandTask'=>DemandTask::tableName()])
                ->leftJoin(['FwItem'=> Item::tableName()],'DemandTask.item_id = FwItem.id')
                ->groupBy('DemandTask.item_id');
        return $itemQuery->all(Yii::$app->db);
    }
     /**
     * 按子项目统计
     * @param Query $sourceQuery
     * @param int $type       类型：0学时，1花费
     * @return Array
     */
    private function getStatisticsByItemChild($sourceQuery,$type){
        $selectName = $type == 1 ? 'SUM(COALESCE(DemandTask.cost,0) + COALESCE(DemandTask.cost,0) * DemandTask.bonus_proportion)' : 'SUM(DemandTask.lesson_time)';
        $itemChildQuery = clone $sourceQuery;
        $itemChildQuery->select(['FwItem.name',"{$selectName} AS value"])
                    ->from(['DemandTask'=>DemandTask::tableName()])
                    ->leftJoin(['FwItem'=> Item::tableName()],'DemandTask.item_child_id = FwItem.id')
                    ->groupBy('DemandTask.item_child_id');
        return $itemChildQuery->all(Yii::$app->db);
    }
    
    /**
     * 按团队统计
     * @param Query $sourceQuery
     * @param int $type       类型：0学时，1花费
     * @return Array
     */
    private function getStatisticsByTeam($sourceQuery,$type){
        $selectName = $type == 1 ? 'SUM(COALESCE(DemandTask.cost,0) + COALESCE(DemandTask.cost,0) * DemandTask.bonus_proportion)' : 'SUM(DemandTask.lesson_time)';
        $teamQuery = clone $sourceQuery;
        $teamQuery->select(['Team.name',"{$selectName} AS value",'Count(distinct DemandTask.id) AS total'])
                ->from(['Team'=> Team::tableName()])
                ->leftJoin(['DemandTask'=>DemandTask::tableName()],'DemandTask.create_team = Team.id')
                ->andWhere(['Team.id'=>  ArrayHelper::getColumn(TeamMemberTool::getInstance()->getTeamsByCategoryId(TeamCategory::TYPE_PRODUCT_CENTER), 'id')])
                ->groupBy('Team.id')
                ->orderBy('Team.index DESC');
        return $teamQuery->all(Yii::$app->db);
    }

}
