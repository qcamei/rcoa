<?php

namespace common\models\scene\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\scene\SceneBook;

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
}
