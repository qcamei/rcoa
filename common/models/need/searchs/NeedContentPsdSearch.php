<?php

namespace common\models\need\searchs;

use common\models\need\NeedContentPsd;
use common\models\workitem\Workitem;
use common\models\workitem\WorkitemType;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * NeedContentPsdSearch represents the model behind the search form of `common\models\need\NeedContentPsd`.
 */
class NeedContentPsdSearch extends NeedContentPsd
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'workitem_type_id', 'workitem_id', 'created_at', 'updated_at'], 'integer'],
            [['price_new', 'price_remould'], 'number'],
            [['sort_order', 'is_del'], 'safe'],
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
        $query = (new \yii\db\Query())
                ->select(['NeedContentPsd.id', 'WorkitemType.name AS type_name', 'Workitem.name AS workitem_name', 'price_new',
                    'price_remould', 'sort_order', 'is_del', 'Workitem.unit', 'NeedContentPsd.created_at',
                    'NeedContentPsd.updated_at'])
                ->from(['NeedContentPsd' => NeedContentPsd::tableName()]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => 'id',
        ]);
        
        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        $query->leftJoin(['WorkitemType' => WorkitemType::tableName()], 'WorkitemType.id = NeedContentPsd.workitem_type_id');
        $query->leftJoin(['Workitem' => Workitem::tableName()], 'Workitem.id = NeedContentPsd.workitem_id'); 
        
        // grid filtering conditions
        $query->andFilterWhere([
            'workitem_type_id' => $this->workitem_type_id,
            'workitem_id' => $this->workitem_id,
            'is_del' => $this->is_del,
        ]);

        return $dataProvider;
    }
}
