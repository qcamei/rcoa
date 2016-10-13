<?php

namespace frontend\modules\teamwork\controllers;

use common\models\team\Team;
use common\models\teamwork\CourseManage;
use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\utils\TeamworkTool;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

class StatisticsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
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
        
        /* @var $query Query */
        $query = (new Query())
                ->andFilterWhere(['Course.status'=>$status])
                ->andFilterWhere(['Course.`team_id`'=>$team])
                ->andFilterWhere(['Item.`item_type_id`'=>$item_type_id])
                ->andFilterWhere(['Item.`item_id`'=>$item_id])
                ->andFilterWhere(['Item.`item_child_id`'=>$item_child_id]);
        /* 当时间段参数不为空时 */
        if($dateRange = $request->getQueryParam('dateRange')){
            $dateRange_Arr = explode(" - ",$dateRange);
            //下面所有例子设置时间段为 2016-08-01 到 2016-08-31
            if($status == CourseManage::STATUS_WAIT_START)
            {
                /*
                 * 状态=待开始 AND created_at(创建时间)<=指定时间段最大值
                 * 如：统计 到 2016-08-31 号还没有【开始】的课程
                 * 注：【去年建】的课程到 2016-08-31 还【未开始】也会统计在内。
                 */
                $query->andFilterWhere(['<=','Course.created_at',strtotime($dateRange_Arr[1])]);
            }else if($status == CourseManage::STATUS_NORMAL)
            {
                /*
                 * 状态=在建中 AND real_start_time(实际开始时间)<=指定时间段最大值
                 * 如：统计 到 2016-08-31 号还在【建设中】的课程
                 * 注：【去年开始】的课程到 2016-08-31 还【未完成】也会统计在内。
                 */
                //状态=在建中 AND real_start_time(实际开始时间)<=指定时间最大值，如：统计【指定最大值时间】还在【建设中】的课程
                $query->andFilterWhere(['<=','Course.real_start_time',strtotime($dateRange_Arr[1])]);
            }else if($status == CourseManage::STATUS_CARRY_OUT)
            {
                /*
                 * 状态=已完成 AND 指定时间最小值<real_carry_out(实际完成时间)<指定时间段最大值
                 * 如：统计 2016-08-01 到 2016-08-31 内完成的课程
                 */
                $query->andFilterWhere(['between','Course.real_carry_out',$dateRange_Arr[0],$dateRange_Arr[1]]);
            }else{
                /**
                 * 状态为空时，每个条件都加上对应状态
                 * 条件为或者关系，只要满足其中一条规则即可统计在内
                 */
                $query->orFilterWhere(['and',"Course.status=".CourseManage::STATUS_WAIT_START,  ['<=','Course.created_at',strtotime($dateRange_Arr[1])]]);
                $query->orFilterWhere(['and',"Course.status=".CourseManage::STATUS_NORMAL,      ['<=','Course.real_start_time',strtotime($dateRange_Arr[1])]]);
                $query->orFilterWhere(['and',"Course.status=".CourseManage::STATUS_CARRY_OUT,   ['between','Course.real_carry_out',$dateRange_Arr[0],$dateRange_Arr[1]]]);
            }
        }
        $model = new ItemManage();
        $teams = $this->getStatisticsByTeam($query);//按团队统计
        $allCHours = array_sum(ArrayHelper::getColumn($teams, 'value'));//总学时 
        $allCourse = array_sum(ArrayHelper::getColumn($teams, 'total'));//总课程
        return $this->render('index',[
            'dateRange'=>$dateRange,
            'item_type_id'=>$item_type_id,
            'item_id'=>$item_id,
            'item_child_id'=>$item_child_id,
            'team'=>$team,
            'status'=>$status,
            'model'=>$model,
            'allCHours'=>$allCHours,
            'allCourse'=>$allCourse,
            
            'twTool'=>TeamworkTool::getInstance(),
            'itemTypes'=>$this->getStatisticsByItemType($query),//按行业统计
            'items'=>$this->getStatisticsByItem($query),//按项目统计
            'itemChilds'=>$this->getStatisticsByItemChild($query),//按子项目统计
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
        $teamQuery->select(['Team.name','SUM(Course.lession_time) AS value','Count(Course.id) AS total'])
                ->from(['Team'=> Team::tableName()])
                ->leftJoin(['Course'=>CourseManage::tableName()],'Course.team_id = Team.id')
                ->leftJoin(['Item'=>ItemManage::tableName()], 'Course.project_id = Item.id')
                ->leftJoin(['FItem'=>ItemManage::tableName()], 'Course.course_id = FItem.id')
                ->groupBy('Team.id');
        return $teamQuery->all(Yii::$app->db);
    }
}
