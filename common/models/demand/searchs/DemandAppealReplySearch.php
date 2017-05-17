<?php

namespace common\models\demand\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\demand\DemandReply;

/**
 * DemandReplySearch represents the model behind the search form about `common\models\demand\DemandReply`.
 */
class DemandReplySearch extends DemandReply
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'demand_appeal_id', 'pass', 'created_at', 'updated_at'], 'integer'],
            [['title', 'des', 'create_by'], 'safe'],
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
        $query = DemandReply::find();

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
            'demand_appeal_id' => $this->demand_appeal_id,
            'pass' => $this->pass,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'des', $this->des])
            ->andFilterWhere(['like', 'create_by', $this->create_by]);

        return $dataProvider;
    }
}
