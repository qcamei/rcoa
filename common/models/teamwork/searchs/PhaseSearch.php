<?php

namespace common\models\teamwork\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\teamwork\Phase;

/**
 * PhaseSearch represents the model behind the search form about `common\models\teamwork\Phase`.
 */
class PhaseSearch extends Phase
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'template_type_id', 'created_at', 'updated_at', 'index'], 'integer'],
            [['name', 'create_by', 'is_delete'], 'safe'],
            [['weights'], 'number'],
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
        $query = Phase::find();

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
            'template_type_id' => $this->template_type_id,
            'weights' => $this->weights,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'index' => $this->index,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'create_by', $this->create_by])
            ->andFilterWhere(['like', 'is_delete', $this->is_delete]);

        return $dataProvider;
    }
}
