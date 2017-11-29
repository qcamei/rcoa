<?php

namespace common\models\mconline\searchs;

use common\models\mconline\McbsCourseBlock;
use common\models\mconline\McbsCourseChapter;
use common\models\mconline\McbsCoursePhase;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * McbsCourseChapterSearch represents the model behind the search form about `common\models\mconline\McbsCourseChapter`.
 */
class McbsCourseChapterSearch extends McbsCourseChapter
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
            [['id', 'block_id', 'name', 'des'], 'safe'],
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
        
        $query = McbsCourseChapter::find()->from(['CourseChapter'=> McbsCourseChapter::tableName()]);

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

        $query->leftJoin(['CourseBlock'=>McbsCourseBlock::tableName()], '(CourseBlock.id = block_id AND CourseBlock.is_del = 0)');
        $query->leftJoin(['CoursePhase'=>McbsCoursePhase::tableName()], '(CoursePhase.id = CourseBlock.phase_id AND CoursePhase.is_del = 0)');
        
        // grid filtering conditions
        $query->where(['CourseChapter.is_del' => 0]);
        $query->andFilterWhere([
            'id' => $this->id,
            'course_id' => $this->course_id,
            'block_id' => $this->block_id,
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