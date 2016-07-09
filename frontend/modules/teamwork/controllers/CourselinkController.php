<?php

namespace frontend\modules\teamwork\controllers;

use common\models\teamwork\CourseLink;
use common\models\teamwork\CoursePhase;
use frontend\modules\teamwork\TeamworkTool;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * CourselinkController implements the CRUD actions for CourseLink model.
 */
class CourselinkController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            //access验证是否有登录
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    /**
     * Lists all CourseLink models.
     * @return mixed
     */
    public function actionIndex($course_id)
    {
        $coursePhase = CoursePhase::findAll(['course_id' => $course_id, 'is_delete' => 'N']);
        
        return $this->render('index', [
            'model' => $this->findModel(['course_id' => $course_id]),
            'coursePhase' => $coursePhase,
            'course_id' => $course_id
        ]);
    }
    
    /**
     * Progress all CourseLink models.
     * @return mixed
     */
    public function actionProgress($course_id)
    {
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $coursePhase = $twTool->getCoursePhaseProgressAll($course_id);
        return $this->render('progress', [
            'course_id' => $course_id,
            'coursePhase' => $coursePhase,
        ]);
    }

    /**
     * Displays a single CourseLink model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new CourseLink model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($course_id)
    {
        $model = new CourseLink();
        //$params = Yii::$app->request->queryParams;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'course_id' => $course_id,
            ]);
        }
    }

    /**
     * Updates an existing CourseLink model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
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
     * PhaseDelete an existing CourseLink model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionPhaseDelete($id)
    {
        $model = CoursePhase::findOne($id);
        $model->is_delete = 'Y';
        if($model->update() !== false && $model->course->project->getIsLeader() && $model->course->create_by == \Yii::$app->user->id){
            Yii::$app->db->createCommand()
                ->update(CourseLink::tableName(), ['is_delete'=> 'Y'], [
                    'course_id' => $model->course_id, 'course_phase_id' => $model->phase_id])->execute();
            $this->redirect(['index', 'course_id' => $model->course_id]);
        }else 
            throw new NotAcceptableHttpException('只有队长 or 该课程隶属于自己才可以操作');
    }
    
    /**
     * Entry a new CourseLink model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionEntry($id)
    {
        $model = $this->findModel($id);
        if(!$model->course->project->getIsLeader() || $model->course->create_by == \Yii::$app->user->id)
            throw new NotAcceptableHttpException('只有队长 or 该课程隶属于自己才可以操作');
            
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['progress', 'course_id' => $model->course_id]);
        } else {
            return $this->renderPartial('entry', [
                'model' => $model,
            ]);
        }
    }
    
    
    /**
     * LinkDelete an existing CourseLink model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     
    public function actionLinkDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_delete = 'Y';
        
        if($model->course->project->getIsLeader() && $model->course->create_by == \Yii::$app->user->id){
            $model->update();
            $this->redirect(['index', 'course_id' => $model->course_id]);
        }else 
            throw new NotAcceptableHttpException('只有队长 or 该课程隶属于自己才可以操作');
    }*/
    
    /**
     * Deletes an existing CourseLink model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CourseLink model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CourseLink the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CourseLink::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
