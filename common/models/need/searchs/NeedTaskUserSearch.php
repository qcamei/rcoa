<?php

namespace common\models\need\searchs;

use common\models\need\NeedTaskUser;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * NeedTaskUserSearch represents the model behind the search form of `common\models\need\NeedTaskUser`.
 */
class NeedTaskUserSearch extends NeedTaskUser
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'created_at', 'updated_at'], 'integer'],
            [['user_id', 'need_task_id', 'privilege', 'is_del'], 'safe'],
            [['performance_percent'], 'number'],
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
        $this->need_task_id = ArrayHelper::getValue($params, 'need_task_id');
        
        $query = NeedTaskUser::find();

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
            'user_id' => $this->user_id,
            'need_task_id' => $this->need_task_id,
            'performance_percent' => $this->performance_percent,
            'privilege' => $this->privilege,
            'is_del' => 0,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        
        $query->orderBy(['privilege' => SORT_DESC]);
        
        return $dataProvider;
    }
}
