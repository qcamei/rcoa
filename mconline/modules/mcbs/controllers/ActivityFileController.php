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

        return $this->render('index', [
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
     * 下载 
     * 如果不需要查数据库的话直接做参数传递  
     * yii::app ()->request->sendFile (文件名,  file_get_contents (文件路径));  
     */
    public function actionDownload($id) {
        if (isset($_GET['id'])) {
            $model = new McbsActivityFileSearch(); //你的model  
            $result = $model->find(array(
                'select' => array('Uploadfile.path', 'Uploadfile.name'),
                'condition' => 'id=:id', //条件  
                'params' => array(':id' => $id)
            ));
            if (!$result) {
                throw new CHttpException(404, '文件不存在！');
            } else {
                // 服务器端文件的路径   
                $fontArr = explode('/', $result->url);
                $fileName = end($fontArr); //得到文件名字  
                if (file_exists($result->url)) {
                    //发送两个参数一个是名称上面已经处理好，也可以改成你要的，后面是文件路径  
                    yii::app()->request->sendFile($fileName, file_get_contents($result->url));
                }
            }
        }
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
        $course_id = Yii::$app->request->queryParams['course_id'];
        $query = (new Query())
                ->select(['McbsCourse.id AS course_id', 'CourseChapter.id AS chapter_id','CourseChapter.name'])
                ->from(['McbsCourse' => McbsCourse::tableName()])
                //关联查询阶段
                ->leftJoin(['CoursePhase' => McbsCoursePhase::tableName()], 'CoursePhase.course_id = McbsCourse.id')
                //关联查询区块
                ->leftJoin(['CourseBlock' => McbsCourseBlock::tableName()], 'CourseBlock.phase_id = CoursePhase.id')
                //关联查询章
                ->leftJoin(['CourseChapter' => McbsCourseChapter::tableName()], 'CourseChapter.block_id = CourseBlock.id')
                ->where(['McbsCourse.id' => $course_id,]);
        $chapter = $query->all();

        return ArrayHelper::map($chapter, 'chapter_id', 'name');
    }

    /**
     * 查询节
     * @return array
     */
    public function getBelongSection() {
        $course_id = Yii::$app->request->queryParams['course_id'];
        $query = (new Query())
                ->select(['McbsCourse.id', 'CourseSection.id AS section_id', 'CourseSection.name'])
                ->from(['McbsCourse' => McbsCourse::tableName()])
                //关联查询阶段
                ->leftJoin(['CoursePhase' => McbsCoursePhase::tableName()], 'CoursePhase.course_id = McbsCourse.id')
                //关联查询区块
                ->leftJoin(['CourseBlock' => McbsCourseBlock::tableName()], 'CourseBlock.phase_id = CoursePhase.id')
                //关联查询章
                ->leftJoin(['CourseChapter' => McbsCourseChapter::tableName()], 'CourseChapter.block_id = CourseBlock.id')
                //关联查询节
                ->leftJoin(['CourseSection' => McbsCourseSection::tableName()], 'CourseSection.chapter_id = CourseChapter.id')
                ->where(['McbsCourse.id' => $course_id,]);
        $section = $query->all();

        return ArrayHelper::map($section, 'section_id', 'name');
    }

    /**
     * 查询活动
     * @return array
     */
    public function getBelongActivity() {
        $course_id = Yii::$app->request->queryParams['course_id'];
        $query = (new Query())
                ->select(['McbsCourse.id', 'CourseActivity.name AS activity_name','CourseActivity.id AS activity_id'])
                ->from(['McbsCourse' => McbsCourse::tableName()])
                //关联查询阶段
                ->leftJoin(['CoursePhase' => McbsCoursePhase::tableName()], 'CoursePhase.course_id = McbsCourse.id')
                //关联查询区块
                ->leftJoin(['CourseBlock' => McbsCourseBlock::tableName()], 'CourseBlock.phase_id = CoursePhase.id')
                //关联查询章
                ->leftJoin(['CourseChapter' => McbsCourseChapter::tableName()], 'CourseChapter.block_id = CourseBlock.id')
                //关联查询节
                ->leftJoin(['CourseSection' => McbsCourseSection::tableName()], 'CourseSection.chapter_id = CourseChapter.id')
                //关联查询活动表
                ->leftJoin(['CourseActivity' => McbsCourseActivity::tableName()], 'CourseActivity.section_id = CourseSection.id')
                ->where(['McbsCourse.id' => $course_id,]);
        $activity = $query->all();

        return ArrayHelper::map($activity, 'activity_id', 'activity_name');
    }

    /**
     * 查询上传者
     * @return array
     */
    public function getUploadBy() {
        $course_id = Yii::$app->request->queryParams['course_id'];
        $uploadBy = (new Query())
                ->select(['ActivityFile.id', 'ActivityFile.create_by'])
                ->from(['ActivityFile' => McbsActivityFile::tableName()])
                //关联查询上传者
                ->leftJoin(['CreateBy' => User::tableName()], 'CreateBy.id = ActivityFile.create_by')
                ->addSelect(['CreateBy.nickname AS username'])
                ->where(['ActivityFile.course_id' => $course_id,])
                ->all();

        return ArrayHelper::map($uploadBy, 'create_by', 'username');
    }

}
