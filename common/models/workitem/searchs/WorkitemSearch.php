<?php

namespace common\models\workitem\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\workitem\Workitem;

/**
 * WorkitemSearch represents the model behind the search form about `common\models\workitem\Workitem`.
 */
class WorkitemSearch extends Workitem
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'index', 'created_at', 'updated_at'], 'integer'],
            [['name', 'unit', 'des'], 'safe'],
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
        $query = Workitem::find();

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
            'index' => $this->index,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'unit', $this->unit])
            ->andFilterWhere(['like', 'des', $this->des]);

        return $dataProvider;
    }
}
