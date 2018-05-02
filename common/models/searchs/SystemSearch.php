<?php

namespace common\models\searchs;

use common\models\System;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SystemSearch represents the model behind the search form about `common\models\System`.
 */
class SystemSearch extends System
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'isjump'], 'integer'],
            [['name', 'module_image', 'module_link', 'des', 'aliases'], 'safe'],
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
        $query = System::find()->where(['is_delete' => 'N'])->orderBy('index asc');

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
            'isjump' => $this->isjump,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'module_image', $this->module_image])
            ->andFilterWhere(['like', 'module_link', $this->module_link])
            ->andFilterWhere(['like', 'des', $this->des])
            ->andFilterWhere(['like', 'aliases', $this->aliases]);

        return $dataProvider;
    }
}