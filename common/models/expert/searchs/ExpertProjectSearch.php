<?php

namespace common\models\expert\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\expert\ExpertProject;

/**
 * ExpertProjectSearch represents the model behind the search form about `common\models\expert\ExpertProject`.
 */
class ExpertProjectSearch extends ExpertProject
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'expert_id', 'project_id', 'start_time', 'end_time', 'cost', 'compatibility'], 'integer'],
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
        $query = ExpertProject::find();

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
            'expert_id' => $this->expert_id,
            'project_id' => $this->project_id,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'cost' => $this->cost,
            'compatibility' => $this->compatibility,
        ]);

        return $dataProvider;
    }
}
