<?php

namespace frontend\modules\teamwork\controllers;

use common\models\team\Team;
use common\models\teamwork\CourseManage;
use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\TeamworkTool;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class StatisticsController extends Controller
{
    public function actionIndex()
    {
        $post = Yii::$app->getRequest()->getQueryParams();
        $dateRange = isset($post['dateRange']) ? $post['dateRange'] : null;
        $team = isset($post['team']) ? $post['team'] : null;
        $status = isset($post['status'])? $post['status'] : null;
        /** 按类型分类 */
        $itemTyps = (new Query())
                    ->select(['ItemType.name',
                            'SUM(Course.lession_time) AS value'])
                    ->from(['Course'=>CourseManage::tableName()])
                    ->leftJoin(['Item'=>ItemManage::tableName()], 'Course.project_id = Item.id')
                    ->leftJoin(['ItemType'=>  ItemType::tableName()],'Item.item_type_id = ItemType.id')
                    ->andFilterWhere(['Course.status'=>$status])
                    ->andFilterWhere(['Course.`team_id`'=>$team])
                    ->groupBy('Item.item_type_id');
         /** 按项目分类 */
        $items = (new Query())
                    ->select(['FwItem.name',
                            'SUM(Course.lession_time) AS value'])
                    ->from(['Course'=>CourseManage::tableName()])
                    ->leftJoin(['Item'=>ItemManage::tableName()], 'Course.project_id = Item.id')
                    ->leftJoin(['FwItem'=> Item::tableName()],'Item.item_id = FwItem.id')
                     ->andFilterWhere(['Course.status'=>$status])
                    ->andFilterWhere(['Course.`team_id`'=>$team])
                    ->groupBy('Item.item_id');
        /** 按子项目分类 */
        $itemChilds = (new Query())
                    ->select(['FwItem.name',
                            'SUM(Course.lession_time) AS value'])
                    ->from(['Course'=>CourseManage::tableName()])
                    ->leftJoin(['Item'=>ItemManage::tableName()], 'Course.project_id = Item.id')
                    ->leftJoin(['FwItem'=> Item::tableName()],'Item.item_child_id = FwItem.id')
                    ->andFilterWhere(['Course.status'=>$status])
                    ->andFilterWhere(['Course.`team_id`'=>$team])
                    ->groupBy('Item.item_child_id');
        /** 按团队分类 */
        $teams = (new Query())
                    ->select(['Team.name',
                            'SUM(Course.lession_time) AS value'])
                    ->from(['Team'=> Team::tableName()])
                    ->leftJoin(['Course'=>CourseManage::tableName()],'Course.team_id = Team.id')
                    ->leftJoin(['Item'=>ItemManage::tableName()], 'Course.project_id = Item.id')
                    ->andFilterWhere(['Course.status'=>$status])
                    ->andFilterWhere(['Course.`team_id`'=>$team])
                    ->groupBy('Team.id');
        if($dateRange && $status!=ItemManage::STATUS_NORMAL)
        {
            $dateRange_Arr = explode(" - ",$dateRange);
            $date1 = $dateRange_Arr[0];
            $date2 = $dateRange_Arr[1];

            $itemTyps   ->andFilterWhere(['between','Course.real_carry_out',$date1,$date2]);
            /** 按项目分类 */
            $items      ->andFilterWhere(['between','Course.real_carry_out',$date1,$date2]);
            /** 按子项目分类 */
            $itemChilds ->andFilterWhere(['between','Course.real_carry_out',$date1,$date2]);
            /** 按团队分类 */
            $teams      ->andFilterWhere(['between','Course.real_carry_out',$date1,$date2]);
        }
        
        $model = new ItemManage();
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $teamIds = Team::find()
                    ->select(['id','name'])
                    ->asArray()
                    ->all();
        $teamIds = ArrayHelper::map($teamIds, 'id', 'name');
        
        return $this->render('index',[
            'dateRange'=>$dateRange,
            'team'=>$team,
            'status'=>$status,
            'teamIds'=>$teamIds,
            'model'=>$model,
            'twTool'=>$twTool,
            'itemTypes'=>$itemTyps->all(Yii::$app->db),
            'items'=>$items->all(Yii::$app->db),
            'itemChilds'=>$itemChilds->all(Yii::$app->db),
            'teams'=>$teams->all(Yii::$app->db)
        ]);
    }
}
