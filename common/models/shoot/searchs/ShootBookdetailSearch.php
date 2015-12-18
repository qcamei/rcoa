<?php

namespace common\models\shoot\searchs;

use common\models\shoot\ShootBookdetail;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ShootBookdetailSearch represents the model behind the search form about `common\models\shoot\ShootBookdetail`.
 */
class ShootBookdetailSearch extends ShootBookdetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'fw_college', 'fw_project', 'fw_course', 'lession_time', 'u_teacher', 'u_contacter', 'u_booker', 'book_time', 'index', 'shoot_mode', 'photograph', 'status', 'created_at', 'updated_at', 'ver'], 'integer'],
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
        $query = ShootBookdetail::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'fw_college' => $this->fw_college,
            'fw_project' => $this->fw_project,
            'fw_course' => $this->fw_course,
            'lession_time' => $this->lession_time,
            'u_teacher' => $this->teacher,
            'u_contacter' => $this->contacter,
            'u_booker' => $this->booker,
            'book_time' => $this->book_time,
            'index' => $this->index,
            'shoot_mode' => $this->shoot_mode,
            'photograph' => $this->photograph,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'ver' => $this->ver,
        ]);

        return $dataProvider;
    }
    /**
     * 
     * @param type $se array(start=>周起始时间，end=>周结束时间 )
     * @return array 一周拍摄预约数据
    */
   public static function searchWeek($site, $se) {
        $dataProvider = ShootBookdetailSearch::find()
                ->where('book_time >= ' . strtotime($se['start']))
                ->andWhere('book_time <= ' . strtotime($se['end']))
                ->andWhere('site_id = ' . $site)
                ->orderBy('book_time')
                ->with('teacher')
                ->with('contacter')
                ->with('booker')
                ->with('shootMan')
                ->with('appraises')
                ->with('appraiseResults')
                ->all();

        $indexOffsetTimes = [
            '9 hours',
            '14 hours',
            '18 hours',
        ];
        //创建一周空数据
        $weekdatas = [];
        for ($i = 0, $len = 7; $i < $len; $i++) {
            for ($index = 0; $index < 3; $index++) {
                $weekdatas[] = new ShootBookdetailSearch([
                    'site_id' => $site,
                    'book_time' => strtotime($se['start'] . ' +' . ($i) . 'days ' . $indexOffsetTimes[$index]),
                    'index' => $index,
                ]);
            }
        };
        $startIndex = 0;
        foreach ($dataProvider as $model) {
            for ($i = $startIndex, $len = count($weekdatas); $i < $len; $i++) {
                if ($weekdatas[$i]->book_time == $model->book_time) {
                    $weekdatas[$i] = $model;
                    $startIndex = $i + 1;
                    break;
                }
            }
        }
        return $weekdatas;
    }
   
}
