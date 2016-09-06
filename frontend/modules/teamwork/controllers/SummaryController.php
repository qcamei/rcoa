<?php

namespace frontend\modules\teamwork\controllers;

use common\models\teamwork\CourseManage;
use common\models\teamwork\CourseSummary;
use frontend\modules\teamwork\TeamworkTool;
use wskeee\rbac\RbacName;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * SummaryController implements the CRUD actions for CourseSummary model.
 */
class SummaryController extends Controller
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
     * Lists all CourseSummary models.
     * @return mixed
     */
    public function actionIndex($course_id, $date)
    {
        Yii::$app->getResponse()->format = 'json';
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $course = $this->findCourseModel($course_id);
        $errors = [];
        $weekinfo = [];
        $currentTime = date('Y-m-d',  time());
        $startTime = empty($course->real_start_time) ? $currentTime : date('Y-m-d', strtotime($course->real_start_time));
        try
        {
            foreach ($twTool->getWeekInfo($startTime, $date) as $value){
                $result = $twTool->getWeeklyInfo($course_id, $value['start'], $value['end']);
                $weekinfo[] = [
                    'date' => date('m/d', strtotime($value['start'])).'～'.date('m/d', strtotime($value['end'])),
                    'class' => !empty($result) ? 'btn btn-info weekinfo' : ($currentTime > $value['end'] ? 
                        'btn btn-danger weekinfo disabled' : ($currentTime >= $value['start'] && $currentTime <= $value['end'] ? 
                        'btn btn-info weekinfo disabled' : 'btn btn-default weekinfo disabled')),
                    'icon' => $currentTime < $value['start'] ?  'not-to' : 
                                (empty($result) && $currentTime > $value['end'] ? 'leak-write' : 
                                    ($currentTime >= $value['start'] && $currentTime <= $value['end'] ? 
                                         'this-week' : 'already-write')),
                    'start' => $value['start'],
                    'end' => $value['end']
                ];
            }
        } catch (Exception $ex) {
            $errors [] = $ex->getMessage();
        }
        return [
            'type'=>'S',
            'data' =>  $weekinfo,
            'error' => $errors
        ];
    }

    /**
     * Displays a single CourseSummary model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($course_id, $start, $end)
    {
        Yii::$app->getResponse()->format = 'json';
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $get = Yii::$app->request->queryParams;
        $errors = [];
        $weeklyInfo = [];
        try
        {
            $weeklyInfo = $twTool->getWeeklyInfo($course_id, $start, $end);
            $weeklyInfo = [
                'course_id' => $weeklyInfo->course_id,
                'create_time' => $weeklyInfo->create_time,
                'content' => $weeklyInfo->content,
                'create_by' => $weeklyInfo->weeklyCreateBy->weeklyEditorsPeople->u->nickname.
                        '('.$weeklyInfo->weeklyCreateBy->weeklyEditorsPeople->position.')',
                'created_at' => date('Y-m-d H:i', $weeklyInfo->created_at)
            ];
        } catch (Exception $ex) {
            $errors [] = $ex->getMessage();
        }
        return [
            'type'=>'S',
            'data' => $weeklyInfo,
            'error' => $errors
        ];
    }

    /**
     * Creates a new CourseSummary model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($course_id)
    {
        $model = new CourseSummary();
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $model->course_id = $course_id;
        /* @var $course CourseManage */
        $course = CourseManage::findOne(['id' => $model->course_id]);
        $model->create_by = $course->weekly_editors_people;
        $model->create_time = date('Y-m-d', time());
        $editorsPeople = $model->course->weekly_editors_people;
        
        if($model != null && !$model->course->getIsNormal())
            throw new NotAcceptableHttpException('该课程'.$model->course->getStatusName().'！');
        
        if(!(($twTool->getIsLeader() && $course->create_by == \Yii::$app->user->id) 
            || $editorsPeople == \Yii::$app->user->id || $course->course_principal == \Yii::$app->user->id
            || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
            throw new NotAcceptableHttpException('无权限操作！');
       
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['course/view', 'id' => $model->course_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'weekly' => file_get_contents('./filedata/teamwork/weekly/weekly_template.html'),       //获取文件内容
            ]);
        }
    }

    /**
     * Updates an existing CourseSummary model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($course_id, $create_time = null)
    {
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $model = $this->findModel($course_id, $create_time);
        $model->create_by = $model->weeklyCreateBy->weekly_editors_people;
        $editorsPeople = $model->course->weekly_editors_people;
        
        if(!$model->course->getIsNormal())
            throw new NotAcceptableHttpException('该课程'.$model->course->getStatusName().'！');
        
        if( !(($twTool->getIsLeader() && $model->course->create_by == \Yii::$app->user->id) 
            || $editorsPeople == \Yii::$app->user->id || $model->course->course_principal == \Yii::$app->user->id
            || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['course/view', 'id' => $model->course_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'weekly' => $model->content,
            ]);
        }
    }

    /**
     * Deletes an existing CourseSummary model.
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
     * Finds the CourseSummary model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CourseSummary the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($course_id, $create_time)
    {
        $model = CourseSummary::findOne(['course_id' => $course_id, 'create_time' => $create_time]);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('所请求的页面不存在.');
        }
    }
    
    /**
     * Finds the CourseManage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CourseSummary the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findCourseModel($course_id)
    {
        $model = CourseManage::findOne(['id' => $course_id]);
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('所请求的页面不存在.');
        }
    }
    
}
