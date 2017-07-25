<?php

namespace frontend\modules\worksystem\utils;

use common\models\team\Team;
use common\models\worksystem\WorksystemTask;
use common\models\worksystem\WorksystemTaskType;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;



class WorksystemStatistics
{
    private static $instance = null;
   
    /**
     * 添加 数据项
     * @param array $target                     目标数组
     * @param type $item                        项名称
     * @param type $typeId                      数据类型id
     * @param type $name                        数据类型
     * @param type $value                       数据值
     */
    public function addDatas(&$target, $item, $typeId, $name, $value)
    {
        if($item != null){
            //创建 item 数组
            if(!isset($target[$item]))
                $target[$item] = [];
            $item_target = &$target[$item];
        }else
            $item_target = &$target;
        //创建 不同类型数组并且累加
        if(!isset($item_target[$typeId])){
            $item_target[$typeId]['name'] = '';
            $item_target[$typeId]['value'] = 0;
        }
        $item_target[$typeId]['name'] = $name;
        $item_target[$typeId]['value'] += $value;
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
