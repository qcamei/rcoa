<?php

namespace common\models\workitem\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\workitem\WorkitemCabinet;

/**
 * WorkitemCabinetSearch represents the model behind the search form about `common\models\workitem\WorkitemCabinet`.
 */
class WorkitemCabinetSearch extends WorkitemCabinet
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'workitem_id', 'index'], 'integer'],
            [['name', 'title', 'type', 'path', 'content', 'is_deleted'], 'safe'],
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
        $query = WorkitemCabinet::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);
        
        var_dump($params);
        var_dump($this->workitem_id);exit;

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'workitem_id' => $this->workitem_id,
            'is_delete' => 'N',
        ]);
        
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
