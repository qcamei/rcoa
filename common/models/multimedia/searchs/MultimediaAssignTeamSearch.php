<?php

namespace common\models\multimedia\searchs;

use common\models\multimedia\MultimediaAssignTeam;
use common\models\team\Team;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * MultimediaAssignTeamSearch represents the model behind the search form about `common\models\multimedia\MultimediaAssignTeam`.
 */
class MultimediaAssignTeamSearch extends MultimediaAssignTeam
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['team_id'], 'integer'],
            [['u_id'], 'safe'],
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
        $query = MultimediaAssignTeam::find()
                ->leftJoin(['Team' => Team::tableName()], 'Team.id = team_id')
                ->orderBy('Team.index asc');

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
            'team_id' => $this->team_id,
        ]);

        $query->andFilterWhere(['like', 'u_id', $this->u_id]);

        return $dataProvider;
    }
}
