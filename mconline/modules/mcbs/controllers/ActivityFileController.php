<?php

namespace mconline\modules\mcbs\controllers;

use common\models\mconline\McbsActivityFile;
use common\models\mconline\McbsCourse;
use common\models\mconline\McbsCourseActivity;
use common\models\mconline\McbsCourseBlock;
use common\models\mconline\McbsCourseChapter;
use common\models\mconline\McbsCoursePhase;
use common\models\mconline\McbsCourseSection;
use common\models\mconline\searchs\McbsActivityFileSearch;
use common\models\User;
use wskeee\framework\models\Item;
use wskeee\webuploader\models\Uploadfile;
use Yii;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ActivityFileController  implements the CRUD actions for McbsActivityFile model.
 */
class ActivityFileController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all McbsActivityFile models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new McbsActivityFileSearch();
        $dataProvider = $searchModel->searchFileList(Yii::$app->request->queryParams);
        $course_id = ArrayHelper::getValue(Yii::$app->request->queryParams, 'course_id');
        $couModel = McbsCourse::findOne($course_id);
        
        return $this->render('index', [
                    'couModel' => $couModel,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'belongChapter' => $this->getBelongChapter(),
                    'belongSection' => $this->getBelongSection(),
                    'belongActivity' => $this->getBelongActivity(),
                    'uploadBy' => $this->getUploadBy(),
        ]);
    }

    /**
     * Displays a single McbsActivityFile model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new McbsActivityFile model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new McbsActivityFile();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing McbsActivityFile model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing McbsActivityFile model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the McbsActivityFile model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return McbsActivityFile the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = McbsActivityFile::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 查询章
     * @return array
     */
    public function getBelongChapter() {
        $chapter = $this->getIdentityData();
        return ArrayHelper::map($chapter, 'chapter_id', 'chapter_name');
    }

    /**
     * 查询节
     * @return array
     */
    public function getBelongSection() {
        $section =  $this->getIdentityData();
        return ArrayHelper::map($section, 'section_id', 'section_name');
    }

    /**
     * 查询活动
     * @return array
     */
    public function getBelongActivity() {
        $activity = $this->getIdentityData();
        return ArrayHelper::map($activity, 'activity_id', 'activity_name');
    }

    /**
     * 查询上传者
     * @return array
     */
    public function getUploadBy() {
        $course_id = Yii::$app->request->queryParams['course_id'];
        $uploadBy = (new Query())
                ->select(['ActivityFile.id', 'ActivityFile.created_by'])
                ->from(['ActivityFile' => McbsActivityFile::tableName()])
                //关联查询上传者
                ->leftJoin(['CreateBy' => User::tableName()], 'CreateBy.id = ActivityFile.created_by')
                ->addSelect(['CreateBy.nickname AS username'])
                ->distinct()
                ->where(['ActivityFile.course_id' => $course_id,])
                ->all();

        return ArrayHelper::map($uploadBy, 'created_by', 'username');
    }

    /**
     * 查询相同的数据
     * @return array
     */
    public function getIdentityData(){
        $course_id = Yii::$app->request->queryParams['course_id'];
        $query = (new Query())
                ->select(['McbsCourse.id','CourseChapter.id AS chapter_id','CourseSection.id AS section_id',
                    'ActivityFile.activity_id','CourseChapter.name AS chapter_name',
                    'CourseSection.name AS section_name','CourseActivity.name AS activity_name'])
                ->from(['ActivityFile' => McbsActivityFile::tableName()]);
        //根据课程查询显示内容
        $query->where([
            'McbsCourse.id' => $course_id, 'CoursePhase.is_del' => 0, 'CourseBlock.is_del' => 0,
            'CourseChapter.is_del' => 0, 'CourseSection.is_del' => 0, 'CourseActivity.is_del' => 0,
        ]);
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
        
        return $query->all();
    }
}
