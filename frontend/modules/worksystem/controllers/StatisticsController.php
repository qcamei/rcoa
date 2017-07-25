<?php

namespace frontend\modules\worksystem\controllers;

use common\models\worksystem\WorksystemTask;
use frontend\modules\worksystem\utils\WorksystemStatistics;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

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
        return $this->getStatistics();
    }
    
    /**
     * 获取统计数据
     * @param WorksystemStatistics $_wsStatistics
     * @return mixed
     */
    public function getStatistics()
    {
        $_wsStatistics = WorksystemStatistics::getInstance();
        $date = Yii::$app->getRequest()->getQueryParam("dateRange");
        $time = explode(" - ", $date);
        if($date == null){
            $time = [
                date('Y-m-01 0:0:0', time()),  
                date('Y-m-t 23:59:59', time())
            ];
        }
       
        $results = $_wsStatistics->getWorksystemTaskTypes();
        $datas = $_wsStatistics->findWorksystemTaskData($time);
        $datas_count = [];
        $datas_totalCost = [];
        $datas_epibolyCost = [];
        $datas_teamCosts = [];
        $counts = 0;
        $countCost = 0;
        $epibolyCost = 0;
        $target;
        
        foreach ($datas as $index => $result) {
            $typeId = $result['task_type_id'];
            $name = $results[$result['task_type_id']];
            $counts += $result['id'];
            $countCost += $result['reality_cost'];
            $epibolyCost += $result['is_epiboly'] ? $result['reality_cost'] : 0;
            $_wsStatistics->addDatas($datas_count, null, $typeId, $name, $result['id']);
            $_wsStatistics->addDatas($datas_totalCost, null, $typeId, $name, $result['reality_cost']);
            $_wsStatistics->addDatas($datas_epibolyCost, $result['is_epiboly'], $typeId, $name, $result['reality_cost']);
            $_wsStatistics->addDatas($datas_teamCosts, $result['team_name'], $typeId, $name, $result['reality_cost']);
            
        }
        if(!isset($datas_epibolyCost[WorksystemTask::SEEK_EPIBOLY_MARK])){
            $datas_epibolyCost = [];
        }
        $datas_teamCost = [];
        foreach ($datas_teamCosts as $keys => $element) {
            foreach ($element as $value) {
                $datas_teamCost[$keys][$value['name']] = $value['value'];
            }
        }
        
        return $this->render('index', [
            'datas_count' => array_values($datas_count),
            'datas_totalCost' => array_values($datas_totalCost),
            'datas_epibolyCost' => array_values($datas_epibolyCost) ,
            'datas_teamCost' => $datas_teamCost,
            'types' => array_values($results),
            
            //计算总数
            'counts' => $counts,
            'countCost' => $countCost,
            'epibolyCost' => $epibolyCost,
            'dateRange' => $date,
        ]);
    }
}
