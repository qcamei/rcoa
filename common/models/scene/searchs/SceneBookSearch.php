<?php

namespace common\models\scene\searchs;

use common\models\Holiday;
use common\models\scene\SceneBook;
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
     * 开始日期
     * @var string 
     */
    private $date_start;
    /**
     * 结束日期
     * @var string 
     */
    private $date_end;
    
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
        $this->date = ArrayHelper::getValue($params, 'date');                           //时间段
        $notStatus = [SceneBook::STATUS_DEFAULT, SceneBook::STATUS_BOOKING];            //未预约和预约中
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
            'id' => $this->id,
            'site_id' => $this->site_id,
            'time_index' => $this->time_index,
            //'status' => $this->status,
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
        
        //按时间段搜索
        if($this->date != null){
            $this->date = explode(" - ", $this->date);
            $query->andFilterWhere(['between', 'date', $this->date[0], $this->date[1]]);
        }
        //过滤未预约和预约中的数据
        $query->andFilterWhere(['NOT IN', 'status', $notStatus]);

        $query->andFilterWhere(['start_time' => $this->start_time])
            ->andFilterWhere(['teacher_id' => $this->teacher_id])
            ->andFilterWhere(['booker_id' => $this->booker_id])
            ->andFilterWhere(['created_by' => $this->created_by]);
        
        $query->andFilterWhere(['like', 'remark', $this->remark]);
        
        return $dataProvider;
    }
    
    
    /**
     * 
     * @param type $se array(start=>周起始时间，end=>周结束时间 )
     * @return array 一周拍摄预约数据
    */
    public function searchModel($params, $firstSite)
    {
        $holidays = [];
        $this->date = ArrayHelper::getValue($params, 'date', date('Y-m-d'));                         //日期
        $this->date_switch = ArrayHelper::getValue($params, 'date_switch', 'month');                 //月 or 周
        $hasDo = $this->date_switch == 'month';
        $date = $hasDo ? DateUtil::getMonthSE($this->date) : DateUtil::getWeekSE($this->date);
        $this->site_id = ArrayHelper::getValue($params, 'site_id', reset($firstSite));              //场景id
        $this->date_start = ArrayHelper::getValue($date, 'start');                                  //开始日期               
        $this->date_end = ArrayHelper::getValue($date, 'end');                                      //结束日期
        //查询预约任务数据
        $results = $this->searchSceneBook();
        //创建空的日期数据
        $dateDatas = $hasDo ? $this->searchMonth() : $this->searchWeek();
        //获取节假日
        if($hasDo){
            $date = date('Y-m', strtotime($this->date));
            $firstSunday = date('Y-m-d', strtotime("first sunday of $date"));
            //当前月最后一个星期日是几号
            $lastSunday = date('Y-m-d', strtotime("last sunday of $date"));
            //当前月第一个星期日是往前7天的日期
            $this->date_start = date('Y-m-d', strtotime(date('Y-m-d', strtotime("$firstSunday -".(7).' days'))));
            //当前月最后一个星期日是往后7天的日期
            $this->date_end = date('Y-m-d', strtotime(date('Y-m-d', strtotime("$lastSunday +".(7).' days'))));
        }
        $holidayBetweens = Holiday::getHolidayBetween($this->date_start, $this->date_end);
        
        //预约数据组装
        $startIndex = 0;
        foreach ($results as $model) {
            for ($i = $startIndex, $len = count($dateDatas); $i < $len; $i++) {
                if ($dateDatas[$i]->date === $model->date && $dateDatas[$i]->time_index === $model->time_index){
                    $dateDatas[$i] = $model;
                    $startIndex = $i + 1;
                    break;
                }
            }
        }
        //重组组装节假日
        ArrayHelper::multisort($holidayBetweens, 'type');   //按照节假日类型升序排列
        foreach($holidayBetweens as $holiday){
            $date = date('Y-m-d', strtotime($holiday['date']));
            $holidays[$date][$holiday['type']][] = [
                'name' => $holiday['name'],
                'type' => $holiday['type'],
                'des' => $holiday['des'],
                'is_lunar' => $holiday['is_lunar'],
            ];
        }
        
        //预约数据格式
        $dataProvider = new ArrayDataProvider([
            'allModels' => $dateDatas,
            'sort' => [
                'attributes' => ['date', 'time_index'],
            ],
            'pagination' => [
                'pageSize' =>21,
            ],
        ]);
        
        return [
            'filters' => $params,
            'holidays' => $holidays,
            'data' => $dataProvider,
        ];
    }
    
    /**
     * 一个月拍摄预约数据     
     * @return SceneBookSearch
     */
    private function searchMonth()
    {
        //创建一个月空数据
        $monthdatas = [];
        //当前月从星期几天始
        $weekStart = date('w', mktime(0, 0, 0, date('m', strtotime($this->date_start)), date('d', strtotime($this->date_start)), date('Y', strtotime($this->date_start))));                        
        //当前月有多少天
        $dayNum = date('t', mktime(0, 0, 0, date('m', strtotime($this->date_end)), date('d', strtotime($this->date_end)), date('Y', strtotime($this->date_end))));            
        $mday = 1;          //第几天
        for ($i = 0, $len = ceil(($weekStart + $dayNum) / 7); $i < $len; $i++){
            for($d = 0;  $d < 7; $d++){
                $nowDay = 7 * $i + $d + 0;
                if($nowDay >= $weekStart && $mday <= $dayNum){
                    for ($index = 0; $index < 3; $index++){
                        $monthdatas[] = new SceneBookSearch([
                            'id' => md5($this->site_id . date('Y-m-d', strtotime(date('Y-m', strtotime($this->date_start)).'-'.$mday)) . $index . rand(1,10000)),
                            'site_id' => $this->site_id,
                            'date' => date('Y-m-d', strtotime(date('Y-m', strtotime($this->date_start)).'-'.$mday)),
                            'time_index' => $index,
                            'date_switch' => $this->date_switch,
                        ]);
                    }
                    $mday++;
                }
            }
        }
        
        return $monthdatas;
    }

    /**
     * 一周拍摄预约数据     
     * @return SceneBookSearch
     */
    private function searchWeek()
    {
        
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
                    'id' => md5($this->site_id . date('Y-m-d', strtotime($this->date_start . ' +' . ($i) . 'days ')) . $index . rand(1,10000)),
                    'site_id' => $this->site_id,
                    'date' => date('Y-m-d', strtotime($this->date_start . ' +' . ($i) . 'days ')),
                    'time_index' => $index,
                    'date_switch' => $this->date_switch,
                ]);
            }
        }
        
        return $weekdatas;
    }
    
    /**
     * 查询预约任务数据
     * @return Query
     */
    private function searchSceneBook() 
    {
        $statusMap = [SceneBook::STATUS_DEFAULT, SceneBook::STATUS_CANCEL];
        $query = SceneBookSearch::find();
        //添加查询条件
        $query->andFilterWhere(['between', 'date', $this->date_start, $this->date_end]);
        $query->andFilterWhere(['site_id' => $this->site_id]);
        $query->andFilterWhere(['NOT IN', 'status', $statusMap]);
        $query->with('business', 'level', 'profession', 'course');
        $query->with('sceneSite', 'booker', 'createdBy', 'teacher');
        //排序
        $query->orderBy(['date' => SORT_ASC, 'time_index' => SORT_ASC]);
        
        return $query->all();
    }
    
}
