<?php

namespace frontend\modules\need\controllers;

use common\models\need\NeedContent;
use common\models\need\NeedTask;
use common\models\need\NeedTaskUser;
use common\models\User;
use common\models\workitem\Workitem;
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
    /* 重构 layout */
    public $layout = 'statisticsmenu';
    
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
    
    /**
     * 统计-成本页面
     * @return array
     */
    public function actionCost()
    {
        /* @var $request Request */
        $request = Yii::$app->getRequest();
        $type = $request->getQueryParam('type');
        $dateRange = $request->getQueryParam('dateRange');
             
        /* @var $query Query */
        $query = (new Query())->where(['NeedTask.status' => NeedTask::STATUS_FINISHED]);  //已完成的数据
        
        /* 当时间段参数不为空时 */
        if($dateRange != null){
            $dateRange_Arr = explode(" - ",$dateRange);
            $query->andFilterWhere(['between', 'NeedTask.finish_time', strtotime($dateRange_Arr[0]), strtotime($dateRange_Arr[1])]);
        }

        return $this->render('cost', [
            'type' => empty($type) ? '0' : $type,
            'dateRange' => $dateRange,
            'totalCost' => $this->getTotalCost($query),                 //总成本
            'totalWorikitemCost' => $this->getWorikitemCost($query),    //内容总成本（不含绩效）
            'business' => $this->getStatisticsByBusiness($query),       //按行业统计成本
            'layer' => $this->getStatisticsByLayer($query),             //按层次类型统计成本
            'profession' => $this->getStatisticsByProfession($query),   //按专业工种统计成本
            'presonal' => $this->getTotalCostByPresonal($query),        //按人统计成本
            'workitems' => $this->getStatisticsByWorkitem($query),      //按工作项统计
            'items' => ['0' => '新建', '1' => '改造'],
        ]);
    }

    /**
     * 统计-绩效页面
     * @return array
     */
    public function actionBonus()
    {
        /* @var $request Request */
        $request = Yii::$app->getRequest();
        $dateRange = $request->getQueryParam('dateRange');
                
        /* @var $query Query */
        $query = (new Query())->where(['NeedTask.status' => NeedTask::STATUS_FINISHED]);    //已完成的数据
        
        /* 当时间段参数不为空时 */
        if($dateRange != null){
            $dateRange_Arr = explode(" - ",$dateRange);
            $query->andFilterWhere(['between', 'NeedTask.finish_time', strtotime($dateRange_Arr[0]), strtotime($dateRange_Arr[1])]);
        }
        
        return $this->render('bonus', [
            'dateRange' => $dateRange,
            'totalBonus' => $this->getTotalBonus($query),   //总绩效
            'bonus' => $this->getBonusByPresonal($query),   //根据人统计绩效
        ]);
    }
    
    /**
     * 统计-课程明细页面
     * @return array
     */
    public function actionCourseDetails()
    {
        /* @var $request Request */
        $request = Yii::$app->getRequest();
        $type = $request->getQueryParam('type');
        /** 行业 */
        $business = $request->getQueryParam('business');
        /** 层次/类型 */
        $layer = $request->getQueryParam('layer');
        /** 专业/工种 */
        $profession = $request->getQueryParam('profession');
        /** 课程 */
        $course = $request->getQueryParam('course');
              
        /* @var $query Query */
        $query = (new Query())->where(['NeedTask.status' => NeedTask::STATUS_FINISHED]) //已完成的数据
                ->andFilterWhere(['NeedTask.`business_id`' => $business])
                ->andFilterWhere(['NeedTask.`layer_id`' => $layer])
                ->andFilterWhere(['NeedTask.`profession_id`' => $profession])
                ->andFilterWhere(['NeedTask.`course_id`' => $course]);             
        
        return $this->render('course-details', [
            'type' => empty($type) ? '0' : $type,
            'business' => $business,    //行业
            'layer' => $layer,          //层次/类型
            'profession' => $profession,//专业/工种
            'course' => $course,        //课程
            'totalCost' => $this->getTotalCost($query),                 //总成本
            'totalWorikitemCost' => $this->getWorikitemCost($query),    //内容总成本（不含绩效）
            'workitems' => $this->getStatisticsByWorkitem($query),      //按工作项统计
            'presonal' => $this->getTotalCostByPresonal($query),        //按人统计成本
            'businesss' => $this->getItemTyps(),    //行业
            'layers' => $this->getItems(),          //层次/类型
            'professions' => $this->getChildrens($layer),   //专业/工种
            'courses' => $this->getChildrens($profession),  //课程
            'items' => ['0' => '新建', '1' => '改造'],
        ]);
    } 
    
    /**
     * 统计-个人明细页面
     * @return array
     */
    public function actionPersonalDetails()
    {
        /* @var $request Request */
        $request = Yii::$app->getRequest();
        $type = $request->getQueryParam('type');
        $dateRange = $request->getQueryParam('dateRange');
        /** 个人名称 */
        $username = $request->getQueryParam('username');
        
        /* @var $query Query */
        $query = (new Query())->where(['NeedTask.status' => NeedTask::STATUS_FINISHED]) //已完成的数据
                ->andFilterWhere(['NeedTask.receive_by' => $username]);             
        
        /* 当时间段参数不为空时 */
        if($dateRange != null){
            $dateRange_Arr = explode(" - ",$dateRange);
            $query->andFilterWhere(['between', 'NeedTask.finish_time', strtotime($dateRange_Arr[0]), strtotime($dateRange_Arr[1])]);
        }
        
        return $this->render('personal-details', [
            'type' => empty($type) ? '0' : $type,
            'dateRange' => $dateRange,
            'username' => $username,    //个人名称
            'receive' => $this->getAllReceive(),            //承接人
            'totalCost' => $this->getTotalCost($query),     //总成本
            'totalBonus' => $this->getTotalBonus($query),   //总绩效
            'taskCost' => $this->getTaskCost($query),       //根据成本统计
            'taskBonus' => $this->getTaskBonus($query),     //根据绩效统计
        ]);
    }

    //--------------------------------------------------------------------------
    //
    // 统计-公共方法
    //
    //--------------------------------------------------------------------------
    /**
     * 获取总成本
     * @param Query $sourceQuery
     * @return array
     */
    public static function getTotalCost($sourceQuery)
    {
        $selectName = 'SUM(COALESCE(NeedTask.reality_content_cost,0) + COALESCE(NeedTask.reality_outsourcing_cost,0) + '
                . '(COALESCE(NeedTask.reality_content_cost,0)) * NeedTask.performance_percent)';
        $totalCostQuery = clone $sourceQuery;
        $totalCostQuery->select(["{$selectName} AS total_cost"])
                ->from(['NeedTask' => NeedTask::tableName()]);

        return $totalCostQuery->one(Yii::$app->db);
    }
    
    /**
     * 获取总绩效
     * @param Query $sourceQuery
     * @return array
     */
    public static function getTotalBonus($sourceQuery)
    {
        $selectName = 'SUM((COALESCE(NeedTask.reality_content_cost,0)) * NeedTask.performance_percent)';
        $totalBonusQuery = clone $sourceQuery;
        $totalBonusQuery->select(["{$selectName} AS total_bonus"])
                ->from(['NeedTask' => NeedTask::tableName()]);

        return $totalBonusQuery->one(Yii::$app->db);
    }
    
    /**
     * 根据人统计成本
     * @param Query $sourceQuery
     * @return array
     */
    public static function getTotalCostByPresonal($sourceQuery)
    {
        $selectName = 'SUM(COALESCE(NeedTask.reality_content_cost,0) + COALESCE(NeedTask.reality_outsourcing_cost,0) + '
                . '(COALESCE(NeedTask.reality_content_cost,0)) * NeedTask.performance_percent)';
        $presonalQuery = clone $sourceQuery;
        $presonalQuery->select(['User.nickname AS name', "{$selectName} AS value"])
                ->from(['NeedTask' => NeedTask::tableName()])
                ->leftJoin(['User' => User::tableName()], 'User.id = NeedTask.created_by')
                ->groupBy('NeedTask.created_by')
                ->orderBy([$selectName => SORT_ASC]);;
        
        return $presonalQuery->all(Yii::$app->db);
    }
    
    /**
     * 根据工作内容统计成本
     * @param Query $sourceQuery
     * @return array
     */
    public static function getStatisticsByWorkitem($sourceQuery)
    {
        $selectName = 'SUM(NeedContent.price * NeedContent.reality_num)';
        $workitemQuery = clone $sourceQuery;
        $workitemQuery->select(['Workitem.name', "$selectName AS value"])
                ->from(['NeedTask' => NeedTask::tableName()])
                ->leftJoin(['NeedContent' => NeedContent::tableName()], '(NeedContent.need_task_id = NeedTask.id AND NeedContent.is_del = 0)')
                ->leftJoin(['Workitem' => Workitem::tableName()], 'Workitem.id = NeedContent.workitem_id')
                ->groupBy('Workitem.id');

        //新建
        $workitemNewQuery = clone $workitemQuery;
        $workitemNews = $workitemNewQuery
                ->andFilterWhere(['NeedContent.is_new' => 0])->all();

        //改造
        $workitemRemouldQuery = clone $workitemQuery;
        $workitemRemoulds = $workitemRemouldQuery
                ->andFilterWhere(['NeedContent.is_new' => 1])->all();

        $newItems = [];
        $remouldsItems = [];
        //新建
        foreach ($workitemNews as $keys => $workitemNew) {
            $newItems[$workitemNew['name']]['新建'] = (float)$workitemNew['value'];
        }
        //改造
        foreach ($workitemRemoulds as $keys => $workitemNew) {
            $remouldsItems[$workitemNew['name']]['改造'] = (float)$workitemNew['value'];
        }

        return ArrayHelper::merge($newItems, $remouldsItems);
    }
    
    //--------------------------------------------------------------------------
    //
    // 统计-成本页面
    //
    //--------------------------------------------------------------------------
    /**
     * 按行业统计
     * @param Query $sourceQuery
     * @return Array
     */
    public static function getStatisticsByBusiness($sourceQuery)
    {
        $selectName = 'SUM(COALESCE(NeedTask.reality_content_cost,0) + COALESCE(NeedTask.reality_outsourcing_cost,0) + '
                . '(COALESCE(NeedTask.reality_content_cost,0)) * NeedTask.performance_percent)';
        $businessQuery = clone $sourceQuery;
        $businessQuery->select(['ItemType.name',"{$selectName} AS value"])
                    ->from(['NeedTask' => NeedTask::tableName()])
                    ->leftJoin(['ItemType' => ItemType::tableName()], 'NeedTask.business_id = ItemType.id')
                    ->groupBy('NeedTask.business_id');
        
        return $businessQuery->all(Yii::$app->db);
    }
    
    /**
     * 按层次类型统计
     * @param Query $sourceQuery
     * @return Array
     */
    public static function getStatisticsByLayer($sourceQuery)
    {
        $selectName = 'SUM(COALESCE(NeedTask.reality_content_cost,0) + COALESCE(NeedTask.reality_outsourcing_cost,0) + '
                . '(COALESCE(NeedTask.reality_content_cost,0)) * NeedTask.performance_percent)';
        $layerQuery = clone $sourceQuery;
        $layerQuery->select(['FwItem.name',"{$selectName} AS value"])
                ->from(['NeedTask' => NeedTask::tableName()])
                ->leftJoin(['FwItem' => Item::tableName()], 'NeedTask.layer_id = FwItem.id')
                ->groupBy('NeedTask.layer_id');
        
        return $layerQuery->all(Yii::$app->db);
    }
    
     /**
     * 按专业工种统计
     * @param Query $sourceQuery
     * @return Array
     */
    public static function getStatisticsByProfession($sourceQuery)
    {
        $selectName = 'SUM(COALESCE(NeedTask.reality_content_cost,0) + COALESCE(NeedTask.reality_outsourcing_cost,0) + '
                . '(COALESCE(NeedTask.reality_content_cost,0)) * NeedTask.performance_percent)';
        $professionQuery = clone $sourceQuery;
        $professionQuery->select(['FwItem.name',"{$selectName} AS value"])
                    ->from(['NeedTask' => NeedTask::tableName()])
                    ->leftJoin(['FwItem' => Item::tableName()], 'NeedTask.profession_id = FwItem.id')
                    ->groupBy('NeedTask.profession_id');
        
        return $professionQuery->all(Yii::$app->db);
    }
    
    /**
     * 内容总成本（不含绩效）
     * @param Query $sourceQuery
     * @return array
     */
    public static function getWorikitemCost($sourceQuery)
    {
        $selectName = 'SUM(NeedContent.price * NeedContent.reality_num)';
        $workitemQuery = clone $sourceQuery;
        $workitemQuery->select(["$selectName AS value"])
                ->from(['NeedTask' => NeedTask::tableName()])
                ->leftJoin(['NeedContent' => NeedContent::tableName()], '(NeedContent.need_task_id = NeedTask.id AND NeedContent.is_del = 0)');
        
        return $workitemQuery->one(Yii::$app->db);
    }

    //--------------------------------------------------------------------------
    //
    // 统计-绩效页面
    //
    //--------------------------------------------------------------------------
    /**
     * 根据人统计绩效
     * @param Query $sourceQuery
     * @return array
     */
    public static function getBonusByPresonal($sourceQuery)
    {
        $selectName = 'SUM((COALESCE(NeedTask.reality_content_cost,0)) * NeedTask.performance_percent)';
        $presonalQuery = clone $sourceQuery;
        $presonalQuery->select(['User.nickname AS name', "{$selectName} AS value"])
                ->from(['NeedTask' => NeedTask::tableName()])
                ->leftJoin(['NeedTaskUser' => NeedTaskUser::tableName()], 'NeedTaskUser.need_task_id = NeedTask.id')
                ->leftJoin(['User' => User::tableName()], 'User.id = NeedTaskUser.user_id')
                ->andFilterWhere(['NeedTaskUser.is_del' => 0])
                ->groupBy('NeedTaskUser.user_id')
                ->orderBy([$selectName => SORT_ASC]);

        return $presonalQuery->all(Yii::$app->db);
    }
    
    //--------------------------------------------------------------------------
    //
    // 统计-课程明细页
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
     * 获取所有专业/工种 or 课程
     * @param type $itemId              层次/类型ID
     * @return type
     */
    protected function getChildrens($itemId)
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getChildren($itemId), 'id', 'name');
    }
    
    //--------------------------------------------------------------------------
    //
    // 统计-个人明细页
    //
    //--------------------------------------------------------------------------
    /**
     * 获取所有承接人
     * @return Array [id=>name]
     */
    private function getAllReceive()
    {
        $allReceive = (new Query())
                ->select(['User.id', 'User.nickname AS name'])
                ->from(['NeedTask' => NeedTask::tableName()])
                ->leftJoin(['User' => User::tableName()], 'User.id = NeedTask.receive_by')
                ->where(['NeedTask.status' => NeedTask::STATUS_FINISHED])
                ->all();
        
        return ArrayHelper::map($allReceive, 'id', 'name');
    }
    
    /**
     * 获取个人成本明细
     * @param Query $sourceQuery
     * @return array
     */
    public static function getTaskCost($sourceQuery)
    {
        $selectName = 'SUM(COALESCE(NeedTask.reality_content_cost,0) + COALESCE(NeedTask.reality_outsourcing_cost,0) + '
                . '(COALESCE(NeedTask.reality_content_cost,0)) * NeedTask.performance_percent)';
        $businessQuery = clone $sourceQuery;
        $businessQuery->select(['NeedTask.id', 'ItemType.name AS business_name', 'Layer.name AS layer_name', 
                    'Profession.name AS Profession_name', 'Course.name AS course_name', 'NeedTask.task_name', 
                    'NeedTask.finish_time', 'User.nickname', "{$selectName} AS reality_cost"])
                ->from(['NeedTask' => NeedTask::tableName()])
                ->leftJoin(['ItemType'=>  ItemType::tableName()],'NeedTask.business_id = ItemType.id')
                ->leftJoin(['Layer' => Item::tableName()], 'NeedTask.layer_id = Layer.id')
                ->leftJoin(['Profession' => Item::tableName()], 'NeedTask.profession_id = Profession.id')
                ->leftJoin(['Course' => Item::tableName()], 'NeedTask.course_id = Course.id')
                ->leftJoin(['User' => User::tableName()], 'User.id = NeedTask.receive_by')
                ->groupBy('NeedTask.id');
            
        return $businessQuery->all(Yii::$app->db);
    }
    
    /**
     * 获取个人绩效明细
     * @param Query $sourceQuery
     * @return array
     */
    public static function getTaskBonus($sourceQuery)
    {
        $selectCost = 'SUM(COALESCE(NeedTask.reality_content_cost,0))';
        $selectBonus = 'SUM((COALESCE(NeedTask.reality_content_cost,0)) * NeedTask.performance_percent)';
        $businessQuery = clone $sourceQuery;
        $businessQuery->select(['NeedTask.id', 'Layer.name AS layer_name', 'Profession.name AS Profession_name', 
                    'Course.name AS course_name', 'NeedTask.task_name', 'NeedTask.finish_time', 'User.nickname',
                    "{$selectCost} AS reality_cost", "{$selectBonus} AS reality_bonus", 'NeedTask.performance_percent', 
                    "{$selectBonus} * NeedTaskUser.performance_percent AS personal_bonus"])
                ->from(['NeedTask' => NeedTask::tableName()])
                ->leftJoin(['Layer' => Item::tableName()], 'NeedTask.layer_id = Layer.id')
                ->leftJoin(['Profession' => Item::tableName()], 'NeedTask.profession_id = Profession.id')
                ->leftJoin(['Course' => Item::tableName()], 'NeedTask.course_id = Course.id')
                ->leftJoin(['User' => User::tableName()], 'User.id = NeedTask.receive_by')
                ->leftJoin(['NeedTaskUser' => NeedTaskUser::tableName()], '(NeedTaskUser.need_task_id = NeedTask.id AND NeedTaskUser.user_id = NeedTask.receive_by)')
                ->groupBy('NeedTask.id');

        return $businessQuery->all(Yii::$app->db);
    }
}
