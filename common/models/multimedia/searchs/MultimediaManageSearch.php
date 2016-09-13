<?php

namespace common\models\multimedia\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\multimedia\MultimediaManage;

/**
 * MultimediaManageSearch represents the model behind the search form about `common\models\multimedia\MultimediaManage`.
 */
class MultimediaManageSearch extends MultimediaManage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'item_type_id', 'item_id', 'item_child_id', 'course_id', 'video_length', 'progress', 'content_type', 'level', 'make_team', 'status', 'create_team', 'created_at', 'updated_at'], 'integer'],
            [['name', 'carry_out_time', 'path', 'create_by', 'des'], 'safe'],
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
        $query = MultimediaManage::find();

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
            'item_type_id' => $this->item_type_id,
            'item_id' => $this->item_id,
            'item_child_id' => $this->item_child_id,
            'course_id' => $this->course_id,
            'video_length' => $this->video_length,
            'progress' => $this->progress,
            'proportion' => $this->proportion,
            'content_type' => $this->content_type,
            'level' => $this->level,
            'make_team' => $this->make_team,
            'status' => $this->status,
            'create_team' => $this->create_team,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'carry_out_time', $this->carry_out_time])
            ->andFilterWhere(['like', 'path', $this->path])
            ->andFilterWhere(['like', 'create_by', $this->create_by])
            ->andFilterWhere(['like', 'des', $this->des]);

        return $dataProvider;
    }
}
