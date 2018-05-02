<?php

namespace common\models\team\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\team\TeamMember;

/**
 * TeamMemberSearch represents the model behind the search form about `common\models\team\TeamMember`.
 */
class TeamMemberSearch extends TeamMember
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['team_id'], 'integer'],
            [['u_id', 'role_name', 'is_leader', 'is_delete'], 'safe'],
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
        $query = TeamMember::find()->where(['!=', 'is_delete', 'Y']);

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
            'team_id' => $this->team_id,
        ]);

        $query->andFilterWhere(['like', 'u_id', $this->u_id])
            ->andFilterWhere(['like', 'role_name', $this->role_name])
            ->andFilterWhere(['like', 'is_leader', $this->is_leader]);

        return $dataProvider;
    }
}
