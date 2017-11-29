<?php

namespace common\models\mconline\searchs;

use common\models\mconline\McbsActivityType;
use common\models\mconline\McbsCourseActivity;
use common\models\mconline\McbsCourseBlock;
use common\models\mconline\McbsCourseChapter;
use common\models\mconline\McbsCoursePhase;
use common\models\mconline\McbsCourseSection;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * McbsCourseActivitySearch represents the model behind the search form about `common\models\mconline\McbsCourseActivity`.
 */
class McbsCourseActivitySearch extends McbsCourseActivity
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
            [['id', 'section_id', 'name', 'des'], 'safe'],
            [['type_id', 'sort_order', 'created_at', 'updated_at'], 'integer'],
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
        
        $query = McbsCourseActivity::find()
                ->select(['CourseActivity.*','CourseSection.name AS sect_name','ActivityType.icon_path AS iocn_type'])
                ->from(['CourseActivity'=> McbsCourseActivity::tableName()]);

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
        $query->leftJoin(['ActivityType' => McbsActivityType::tableName()], 'ActivityType.id = type_id');
        $query->leftJoin(['CourseSection'=> McbsCourseSection::tableName()], '(CourseSection.id = section_id AND CourseSection.is_del = 0)');
        $query->leftJoin(['CourseChapter'=>McbsCourseChapter::tableName()], '(CourseChapter.id = chapter_id AND CourseChapter.is_del = 0)');
        $query->leftJoin(['CourseBlock'=>McbsCourseBlock::tableName()], '(CourseBlock.id = block_id AND CourseBlock.is_del = 0)');
        $query->leftJoin(['CoursePhase'=>McbsCoursePhase::tableName()], '(CoursePhase.id = phase_id AND CoursePhase.is_del = 0)');
        
        // grid filtering conditions
        $query->where(['CourseActivity.is_del' => 0]);
        $query->andFilterWhere([
            'id' => $this->type_id,
            'section_id' => $this->section_id,
            'course_id' => $this->course_id,
            'type_id' => $this->type_id,
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