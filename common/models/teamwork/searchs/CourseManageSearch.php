<?php

namespace common\models\teamwork\searchs;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\teamwork\CourseManage;

/**
 * CourseManageOwnerSearch represents the model behind the search form about `common\models\teamwork\CourseManage`.
 */
class CourseManageSearch extends CourseManage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'project_id', 'course_id', 'lession_time', 'created_at', 'progress', 'status'], 'integer'],
            [['teacher', 'create_by', 'plan_start_time', 'plan_end_time', 'real_carry_out', 'des'], 'safe'],
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
        $query = CourseManage::find();

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
            'project_id' => $this->project_id,
            'course_id' => $this->course_id,
            'lession_time' => $this->lession_time,
            'created_at' => $this->created_at,
            'progress' => $this->progress,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'teacher', $this->teacher])
            ->andFilterWhere(['like', 'create_by', $this->create_by])
            ->andFilterWhere(['like', 'plan_start_time', $this->plan_start_time])
            ->andFilterWhere(['like', 'plan_end_time', $this->plan_end_time])
            ->andFilterWhere(['like', 'real_carry_out', $this->real_carry_out])
            ->andFilterWhere(['like', 'des', $this->des]);

        return $dataProvider;
    }
}
