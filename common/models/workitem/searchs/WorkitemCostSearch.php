<?php

namespace common\models\workitem\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\workitem\WorkitemCost;

/**
 * WorkitemCostSearch represents the model behind the search form about `common\models\workitem\WorkitemCost`.
 */
class WorkitemCostSearch extends WorkitemCost
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'workitem_id', 'cost_new', 'cost_remould', 'created_at', 'updated_at'], 'integer'],
            [['target_month'], 'safe'],
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
        $query = WorkitemCost::find();

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
            'workitem_id' => $this->workitem_id,
            'cost_new' => $this->cost_new,
            'cost_remould' => $this->cost_remould,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'target_month', $this->target_month]);

        return $dataProvider;
    }
}
