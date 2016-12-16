<?php

namespace common\models\demand\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\demand\DemandAcceptance;

/**
 * DemandAcceptanceSearch represents the model behind the search form about `common\models\demand\DemandAcceptance`.
 */
class DemandAcceptanceSearch extends DemandAcceptance
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'task_id', 'created_at', 'updated_at', 'status'], 'integer'],
            [['title', 'remark', 'create_by', 'complete_time'], 'safe'],
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
        $query = DemandAcceptance::find();

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
            'task_id' => $this->task_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'create_by', $this->create_by])
            ->andFilterWhere(['like', 'complete_time', $this->complete_time]);

        return $dataProvider;
    }
}
