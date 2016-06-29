<?php
namespace common\models\teamwork\searchs;

use common\models\teamwork\PhaseLink;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * PhaseLinkSearch represents the model behind the search form about `wskeee\framework\models\PhaseLink`.
 */
class PhaseLinkSearch extends PhaseLink
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['phases_id', 'link_id', 'progress'], 'integer'],
            [['total', 'completed'], 'number'],
            [['create_by'], 'safe'],
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
        $query = PhaseLink::find();

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
            'phases_id' => $this->phases_id,
            'link_id' => $this->link_id,
            'total' => $this->total,
            'completed' => $this->completed,
            'progress' => $this->progress,
        ]);

        $query->andFilterWhere(['like', 'create_by', $this->create_by]);

        return $dataProvider;
    }
}
