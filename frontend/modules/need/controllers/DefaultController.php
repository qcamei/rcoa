<?php

namespace frontend\modules\need\controllers;

use common\models\need\NeedTask;
use common\models\User;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Default controller for the `need` module
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            //access验证是否有登录
            'access' => [
                'class' => AccessControl::class,
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
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $season = ceil(date('n') /3);   //获取月份的季度
        $seasonStart = strtotime(date('Y-m-01 H:i:s', mktime(0, 0, 0, ($season - 1) * 3 + 1, 1, date('Y')))); //当前季度的开始时间
        $seasonEnd = strtotime(date('Y-m-t H:i:s', mktime(23, 59, 59, $season * 3, 1, date('Y'))));           //当前季度的结束时间

        return $this->render('index',[
            'needCosts' => $this->getUserNeedCostBySeason($seasonStart, $seasonEnd),      //当前季度用户的需求成本
            'demands' => $this->getUserDemandBySeason($seasonStart, $seasonEnd),          //当前季度用户的绩效
        ]);
    }
    
    /**
     * 获取当前季度用户的需求成本
     * @param int $seasonStart  当前季度的开始时间
     * @param int $seasonEnd    当前季度的结束时间
     * @return array
     */
    public function getUserNeedCostBySeason($seasonStart, $seasonEnd) 
    {
        $filter = [NeedTask::STATUS_DEFAULT, NeedTask::STATUS_FINISHED];
        $needCost = (new Query())
                ->select(['User.nickname', 'User.avatar', 'SUM(COALESCE(plan_content_cost,0) + '
                    . 'COALESCE(plan_outsourcing_cost,0) + (COALESCE(plan_content_cost,0) + COALESCE(plan_outsourcing_cost,0)) * performance_percent) AS need_cost'])
                ->from(['NeedTask' => NeedTask::tableName()])
                ->leftJoin(['User' => User::tableName()], 'User.id = NeedTask.created_by')
                ->where(['NeedTask.is_del' => 0])
                ->andFilterWhere(['between', 'NeedTask.created_at', $seasonStart, $seasonEnd])
                ->andFilterWhere(['NOT IN', 'NeedTask.status', $filter])             //过滤创建中和已完成的数据
                ->groupBy('NeedTask.created_by')
                ->all();

        return $needCost;
    }
    
    /**
     * 获取当前季度用户的绩效
     * @param int $seasonStart  当前季度的开始时间
     * @param int $seasonEnd    当前季度的结束时间
     * @return array
     */
    public function getUserDemandBySeason($seasonStart, $seasonEnd) 
    {
        $filter = [NeedTask::STATUS_DEFAULT, NeedTask::STATUS_FINISHED];
        $demand = (new Query())
                ->select(['User.nickname', 'User.avatar',
                    'SUM((COALESCE(plan_content_cost,0) + COALESCE(plan_outsourcing_cost,0)) * performance_percent) AS demand'])
                ->from(['NeedTask' => NeedTask::tableName()])
                ->leftJoin(['User' => User::tableName()], 'User.id = NeedTask.receive_by')
                ->where(['NeedTask.is_del' => 0])
                ->andFilterWhere(['between', 'NeedTask.created_at', $seasonStart, $seasonEnd])
                ->andFilterWhere(['NOT IN', 'NeedTask.status', $filter])             //过滤创建中和已完成的数据
                ->groupBy('NeedTask.receive_by')
                ->all();

        return $demand;
    }
}
