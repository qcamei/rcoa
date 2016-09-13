<?php

namespace common\models\multimedia\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\multimedia\MultimediaProportion;

/**
 * MultimediaProportionSearch represents the model behind the search form about `common\models\multimedia\MultimediaProportion`.
 */
class MultimediaProportionSearch extends MultimediaProportion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name_type', 'des'], 'safe'],
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
        $query = MultimediaProportion::find();

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
            'proportion' => $this->proportion,
        ]);

        $query->andFilterWhere(['like', 'name_type', $this->name_type])
            ->andFilterWhere(['like', 'des', $this->des]);

        return $dataProvider;
    }
}
