<?php

namespace common\models\expert\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\expert\Expert;

/**
 * ExpertSearch represents the model behind the search form about `common\models\expert\Expert`.
 */
class ExpertSearch extends Expert
{
    public $name;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'],'string'],
            [['u_id', 'type', 'birth'], 'integer'],
            [['job_title', 'job_name', 'level', 'employer', 'attainment'], 'safe'],
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
        $query = Expert::find();
        $query->joinWith(['user'])
            ->with('expertType');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        $dataProvider->setSort([
            'attributes' => [
                'name' => [
                    'asc' => ['user.nickname' => SORT_ASC],
                    'desc' => ['user.nickname' => SORT_DESC],
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->u_id,
            'u_id' => $this->u_id,
            'type' => $this->type,
            'birth' => $this->birth,
        ]);

        $query->andFilterWhere(['like', 'job_title', $this->job_title])
            ->andFilterWhere(['like', 'job_name', $this->job_name])
            ->andFilterWhere(['like', 'level', $this->level])
            ->andFilterWhere(['like', 'employer', $this->employer])
            ->andFilterWhere(['like', 'attainment', $this->attainment])
            ->andFilterWhere(['like', 'nickname', $this->name]);

        return $dataProvider;
    }
}
