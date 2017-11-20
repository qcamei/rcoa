<?php

namespace common\models\mconline\searchs;

use common\models\mconline\McbsCourseBlock;
use common\models\mconline\McbsCoursePhase;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * McbsCourseBlockSearch represents the model behind the search form about `common\models\mconline\McbsCourseBlock`.
 */
class McbsCourseBlockSearch extends McbsCourseBlock
{
    /**
     * 课程id
     * @var string 
     */
    private $course_id;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'phase_id', 'name', 'des'], 'safe'],
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
        
        $query = McbsCourseBlock::find();

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
        
        $query->leftJoin(['CoursePhase'=>McbsCoursePhase::tableName()], 'CoursePhase.id = phase_id');
        
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'course_id' => $this->course_id,
            'phase_id' => $this->phase_id,
            'sort_order' => $this->sort_order,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'des', $this->des]);
  
        $query->orderBy('sort_order');
        
        return $query->asArray()->all();
    }
}