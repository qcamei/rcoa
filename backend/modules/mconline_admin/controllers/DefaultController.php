<?php

namespace backend\modules\mconline_admin\controllers;

use common\models\mconline\McbsCourse;
use common\models\mconline\McbsCourseActivity;
use common\models\mconline\McbsCourseBlock;
use common\models\mconline\McbsCourseChapter;
use common\models\mconline\McbsCoursePhase;
use common\models\mconline\McbsCourseSection;
use wskeee\webuploader\models\Uploadfile;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Default controller for the `mconline_admin` module
 */
class DefaultController extends Controller {

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex() {
        
        return $this->render('index', [
                'model' => [
                    'dataHistroy' => $this->getSpaceInfo(),
                    'dataNow' => $this->getSpaceInfo(0),
                    'coursedata' => $this->getCourseInfo(),
                ],
        ]);
    }

    /**
     * 查询空间信息
     * @param int $is_del
     * @return array
     */
    public function getSpaceInfo($is_del=null) {
            $query = (new Query())
                ->select(['COUNT(Uploadfile.id) AS number','(SUM(Uploadfile.size) / (1024 * 1024)) AS size'])
                ->from(['Uploadfile' => Uploadfile::tableName()])
                ->filterWhere(['Uploadfile.is_del' => $is_del])
                ->one();
        return $query;
    }
    
    /**
     * 查询课程信息
     * @return array
     */
    public function getCourseInfo() {
        $query = (new Query())
                ->select(['McbsCourse.id AS course_id','CourseChapter.id chapter_id','CourseSection.id AS section_id',
                    'CourseActivity.id AS activity_id'])
                ->from(['McbsCourse' => McbsCourse::tableName()]);
        //关联查询阶段
        $query->leftJoin(['CoursePhase' => McbsCoursePhase::tableName()], 'CoursePhase.course_id = McbsCourse.id');
        //关联查询区块
        $query->leftJoin(['CourseBlock' => McbsCourseBlock::tableName()], 'CourseBlock.phase_id = CoursePhase.id');
        //关联查询章
        $query->leftJoin(['CourseChapter' => McbsCourseChapter::tableName()], 'CourseChapter.block_id = CourseBlock.id');
        //关联查询节
        $query->leftJoin(['CourseSection' => McbsCourseSection::tableName()], 'CourseSection.chapter_id = CourseChapter.id');
        //关联查询活动表
        $query->leftJoin(['CourseActivity' => McbsCourseActivity::tableName()], 'CourseActivity.section_id = CourseSection.id');
        
        //总课程数
        $courseNum = count(array_filter(array_unique(ArrayHelper::getColumn($query->all(), 'course_id'))));
        //总章数
        $chapterNum = count(array_filter(array_unique(ArrayHelper::getColumn($query->all(), 'chapter_id'))));
        //总节数
        $sectionNum = count(array_filter(array_unique(ArrayHelper::getColumn($query->all(), 'section_id'))));
        //总活动数
        $activityNum = count(array_filter(array_unique(ArrayHelper::getColumn($query->all(), 'activity_id'))));

        return [
            'courseNum' => $courseNum,
            'chapterNum' => $chapterNum,
            'sectionNum' => $sectionNum,
            'activityNum' => $activityNum,
        ];
    }
}
