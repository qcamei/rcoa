<?php

namespace frontend\modules\teamwork\controllers;

use common\models\team\Team;
use common\models\teamwork\CourseManage;
use common\models\teamwork\ItemManage;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class StatisticsController extends Controller
{
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
        
        /* @var $query Query */
        $query = (new Query())
                ->andFilterWhere(['Course.status'=>($status == 0 || $status == 100) ? null : $status])
                ->andFilterWhere(['Course.`team_id`'=>$team])
                ->andFilterWhere(['Item.`item_type_id`'=>$item_type_id])
                ->andFilterWhere(['Item.`item_id`'=>$item_id])
                ->andFilterWhere(['Item.`item_child_id`'=>$item_child_id]);
                
        /** 
         * 状态为【完成】，查找【完成时间】在【划定的时间】内
         * 状态为【在线】，查找【计划开始时间】在【划定时间】内
         * 如果状态为空，查找【完成时间】和【计划开始时间】都在【划定时间】内
         **/
        if($dateRange = $request->getQueryParam('dateRange')){
            $dateRange_Arr = explode(" - ",$dateRange);
            if($status == 100 || $status == ItemManage::STATUS_CARRY_OUT)
                $query->andFilterWhere(['between','Course.real_carry_out',$dateRange_Arr[0],$dateRange_Arr[1]]);
            if($status == 0 || $status == 100 || $status == ItemManage::STATUS_NORMAL)
                $query->andFilterWhere(['between','Course.plan_start_time',$dateRange_Arr[0],$dateRange_Arr[1]]);
        }
        $model = new ItemManage();
        $teams = $this->getStatisticsByTeam($query);
        /** 总学时 */
        $allCHours = array_sum(ArrayHelper::getColumn($teams, 'value'));
        return $this->render('index',[
            'dateRange'=>$dateRange,
            'item_type_id'=>$item_type_id,
            'item_id'=>$item_id,
            'item_child_id'=>$item_child_id,
            'team'=>$team,
            'status'=>$status,
            'model'=>$model,
            'allCHours'=>$allCHours,
            
            'twTool'=>Yii::$app->get('twTool'),
            'itemTypes'=>$this->getStatisticsByItemType($query),
            'items'=>$this->getStatisticsByItem($query),
            'itemChilds'=>$this->getStatisticsByItemChild($query),
            'teams'=>$teams,
            'item_type_ids'=>$this->getItemTyps(),
            'item_ids'=>$this->getItems(),
            'teamIds'=>$this->getTeamIds(),
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
        $teamIds = Team::find()
                    ->select(['id','name'])
                    ->asArray()
                    ->all();
        $teamIds = ArrayHelper::map($teamIds, 'id', 'name');
        return $teamIds;
    }
    /**
     * 按行业统计
     * @param Query $sourceQuery
     * @return Array
     */
    private function getStatisticsByItemType($sourceQuery){
        $itemTypQuery = clone $sourceQuery;
        $itemTypQuery->select(['ItemType.name','SUM(Course.lession_time) AS value'])
                    ->from(['Course'=>CourseManage::tableName()])
                    ->leftJoin(['Item'=>ItemManage::tableName()], 'Course.project_id = Item.id')
                    ->leftJoin(['ItemType'=>  ItemType::tableName()],'Item.item_type_id = ItemType.id')
                    ->groupBy('Item.item_type_id');
        return $itemTypQuery->all(Yii::$app->db);
    }
    /**
     * 按项目统计
     * @param Query $sourceQuery
     * @return Array
     */
    private function getStatisticsByItem($sourceQuery){
        $itemQuery = clone $sourceQuery;
        $itemQuery->select(['FwItem.name','SUM(Course.lession_time) AS value'])
                ->from(['Course'=>CourseManage::tableName()])
                ->leftJoin(['Item'=>ItemManage::tableName()], 'Course.project_id = Item.id')
                ->leftJoin(['FwItem'=> Item::tableName()],'Item.item_id = FwItem.id')
                ->groupBy('Item.item_id');
        return $itemQuery->all(Yii::$app->db);
    }
     /**
     * 按子项目统计
     * @param Query $sourceQuery
     * @return Array
     */
    private function getStatisticsByItemChild($sourceQuery){
        $itemChildQuery = clone $sourceQuery;
        $itemChildQuery->select(['FwItem.name','SUM(Course.lession_time) AS value'])
                    ->from(['Course'=>CourseManage::tableName()])
                    ->leftJoin(['Item'=>ItemManage::tableName()], 'Course.project_id = Item.id')
                    ->leftJoin(['FwItem'=> Item::tableName()],'Item.item_child_id = FwItem.id')
                    ->groupBy('Item.item_child_id');
        return $itemChildQuery->all(Yii::$app->db);
    }
    
    /**
     * 按团队统计
     * @param Query $sourceQuery
     * @return Array
     */
    private function getStatisticsByTeam($sourceQuery){
        $teamQuery = clone $sourceQuery;
        $teamQuery->select(['Team.name','SUM(Course.lession_time) AS value'])
                ->from(['Team'=> Team::tableName()])
                ->leftJoin(['Course'=>CourseManage::tableName()],'Course.team_id = Team.id')
                ->leftJoin(['Item'=>ItemManage::tableName()], 'Course.project_id = Item.id')
                ->groupBy('Team.id');
        return $teamQuery->all(Yii::$app->db);
    }
}
