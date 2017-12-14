<?php

namespace mconline\modules\mcbs\controllers;

use common\models\mconline\McbsActivityFile;
use common\models\mconline\McbsCourse;
use common\models\mconline\McbsCourseActivity;
use common\models\mconline\McbsCourseBlock;
use common\models\mconline\McbsCourseChapter;
use common\models\mconline\McbsCoursePhase;
use common\models\mconline\McbsCourseSection;
use common\models\mconline\McbsFileActionResult;
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
    public function actionIndex($course_id) {
        $couModel = McbsCourse::findOne($course_id);
        $searchModel = new McbsActivityFileSearch();
        $dataProvider = $searchModel->searchFileList(Yii::$app->request->queryParams);
        
        return $this->render('index', [
                    'couModel' => $couModel,
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
                    'belongChapter' => ArrayHelper::map($dataProvider['dataProvider']->models, 'chapter_id', 'chapter_name'), //$this->getBelongChapter(),       //所属章
                    'belongSection' => ArrayHelper::map($dataProvider['dataProvider']->models, 'section_id', 'section_name'), //$this->getBelongSection(),       //所属节
                    'belongActivity' => ArrayHelper::map($dataProvider['dataProvider']->models, 'activity_id', 'activity_name'), //$this->getBelongActivity(),     //所属活动
                    'uploadBy' => ArrayHelper::map($dataProvider['dataProvider']->models, 'created_by', 'nickname'),                 //上传者
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

}
