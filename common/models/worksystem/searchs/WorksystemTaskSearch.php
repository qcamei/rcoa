<?php

namespace common\models\worksystem\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\worksystem\WorksystemTask;

/**
 * WorksystemTaskSearch represents the model behind the search form about `common\models\worksystem\WorksystemTask`.
 */
class WorksystemTaskSearch extends WorksystemTask
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'item_type_id', 'item_id', 'item_child_id', 'course_id', 'task_type_id', 'level', 'is_brace', 'is_epiboly', 'external_team', 'status', 'progress', 'create_team', 'index', 'is_delete', 'created_at', 'updated_at', 'finished_at'], 'integer'],
            [['name', 'plan_end_time', 'create_by', 'des'], 'safe'],
            [['budget_cost', 'reality_cost', 'budget_bonus', 'reality_bonus'], 'number'],
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
        $query = WorksystemTask::find();

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
            'task_type_id' => $this->task_type_id,
            'level' => $this->level,
            'is_epiboly' => $this->is_brace,
            'is_epiboly' => $this->is_epiboly,
            'budget_cost' => $this->budget_cost,
            'reality_cost' => $this->reality_cost,
            'budget_bonus' => $this->budget_bonus,
            'reality_bonus' => $this->reality_bonus,
            'external_team' => $this->external_team,
            'status' => $this->status,
            'progress' => $this->progress,
            'create_team' => $this->create_team,
            'index' => $this->index,
            'is_delete' => $this->is_delete,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'finished_at' => $this->finished_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'plan_end_time', $this->plan_end_time])
            ->andFilterWhere(['like', 'create_by', $this->create_by])
            ->andFilterWhere(['like', 'des', $this->des]);

        return $dataProvider;
    }
}
