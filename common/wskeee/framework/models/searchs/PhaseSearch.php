<?php

namespace wskeee\framework\models\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use wskeee\framework\models\Phase;

/**
 * PhaseSearch represents the model behind the search form about `wskeee\framework\models\Phase`.
 */
class PhaseSearch extends Phase
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'progress'], 'integer'],
            [['name', 'create_by'], 'safe'],
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
            'weights' => $this->weights,
            'progress' => $this->progress,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'create_by', $this->create_by]);

        return $dataProvider;
    }
}
