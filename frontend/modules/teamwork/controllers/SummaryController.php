<?php

namespace frontend\modules\teamwork\controllers;

use common\models\teamwork\CourseManage;
use common\models\teamwork\CourseSummary;
use frontend\modules\teamwork\utils\TeamworkTool;
use wskeee\rbac\RbacManager;
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
        $twTool = TeamworkTool::getInstance();
        $course = $this->findCourseModel($course_id);
        $start = 0;
        $errors = [];
        $weekinfo = [];
        $currentTime = date('Y-m-d',  time());
        $startTime = empty($course->real_start_time) ? $currentTime : date('Y-m-d', strtotime($course->real_start_time));
        $weeks = $twTool->getWeekInfo($startTime, $date);       //获取1月每周的星期1和星期天日期
        $weekStart = reset($weeks);
        $weekEnd = end($weeks);
        $dateArr = ['start' => $weekStart['start'], 'end' => $weekEnd['end']];
        $weeklyDate = ArrayHelper::getColumn($twTool->getWeeklyInfo($course->id, $dateArr), 'create_time');
        try
        {
            foreach ($weeks as &$week) {
                for ($i = $start; $i < count($weeklyDate); $i++) {
                    if ($week['start'] <= $weeklyDate[$i] && $week['end'] >= $weeklyDate[$i]) {
                        $week['has'] = true;
                        $start = $i + 1;
                        break;
                    }
                }
                $weekinfo[] = [
                    'date' => date('m/d', strtotime($week['start'])) . '～' . date('m/d', strtotime($week['end'])),
                    'class' => $currentTime < $week['start'] ? 'btn btn-default weekinfo disabled' : 
                       (!isset($week['has']) && $currentTime > $week['end'] ? 'btn btn-danger weekinfo disabled' : 
                       ($currentTime >= $week['start'] && $currentTime <= $week['end'] ? 'btn btn-info weekinfo' : 'btn btn-info weekinfo')),
                    'icon' => $currentTime < $week['start'] ? 'not-to' : (!isset($week['has']) && $currentTime > $week['end'] ? 'leak-write' :
                                    ($currentTime >= $week['start'] && $currentTime <= $week['end'] ? 'this-week' : 'already-write')),
                    'week' => $week
                ];
            }
        } catch (Exception $ex) {
            $errors [] = $ex->getMessage();
        }
        return [
            'type'=> 1,
            'date' => $date,
            'data' =>  $weekinfo,
            'error' => $errors
        ];
    }

    /**
     * Displays a single CourseSummary model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($course_id, $date)
    {
        Yii::$app->getResponse()->format = 'json';
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        $get = Yii::$app->request->queryParams;
        $weeks = explode('/', $date);
        $week = [
            'start' => $weeks[0],
            'end' => $weeks[1]
        ];
        $errors = [];
        $weeklyInfo = [];
        try
        {
            $weeklyInfo = $twTool->getWeeklyInfo($course_id, $week, false);
            if(!empty($weeklyInfo)){
                $weeklyInfo = [
                    'create_time' => $weeklyInfo->create_time,
                    'content' => $weeklyInfo->content,
                    'create_by' => $weeklyInfo->weeklyCreateBy->weeklyEditorsPeople->user->nickname,
                    'created_at' => date('Y-m-d H:i', $weeklyInfo->created_at)
                ];
            }  else {
                $weeklyInfo = [
                    'create_time' => null,
                    'content' => '无',
                    'create_by' => '无',
                    'created_at' => '无'
                ];
            }
        } catch (Exception $ex) {
            $errors [] = $ex->getMessage();
        }
        return [
            'type'=> 1,
            'date' => $date,
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
        $twTool = TeamworkTool::getInstance();
        /* @var $course CourseManage */
        $course = CourseManage::findOne(['id' => $course_id]);
        /* @var $rbacManager RbacManager */  
        $rbacManager = \Yii::$app->authManager;
        $model->course_id = $course_id;
        $model->create_by = \Yii::$app->user->id;
        $model->create_time = date('Y-m-d', time());
        
        if(!($course->weeklyEditorsPeople->u_id == \Yii::$app->user->id
          || $course->coursePrincipal->u_id == \Yii::$app->user->id || $rbacManager->isRole(RbacName::ROLE_TEAMWORK_DEVELOP_MANAGER, Yii::$app->user->id)))
            throw new NotAcceptableHttpException('无权限操作！');
        if($model != null && !$model->course->getIsNormal())
            throw new NotAcceptableHttpException('该课程'.$model->course->getStatusName().'！');
        
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
        $twTool = TeamworkTool::getInstance();
        /* @var $rbacManager RbacManager */  
        $rbacManager = \Yii::$app->authManager;
        if($create_time == null)
            $model = $twTool->getWeeklyInfo($course_id, $twTool->getWeek(date('Y-m-d', time())), false);
        else
            $model = $this->findModel($course_id, $create_time);
        
        if(!($model->course->weeklyEditorsPeople->u_id == \Yii::$app->user->id
          || $model->course->coursePrincipal->u_id == \Yii::$app->user->id || $rbacManager->isRole(RbacName::ROLE_TEAMWORK_DEVELOP_MANAGER, Yii::$app->user->id)))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->course->getIsNormal())
            throw new NotAcceptableHttpException('该课程'.$model->course->getStatusName().'！');
        
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
     
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }*/

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
