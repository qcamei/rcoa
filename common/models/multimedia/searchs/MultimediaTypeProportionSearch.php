<?php

namespace common\models\multimedia\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\multimedia\MultimediaTypeProportion;

/**
 * MultimediaTypeProportionSearch represents the model behind the search form about `common\models\multimedia\MultimediaTypeProportion`.
 */
class MultimediaTypeProportionSearch extends MultimediaTypeProportion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'content_type', 'created_at', 'updated_at'], 'integer'],
            [['proportion'], 'number'],
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
        $query = MultimediaTypeProportion::find();

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
            'content_type' => $this->content_type,
            'proportion' => $this->proportion,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }
}
