<?php

namespace common\models\scene\searchs;

use common\models\scene\SceneAppraise;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * SceneAppraiseSearch represents the model behind the search form about `common\models\scene\SceneAppraise`.
 */
class SceneAppraiseSearch extends SceneAppraise
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'role', 'q_id', 'value', 'index', 'created_at', 'updated_at'], 'integer'],
            [['book_id'], 'safe'],
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
        $this->book_id = ArrayHelper::getValue($params, 'book_id');
        $this->role = ArrayHelper::getValue($params, 'role');
        $this->user_id = ArrayHelper::getValue($params, 'user_id');
        
        $query = SceneAppraise::find();

//        // add conditions that should always apply here
//
//        $dataProvider = new ActiveDataProvider([
//            'query' => $query,
//        ]);
//
//        $this->load($params);
//
//        if (!$this->validate()) {
//            // uncomment the following line if you do not want to return any records when validation fails
//            // $query->where('0=1');
//            return $dataProvider;
//        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'book_id' => $this->book_id,
            'role' => $this->role,
            'q_id' => $this->q_id,
            'value' => $this->value,
            'index' => $this->index,
            'user_id' => $this->user_id,
            'user_value' => $this->user_value,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'user_data', $this->user_data]);
        
        $query->orderBy(['role' => SORT_DESC, 'index' => SORT_ASC]);

        $results = [];
        foreach ($query->all() as $value) {
            /* @var $value SceneAppraise */
            $results[$value->role][$value->q_id] = $value;
        }
        
        return $results;
    }
}
