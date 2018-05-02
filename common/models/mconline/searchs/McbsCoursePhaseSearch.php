<?php

namespace common\models\mconline\searchs;

use common\models\mconline\McbsCoursePhase;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * McbsCoursePhaseSearch represents the model behind the search form about `common\models\mconline\McbsCoursePhase`.
 */
class McbsCoursePhaseSearch extends McbsCoursePhase
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'course_id', 'name', 'des'], 'safe'],
            [['value_percent'], 'number'],
            [['sort_order', 'created_at', 'updated_at'], 'integer'],
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
        $this->course_id = ArrayHelper::getValue($params, 'course_id');
        
        $query = McbsCoursePhase::find();

        // add conditions that should always apply here

        /*$dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);*/

        $this->load($params);
        
        /*if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }*/

        // grid filtering conditions
        $query->where(['is_del' => 0]);
        $query->andFilterWhere([
            'id' => $this->id,
            'course_id' => $this->course_id,
            'value_percent' => $this->value_percent,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);
        
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'des', $this->des]);
        
        $query->orderBy(['sort_order'=>SORT_ASC]);

        return $query->asArray()->all();
    }
}
