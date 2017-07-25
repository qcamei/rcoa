<?php

namespace frontend\modules\worksystem\utils;

use common\models\team\Team;
use common\models\team\TeamCategory;
use common\models\worksystem\WorksystemTask;
use common\models\worksystem\WorksystemTaskType;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;



class WorksystemStatistics
{
    private static $instance = null;
   
    /**
     * 初始数据项
     * @return array
     */
    public function getDataInitial()
    {
        $results = $this->getWorksystemTaskTypes();
        $items = [];
        foreach ($results as $itemName) {
           $items[$itemName] = [
                'value' => 0,
                'name' => $itemName
           ];
        }
        
        return $items;
    }
    
    /**
     * 添加 数据项
     * @param array $target                     目标数组
     * @param type $item                        项名称
     * @param type $type                        数据类型
     * @param type $value                       数据值
     */
    public function addDatas(&$target, $item, $type, $value)
    {
        if($item != null){
            //创建 item 数组
            if(!isset($target[$item]))
                $target[$item] = [];
            $item_target = &$target[$item];
        }else
            $item_target = &$target;
        //创建 不同类型数组并且累加
        if(!isset($item_target[$type]))
            $item_target[$type]['value'] = 0;
        
        $item_target[$type]['name'] = $type;
        $item_target[$type]['value'] += $value;
    }

    /**
     * 查询工作系统任务数据
     * @param array $dateRange
     * @return query
     */
    public function findWorksystemTaskData($dateRange)
    {
        $query = (new Query())
                ->select([
                'COUNT(Worksystem.id) AS id',
                'Worksystem.task_type_id',
                'Worksystem.is_epiboly',
                'Worksystem.reality_cost',
                'Worksystem.finished_at',
                'Team.`name` AS team_name'
            ])
            ->from(['Worksystem' => WorksystemTask::tableName()])
            ->leftJoin(['Team' => Team::tableName()], 'Team.id = Worksystem.create_team')
            ->where(['Worksystem.status' => WorksystemTask::STATUS_COMPLETED])
            ->andWhere(['between','Worksystem.finished_at',strtotime($dateRange[0]),strtotime($dateRange[1])])
            ->orderBy('Team.index DESC')
            ->groupBy('Worksystem.id');
        
        return $query->all(Yii::$app->db);
    }
    
    /**
     * 获取所有工作系统任务类型
     * @return array
     */
    public function getWorksystemTaskTypes()
    {
        $results = (new Query())
                ->select(['id', 'name'])
                ->from(WorksystemTaskType::tableName())
                ->all();
        
        return ArrayHelper::map($results, 'id', 'name');
    }
    
    /**
     * 获取所有初始开发团队
     * @return array
     */
    public function getDataInitialTeams()
    {
        $_tmTool = TeamMemberTool::getInstance();
        $teams = $_tmTool->getTeamsByCategoryId(TeamCategory::TYPE_CCOA_DEV_TEAM);
        $teamInitias = [];
        foreach ($teams as $item) {
            $teamInitias[$item['name']] =[];
        }
        
        return $teamInitias;
    }

    /**
     * 获取单例
     * @return WorksystemStatistics
     */
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new WorksystemStatistics();
        }
        return self::$instance;
    }
}
