<?php

namespace common\models\demand\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\demand\DemandTask;

/**
 * DemandTaskSearch represents the model behind the search form about `common\models\demand\DemandTask`.
 */
class DemandTaskSearch extends DemandTask
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'item_type_id', 'item_id', 'item_child_id', 'course_id', 'lesson_time', 'credit', 'mode', 'team_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['teacher', 'course_description', 'undertake_person', 'plan_check_harvest_time', 'reality_check_harvest_time', 'create_by', 'des'], 'safe'],
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
        $query = DemandTask::find();

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
            'item_type_id' => $this->item_type_id,
            'item_id' => $this->item_id,
            'item_child_id' => $this->item_child_id,
            'course_id' => $this->course_id,
            'lesson_time' => $this->lesson_time,
            'credit' => $this->credit,
            'mode' => $this->mode,
            'team_id' => $this->team_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'teacher', $this->teacher])
            ->andFilterWhere(['like', 'course_description', $this->course_description])
            ->andFilterWhere(['like', 'undertake_person', $this->undertake_person])
            ->andFilterWhere(['like', 'plan_check_harvest_time', $this->plan_check_harvest_time])
            ->andFilterWhere(['like', 'reality_check_harvest_time', $this->reality_check_harvest_time])
            ->andFilterWhere(['like', 'create_by', $this->create_by])
            ->andFilterWhere(['like', 'des', $this->des]);

        return $dataProvider;
    }
}
