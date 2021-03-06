<?php

namespace common\models\worksystem\searchs;

use common\models\worksystem\WorksystemContentinfo;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WorksystemContentinfoSearch represents the model behind the search form about `common\models\worksystem\WorksystemContentinfo`.
 */
class WorksystemContentinfoSearch extends WorksystemContentinfo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'worksystem_task_id', 'worksystem_content_id', 'is_new', 'budget_number', 'reality_number', 'index', 'is_delete', 'created_at', 'updated_at'], 'integer'],
            [['price', 'budget_cost', 'reality_cost'], 'number'],
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
        $query = WorksystemContentinfo::find();

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
            'worksystem_task_id' => $this->worksystem_task_id,
            'worksystem_content_id' => $this->worksystem_content_id,
            'is_new' => $this->is_new,
            'price' => $this->price,
            'budget_number' => $this->budget_number,
            'budget_cost' => $this->budget_cost,
            'reality_number' => $this->reality_number,
            'reality_cost' => $this->reality_cost,
            'index' => $this->index,
            'is_delete' => $this->is_delete,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
