<?php

namespace frontend\modules\scene\controllers;

use common\models\scene\SceneAppraise;
use common\models\scene\SceneBook;
use common\models\scene\SceneBookUser;
use common\models\scene\SceneSite;
use common\models\User;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Request;

/**
 * Statistics controller for the `scene` module
 */
class StatisticsController extends Controller
{   
    public $layout = 'scene';
    
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
                        'roles' => [],
                    ]
                ],
            ],
        ];
    }
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {       
        /* @var $request Request */
        $request = Yii::$app->getRequest();
        /** 时间段 */
        $date = $request->getQueryParam("dateRange");
        /** 场地名称 */
        $site = $request->getQueryParam("siteName");
        
        /* @var $query Query */
        $query = (new Query())
                ->andFilterWhere(['SceneBook.site_id' => $site]);
        
        //按时间段搜索
        if($date != null){
            $date_Arr = explode(" - ", $date);
            $query->andFilterWhere(['between', 'SceneBook.date', $date_Arr[0], $date_Arr[1]]);
        }
        
        return $this->render('index',[
            'site' => $site,
            'dateRange' => $date,
            
            'siteName' => $this->getSiteName(),
            'books' => $this->getStatisticsBySite($query),
            'booker' => $this->getStatisticsByBooker($query),
            'director' => $this->getStatisticsByDirector($query),
            'photographer' => $this->getStatisticsByDirector($query, 2),
        ]);
    }

    /**
     * 查询场地
     * @return array
     */
    private function getSiteName() {
        $query = (new Query())
                ->select(['id', 'name'])
                ->from(['Site' => SceneSite::tableName()])
                ->all();
        
        return ArrayHelper::map($query, 'id', 'name');
    }

    /**
     * 按照场地统计
     * @param Query $query
     * @return array
     */
    private function getStatisticsBySite($query) {
        $siteQuery = clone $query;
        $notStatus = [SceneBook::STATUS_DEFAULT, SceneBook::STATUS_BOOKING];            //未预约和预约中
        
        $siteQuery->select(['Site.name', 'COUNT(SceneBook.site_id) AS book_number',
                            'SUM(SceneBook.status=400) AS miss_number', 'min(SceneBook.date) AS earliest_time'])
                ->from(['SceneBook' => SceneBook::tableName()])
                ->leftJoin(['Site' => SceneSite::tableName()], 'Site.id = SceneBook.site_id')
                ->andFilterWhere(['NOT IN', 'SceneBook.status', $notStatus])             //过滤未预约和预约中的数据
                ->groupBy(['SceneBook.site_id']);
        $allCount = $siteQuery->all(\Yii::$app->db);

        //计算总数
        $allTotal = [];
        if($allCount != null){
            $allTotal = [[
                'name' => '合计',
                'book_number' => array_sum(ArrayHelper::getColumn($allCount, 'book_number')),
                'miss_number' => array_sum(ArrayHelper::getColumn($allCount, 'miss_number')),
                'earliest_time' => min(ArrayHelper::getColumn($allCount, 'earliest_time')),
            ]];
        }

        return array_merge($allCount, $allTotal);
    }
    
    /**
     * 按照预约人统计
     * @param Query $query
     * @return array
     */
    private function getStatisticsByBooker($query) {
        $bookerQuery = clone $query;
        $notStatus = [SceneBook::STATUS_DEFAULT, SceneBook::STATUS_BOOKING];            //未预约和预约中
        
        $bookerQuery->select(['User.nickname', 'COUNT(SceneBook.booker_id) AS booker_number',
                                'SUM(SceneBook.status=400) AS miss_number',
                                'FORMAT((SUM(SceneBook.status=400)/COUNT(SceneBook.booker_id)) * 100, 2) AS miss_rate'])
                ->from(['SceneBook' => SceneBook::tableName()])
                ->leftJoin(['User' => User::tableName()], 'User.id = SceneBook.booker_id')
                ->andFilterWhere(['NOT IN', 'SceneBook.status', $notStatus])             //过滤未预约和预约中的数据
                ->groupBy(['SceneBook.booker_id']);
        
        return $bookerQuery->all(\Yii::$app->db);
    }
    
    /**
     * 按照编导统计/按照摄影师统计
     * @param Query $query
     * @param integer $role     1为编导，2为摄影师
     * @return array
     */
    private function getStatisticsByDirector($query, $role = 1) {
        $directorQuery = clone $query;
        $sceneBookQuery = clone $query;
        $notStatus = [SceneBook::STATUS_DEFAULT, SceneBook::STATUS_BOOKING];            //未预约和预约中

        $sceneBookQuery->select(['SceneBook.id'])
            ->from(['SceneBook' => SceneBook::tableName()]);
        $sceneBookQuery->andWhere(['NOT IN', 'SceneBook.status', $notStatus]);          //过滤未预约和预约中的数据
        
        $directorQuery->select(['User.nickname AS name', 'COUNT(DISTINCT SceneBookUser.book_id) AS contact_number',
                                'FORMAT((SUM(SceneAppraise.user_value)/SUM(SceneAppraise.q_value) * 100), 1) AS score'])
                ->from(['SceneBookUser' => SceneBookUser::tableName()])
                ->leftJoin(['User' => User::tableName()], 'User.id = SceneBookUser.user_id AND User.status = 10')
                ->leftJoin(['SceneAppraise' => SceneAppraise::tableName()], 
                        '(SceneAppraise.book_id = SceneBookUser.book_id AND SceneAppraise.role = SceneBookUser.role)')
                ->where(['SceneBookUser.book_id' => $sceneBookQuery, 'SceneBookUser.role' => $role])
                ->andFilterWhere(['SceneBookUser.is_delete' => 0])
                ->groupBy(['SceneBookUser.user_id']);

        return $directorQuery->all(\Yii::$app->db);
    }

}
