<?php

namespace common\models\scene\searchs;

use common\models\scene\SceneBook;
use common\models\scene\SceneSite;
use common\models\User;
use wskeee\framework\models\Item;
use wskeee\utils\DateUtil;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * SceneBookSearch represents the model behind the search form about `common\models\scene\SceneBook`.
 */
class SceneBookSearch extends SceneBook
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'date', 'start_time', 'remark', 'teacher_id', 'booker_id', 'created_by'], 'safe'],
            [['site_id', 'time_index', 'status', 'business_id', 'level_id', 'profession_id', 'course_id', 'lession_time', 'content_type', 'shoot_mode', 'is_photograph', 'camera_count', 'is_transfer', 'created_at', 'updated_at', 'ver'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = SceneBook::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'site_id' => $this->site_id,
            'date' => $this->date,
            'time_index' => $this->time_index,
            'status' => $this->status,
            'business_id' => $this->business_id,
            'level_id' => $this->level_id,
            'profession_id' => $this->profession_id,
            'course_id' => $this->course_id,
            'lession_time' => $this->lession_time,
            'content_type' => $this->content_type,
            'shoot_mode' => $this->shoot_mode,
            'is_photograph' => $this->is_photograph,
            'camera_count' => $this->camera_count,
            'is_transfer' => $this->is_transfer,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'ver' => $this->ver,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'start_time', $this->start_time])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'teacher_id', $this->teacher_id])
            ->andFilterWhere(['like', 'booker_id', $this->booker_id])
            ->andFilterWhere(['like', 'created_by', $this->created_by]);

        return $dataProvider;
    }
    
    /**
     * 
     * @param type $se array(start=>周起始时间，end=>周结束时间 )
     * @return array 一周拍摄预约数据
    */
    public  function searchWeek($params) 
    {
        $date = ArrayHelper::getValue($params, 'date', date('Y-m-d'));                  //日期
        $dateStart = ArrayHelper::getValue(DateUtil::getWeekSE($date), 'start');        //开始日期               
        $dateEnd = ArrayHelper::getValue(DateUtil::getWeekSE($date), 'end');            //结束日期
        $site_id = ArrayHelper::getValue($params, 'site_id');                           //场景id
        
        $query = SceneBookSearch::find();
        //添加查询条件
        $query->andFilterWhere(['between', 'date', $dateStart, $dateEnd]);
        $query->andFilterWhere(['site_id' => $site_id]);
        
        //排序
        $query->orderBy(['date' => SORT_DESC, 'time_index' => SORT_DESC]);
       
//        $indexOffsetTimes = [
//            '9 hours',
//            '14 hours',
//            '18 hours',
//        ];
        
        //创建一周空数据
        $weekdatas = [];
        for ($i = 0, $len = 7; $i < $len; $i++) {
            for ($index = 0; $index < 3; $index++) {
                $weekdatas[] = new SceneBookSearch([
                    'id' => md5($site_id + date('Y-m-d', time()) + $index + rand(1,10000)),
                    'site_id' => $site_id,
                    'date' => date('Y-m-d', strtotime($dateStart . ' +' . ($i) . 'days ')),
                    'time_index' => $index,
                ]);
            }
        }
        
        $startIndex = 0;
        foreach ($query->all() as $model) {
            for ($i = $startIndex, $len = count($weekdatas); $i < $len; $i++) {
                if (date('Y/m/d',$weekdatas[$i]->date) == date('Y/m/d',$model->date) 
                   && $weekdatas[$i]->time_index == $model->time_index) 
                {
                    $weekdatas[$i] = $model;
                    $startIndex = $i + 1;
                    break;
                }
            }
        }
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $weekdatas,
            'sort' => [
                'attributes' => ['date', 'time_index'],
            ],
            'pagination' => [
                'pageSize' =>21,
            ],
        ]);
        return $dataProvider;
    }
    
    public function searchBooks($params)
    {
        $query = (new Query())
                ->select(['Book.id', 'Site.name', 'date', 'time_index', 'ItemCourse.name AS course_name',
                    'User.nickname AS booker', 'Book.status'])
                ->from(['Book' => SceneBook::tableName()]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        //关联查询场地名
        $query->leftJoin(['Site' => SceneSite::tableName()], 'Site.id = Book.site_id');
        //关联查询课程名
        $query->leftJoin(['ItemCourse' => Item::tableName()], 'ItemCourse.id = Book.course_id');
        //关联查询预约人
        $query->leftJoin(['User' => User::tableName()], 'User.id = Book.booker_id');
        
        return $dataProvider;
    }
}
