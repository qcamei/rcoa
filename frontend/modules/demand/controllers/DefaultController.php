<?php

namespace frontend\modules\demand\controllers;

use common\models\demand\DemandTask;
use common\models\demand\DemandWorkitem;
use common\models\team\Team;
use common\models\team\TeamCategory;
use frontend\modules\demand\utils\DemandTool;
use wskeee\team\TeamMemberTool;
use yii\db\Query;
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
        
        return $this->render('index', [
            'team' => $this->getCourseDevelopTeam(),
            'teamCompleted' => $dtTool->getTeamDemandCount(DemandTask::STATUS_COMPLETED),
            'teamUnfinished' => $dtTool->getTeamDemandCount(DemandTask::$defaultStatus),
            'teamCost' => $this->getTeamDemandWorkitemCost(),
        ]);
    }
    
    /**
     * Member all ItemManage models.
     * @return mixed
     */
    public function actionMember($team_id)
    {
       $team = Team::findOne(['id' => $team_id]);
        return $this->render('member', [
            'team' => $team,
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
    
    public function getTeamDemandWorkitemCost()
    {
        $teamCost = (new Query())
                    ->select([
                        'Demamd_task.create_team',
                        'CEIL(SUM((Demamd_task.cost + Demamd_task.cost * Demamd_task.bonus_proportion) + Demamd_task.external_reality_cost )) / 10000 AS cost'
                    ])
                    ->from(['Demamd_task' => DemandTask::tableName()])
                    ->where(['!=', 'Demamd_task.status', DemandTask::STATUS_CANCEL])
                    ->groupBy('Demamd_task.create_team')
                    ->all();
        
        return ArrayHelper::map($teamCost, 'create_team', 'cost');
    }
}
