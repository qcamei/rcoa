<?php

namespace frontend\modules\teamwork\controllers;

use common\models\teamwork\CourseLink;
use common\models\teamwork\CourseManage;
use common\models\teamwork\CoursePhase;
use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\TeamworkTool;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
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
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $coursePhase = CoursePhase::findAll(['course_id' => $course_id, 'is_delete' => 'N']);
        
        return $this->render('index', [
            'model' => $this->findModel(['course_id' => $course_id]),
            'twTool' => $twTool,
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
            'twTool' => $twTool,
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
     * 新增阶段和环节
     * Creates a new CourseLink model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($course_id)
    {
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $phaseModel = new CoursePhase();
        $phaseModel->loadDefaultValues();
        $post = Yii::$app->request->post();
        $phaseModel->course_id = $course_id;
        if(!$twTool->getIsLeader() || $phaseModel->course->create_by !== \Yii::$app->user->id)
            throw new NotAcceptableHttpException('只有队长 or 该课程隶属于自己才可以【新增阶段和环节】');
            
        if ($phaseModel->load($post)){
            Yii::$app->db->createCommand()
                    ->update(CoursePhase::tableName(), ['is_delete'=> 'N', 'weights' => $post['CoursePhase']['weights']], [
                        'course_id' => $course_id,'phase_id' => $post['CoursePhase']['phase_id'],
                    ])->execute();
            
            foreach ($post['link_id'] as $value)
                Yii::$app->db->createCommand()
                    ->update(CourseLink::tableName(), ['is_delete'=> 'N'], ['id' => (int)$value])->execute();
          
            return $this->redirect(['index', 'course_id' => $course_id]);
        }else {
            return $this->render('create', [
                'phaseModel' => $phaseModel,
                'phase' => $this->getCoursePhase(['course_id' => $course_id, 'is_delete' => 'Y']),
                'link' => [],
                'course_id' => $course_id,
            ]);
        }
    }

    /**
     * 编辑阶段和环节
     * Updates an existing CourseLink model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $phaseModel = CoursePhase::findOne($id);
        $post = Yii::$app->request->post();
        $link = empty($post['link_id']) ? [] : $post['link_id'];
        if(!$twTool->getIsLeader() || $phaseModel->course->create_by !== \Yii::$app->user->id)
            throw new NotAcceptableHttpException('只有队长 or 该课程隶属于自己才可以【编辑阶段和环节】');
        
        if ($phaseModel->load($post) && $phaseModel->save()) {
            foreach ($link as $value) 
                Yii::$app->db->createCommand()
                    ->update(CourseLink::tableName(), ['is_delete'=> 'N'], ['id' => (int)$value])->execute();
            
            return $this->redirect(['index', 'course_id' => $phaseModel->course_id]);
        } else {
            return $this->render('update', [
                'phaseModel' => $phaseModel,
                'phase' => $this->getCoursePhase(['course_id' => $phaseModel->course_id, 'is_delete' => 'N']),
                'link' => $this->getCourseLink(['course_id' => $phaseModel->course_id, 
                    'course_phase_id' => $phaseModel->phase_id, 'is_delete' => 'Y']),
            ]);
        }
    }
    
    /**
     * Entry a new CourseLink model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionEntry($id)
    {
        $model = $this->findModel($id);
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        if(!$twTool->getIsLeader() || $model->course->create_by !== \Yii::$app->user->id)
            throw new NotAcceptableHttpException('只有队长 or 该课程隶属于自己才可以操作');
            
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['progress', 'course_id' => $model->course_id]);
        } else {
            return $this->renderAjax('entry', [
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
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $model->is_delete = 'Y';
        if($model->update() !== false && $twTool->getIsLeader() && $model->course->create_by == \Yii::$app->user->id){
            Yii::$app->db->createCommand()
                ->update(CourseLink::tableName(), ['is_delete'=> 'Y'], [
                    'course_id' => $model->course_id, 'course_phase_id' => $model->phase_id])->execute();
            $this->redirect(['index', 'course_id' => $model->course_id]);
        }else 
            throw new NotAcceptableHttpException('只有队长 or 该课程隶属于自己才可以操作');
    }
   
    /**
     * 获取该课程阶段下的所有环节
     * @param type $id
     * @return type JSON
     */
    public function actionSearch($phase_id)
    {
        Yii::$app->getResponse()->format = 'json';
        $link = CourseLink::find()
                ->where(['course_phase_id' => $phase_id, 'is_delete' => 'Y'])
                ->with('link')
                ->all();
        $errors = [];
        $items = [];
        try
        {
            foreach ($link as $value) {
                $items[] = [
                    'id' => $value->id,
                    'name' => $value->link->name
                ];
            }
        } catch (Exception $ex) {
            $errors [] = $ex->getMessage();
        }
        return [
            'type'=>'S',
            'data' => $items,
            'error' => $errors
        ];
    }
    
    /**
     * LinkDelete an existing CourseLink model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLinkDelete($id)
    {
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $model = $this->findModel($id);
        $model->is_delete = 'Y';
        
        if($twTool->getIsLeader() && $model->course->create_by == \Yii::$app->user->id){
            $model->update();
            $this->redirect(['index', 'course_id' => $model->course_id]);
        }else 
            throw new NotAcceptableHttpException('只有队长 or 该课程隶属于自己才可以操作');
    }
    
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
    
    /**
     * 获取所有课程阶段
     * @param type $condition   条件
     * @return type
     */
    public function getCoursePhase($condition)
    {
        $phase = CoursePhase::find()
                ->where($condition)
                ->with('phase')
                ->all();
        return ArrayHelper::map($phase, 'phase_id', 'phase.name');
        
    }
    
    /**
     * 获取所有课程环节
     * @param type $condition   条件
     * @return type
     */
    public function getCourseLink($condition)
    {
        $phase = CourseLink::find()
                ->where($condition)
                ->with('link')
                ->all();
        return ArrayHelper::map($phase, 'id', 'link.name');
        
    }
}
