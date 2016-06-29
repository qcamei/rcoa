<?php

namespace common\models\teamwork\searchs;

use common\models\teamwork\ItemManage;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ItemManageSearch represents the model behind the search form about `wskeee\framework\models\ItemManage`.
 */
class ItemManageSearch extends ItemManage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'item_type_id', 'item_id', 'item_child_id', 'created_at', 'progress', 'status'], 'integer'],
            [['create_by', 'forecast_time', 'real_carry_out', 'background', 'use'], 'safe'],
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
        $query = ItemManage::find();

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
            'item_type_id' => $this->item_type_id,
            'item_id' => $this->item_id,
            'item_child_id' => $this->item_child_id,
            'created_at' => $this->created_at,
            'progress' => $this->progress,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'create_by', $this->create_by])
            ->andFilterWhere(['like', 'forecast_time', $this->forecast_time])
            ->andFilterWhere(['like', 'real_carry_out', $this->real_carry_out])
            ->andFilterWhere(['like', 'background', $this->background])
            ->andFilterWhere(['like', 'use', $this->use]);

        return $dataProvider;
    }
    
    /*public static function searchData()
    {
        $model = new ItemManage();
        $dataProvider = ItemManageSearch::find()
                        ->where([
                            'item_type_id' => $model->item_type_id,
                            'item_id' => $model->item_id,
                            'item_child_id' => $model->item_child_id,
                            'progress' => $model->progress,
                        ]);
        return $dataProvider;
    }*/
}
