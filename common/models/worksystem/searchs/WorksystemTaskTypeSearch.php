<?php

namespace common\models\worksystem\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\worksystem\WorksystemTaskType;

/**
 * WorksystemTaskTypeSearch represents the model behind the search form about `common\models\worksystem\WorksystemTaskType`.
 */
class WorksystemTaskTypeSearch extends WorksystemTaskType
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'index', 'is_delete', 'created_at', 'updated_at'], 'integer'],
            [['name', 'icon', 'des'], 'safe'],
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
        $query = WorksystemTaskType::find();

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
            'is_delete' => $this->is_delete,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'icon', $this->icon])
            ->andFilterWhere(['like', 'des', $this->des]);

        return $dataProvider;
    }
}
