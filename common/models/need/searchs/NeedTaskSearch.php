<?php

namespace common\models\need\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\need\NeedTask;

/**
 * NeedTaskSearch represents the model behind the search form of `common\models\need\NeedTask`.
 */
class NeedTaskSearch extends NeedTask
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'task_name', 'level', 'status', 'is_del', 'save_path', 'des', 'receive_by', 'audit_by', 'created_by'], 'safe'],
            [['company_id', 'business_id', 'layer_id', 'profession_id', 'course_id', 'need_time', 'finish_time', 'created_at', 'updated_at'], 'integer'],
            [['performance_percent', 'plan_content_cost', 'plan_outsourcing_cost', 'reality_content_cost', 'reality_outsourcing_cost'], 'number'],
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
        $query = NeedTask::find();

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
            'company_id' => $this->company_id,
            'business_id' => $this->business_id,
            'layer_id' => $this->layer_id,
            'profession_id' => $this->profession_id,
            'course_id' => $this->course_id,
            'performance_percent' => $this->performance_percent,
            'need_time' => $this->need_time,
            'finish_time' => $this->finish_time,
            'plan_content_cost' => $this->plan_content_cost,
            'plan_outsourcing_cost' => $this->plan_outsourcing_cost,
            'reality_content_cost' => $this->reality_content_cost,
            'reality_outsourcing_cost' => $this->reality_outsourcing_cost,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'task_name', $this->task_name])
            ->andFilterWhere(['like', 'level', $this->level])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'is_del', $this->is_del])
            ->andFilterWhere(['like', 'save_path', $this->save_path])
            ->andFilterWhere(['like', 'des', $this->des])
            ->andFilterWhere(['like', 'receive_by', $this->receive_by])
            ->andFilterWhere(['like', 'audit_by', $this->audit_by])
            ->andFilterWhere(['like', 'created_by', $this->created_by]);

        return $dataProvider;
    }
}
