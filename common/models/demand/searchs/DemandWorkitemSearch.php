<?php

namespace common\models\demand\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\demand\DemandWorkitem;

/**
 * DemandWorkitemSearch represents the model behind the search form about `common\models\demand\DemandWorkitem`.
 */
class DemandWorkitemSearch extends DemandWorkitem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'demand_task_id', 'workitem_type_id', 'workitem_id', 'is_new', 'value', 'cost', 'created_at', 'updated_at'], 'integer'],
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
        $query = DemandWorkitem::find();

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
            'demand_task_id' => $this->demand_task_id,
            'workitem_type_id' => $this->workitem_type_id,
            'workitem_id' => $this->workitem_id,
            'is_new' => $this->is_new,
            'value' => $this->value,
            'cost' => $this->cost,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
