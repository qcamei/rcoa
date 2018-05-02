<?php

namespace common\models\need\searchs;

use common\models\need\NeedContent;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * NeedContentSearch represents the model behind the search form of `common\models\need\NeedContent`.
 */
class NeedContentSearch extends NeedContent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'workitem_type_id', 'workitem_id', 'plan_num', 'reality_num', 'created_at', 'updated_at'], 'integer'],
            [['need_task_id', 'is_new', 'sort_order', 'is_del', 'created_by'], 'safe'],
            [['price'], 'number'],
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
        
        $query = NeedContent::find();

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
            'need_task_id' => $this->need_task_id,
            'workitem_type_id' => $this->workitem_type_id,
            'workitem_id' => $this->workitem_id,
            'is_new' => $this->is_new,
            'price' => $this->price,
            'plan_num' => $this->plan_num,
            'reality_num' => $this->reality_num,
            'is_del' => 0,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->orderBy(['sort_order' => SORT_ASC, 'is_new' => SORT_ASC]);

        return $dataProvider;
    }
}
