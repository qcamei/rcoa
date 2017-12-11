<?php

namespace common\models\mconline\searchs;

use common\models\mconline\McbsActivityFile;
use common\models\mconline\McbsCourse;
use common\models\mconline\McbsCourseActivity;
use common\models\mconline\McbsCourseBlock;
use common\models\mconline\McbsCourseChapter;
use common\models\mconline\McbsCoursePhase;
use common\models\mconline\McbsCourseSection;
use common\models\User;
use wskeee\framework\models\Item;
use wskeee\webuploader\models\Uploadfile;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * McbsActivityFileSearch represents the model behind the search form about `common\models\mconline\McbsActivityFile`.
 */
class McbsActivityFileSearch extends McbsActivityFile
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'expire_time', 'created_at', 'updated_at'], 'integer'],
            [['activity_id', 'file_id', 'course_id', 'created_by'], 'safe'],
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
        $query = McbsActivityFile::find();

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
            'expire_time' => $this->expire_time,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'activity_id', $this->activity_id])
            ->andFilterWhere(['like', 'file_id', $this->file_id])
            ->andFilterWhere(['like', 'course_id', $this->course_id])
            ->andFilterWhere(['like', 'created_by', $this->created_by]);

        return $dataProvider;
    }
    
    /**
     * 查找与该课程相关的文件
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchFileList($params)
    {
        $course_id = ArrayHelper::getValue($params, 'course_id', null);
        $chapter_id = ArrayHelper::getValue($params, 'chapter_id');                     //章ID
        $section_id = ArrayHelper::getValue($params, 'section_id');                     //节ID
        $activity_id = ArrayHelper::getValue($params, 'activity_id');                   //活动ID
        $createBy = ArrayHelper::getValue($params, 'created_by');                        //创建者ID
        $keyword = ArrayHelper::getValue($params, 'McbsActivityFileSearch.file_id');    //查询文件名的关键字
        $query = (new Query())
                ->select(['McbsCourse.id','CourseChapter.id AS chapter_id','CourseSection.id AS section_id',
                    'ActivityFile.activity_id','ActivityFile.created_at','ActivityFile.expire_time',
                    'CourseChapter.name AS chapter_name','CourseSection.name AS section_name',
                    'CourseActivity.name AS activity_name','CreateBy.nickname AS created_by',
                    'Uploadfile.name AS filename','ActivityFile.file_id', 'ItemCourse.name AS course_name'])
                ->from(['ActivityFile' => McbsActivityFile::tableName()]);
        
        // add conditions that should always apply here
        //根据课程查询显示内容
        $query->where([
            'McbsCourse.id' => $course_id, 'CoursePhase.is_del' => 0, 'CourseBlock.is_del' => 0,
            'CourseChapter.is_del' => 0, 'CourseSection.is_del' => 0, 'CourseActivity.is_del' => 0,
        ]);
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        
        //关联查询活动表
        $query->leftJoin(['CourseActivity' => McbsCourseActivity::tableName()], 'CourseActivity.id = ActivityFile.activity_id');
        //关联查询节
        $query->leftJoin(['CourseSection' => McbsCourseSection::tableName()], 'CourseSection.id = CourseActivity.section_id');
        //关联查询章
        $query->leftJoin(['CourseChapter' => McbsCourseChapter::tableName()], 'CourseChapter.id = CourseSection.chapter_id');
        //关联查询区块
        $query->leftJoin(['CourseBlock' => McbsCourseBlock::tableName()], 'CourseBlock.id = CourseChapter.block_id');
        //关联查询阶段
        $query->leftJoin(['CoursePhase' => McbsCoursePhase::tableName()], 'CoursePhase.id = CourseBlock.phase_id');
        //关联查询课程
        $query->leftJoin(['McbsCourse' => McbsCourse::tableName()], 'McbsCourse.id = CoursePhase.course_id');
        //查询课程名称
        $query->leftJoin(['ItemCourse' => Item::tableName()], 'ItemCourse.id = McbsCourse.course_id');
        //关联查询文件名
        $query->leftJoin(['Uploadfile' => Uploadfile::tableName()], 'Uploadfile.id = ActivityFile.file_id');
        //关联查询创建者
        $query->leftJoin(['CreateBy' => User::tableName()], 'CreateBy.id = ActivityFile.created_by');
        // grid filtering conditions
        $query->andFilterWhere([
            'CourseChapter.id' => $chapter_id,
            'CourseSection.id' => $section_id,
            'CourseActivity.id' => $activity_id,
            'ActivityFile.created_by' => $createBy,
        ]);
       //按关键字模糊搜索
        $query->andFilterWhere(['or',
            ['like', 'Uploadfile.name', $keyword],
        ]);
        
        $query->orderBy(["ActivityFile.created_at" => SORT_DESC]);
        
        return [
            'filter' => $params,
            'dataProvider' => $dataProvider
        ];
    }
    
}
