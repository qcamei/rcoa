<?php

namespace common\models\mconline\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\mconline\McbsCourse;

/**
 * McbsCourseSearch represents the model behind the search form about `common\models\mconline\McbsCourse`.
 */
class McbsCourseSearch extends McbsCourse
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'create_by', 'des'], 'safe'],
            [['item_type_id', 'item_id', 'item_child_id', 'course_id', 'status', 'is_publish', 'publish_time', 'close_time', 'created_at', 'updated_at'], 'integer'],
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
        $query = McbsCourse::find();

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
            'item_type_id' => $this->item_type_id,
            'item_id' => $this->item_id,
            'item_child_id' => $this->item_child_id,
            'course_id' => $this->course_id,
            'status' => $this->status,
            'is_publish' => $this->is_publish,
            'publish_time' => $this->publish_time,
            'close_time' => $this->close_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'create_by', $this->create_by])
            ->andFilterWhere(['like', 'des', $this->des]);

        return $dataProvider;
    }
}
