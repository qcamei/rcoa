<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandTask;
use common\models\team\TeamCategory;
use frontend\modules\demand\utils\DemandTool;
use wskeee\team\TeamMemberTool;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Default controller for the `demand` module
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
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        /* @var $dtTool DemandTool */
        $dtTool = DemandTool::getInstance();
        $completed = $dtTool->getDemandCount(DemandTask::STATUS_COMPLETED);
        $unfinished = $dtTool->getDemandCount(DemandTask::$defaultStatus);
        
        return $this->render('index', [
            'completed' => ArrayHelper::getValue($completed, 'total_lesson_time'),
            'unfinished' => ArrayHelper::getValue($unfinished, 'total_lesson_time'),
            'team' => $this->getCourseDevelopTeam(),
            'teamCompleted' => $dtTool->getTeamDemandCount(DemandTask::STATUS_COMPLETED),
            'teamUnfinished' => $dtTool->getTeamDemandCount(DemandTask::$defaultStatus),
        ]);
    }
    
    /**
     * 获取所有课程开发团队
     * @return array
     */
    public function getCourseDevelopTeam()
    {
        $tmTool = TeamMemberTool::getInstance();
        $teams = $tmTool->getTeamsByCategoryId(TeamCategory::TYPE_PRODUCT_CENTER);
        ArrayHelper::multisort($teams, 'index', SORT_ASC);
       
        return $teams;
    }
}
