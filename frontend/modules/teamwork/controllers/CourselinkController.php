<?php

namespace frontend\modules\teamwork\controllers;

use common\models\teamwork\CourseLink;
use common\models\teamwork\CoursePhase;
use frontend\modules\teamwork\utils\TeamworkTool;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
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
        $twTool = TeamworkTool::getInstance();
        /* @var $rbacManager RbacManager */  
        $rbacManager = \Yii::$app->authManager;
        $coursePhase = CoursePhase::find()
                       ->where(['course_id' => $course_id, 'is_delete' => 'N'])
                       ->with('course', 'courseLinks')->all();
        if($this->getWeightTotalIsSmallOrBig($course_id))
                Yii::$app->getSession()->setFlash('error','权重总和不能小于或大于 1！');
        
        return $this->render('index', [
            'model' => $this->findModel(['course_id' => $course_id]),
            'twTool' => $twTool,
            'rbacManager' => $rbacManager,
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
        $twTool = TeamworkTool::getInstance();
        /* @var $rbacManager RbacManager */  
        $rbacManager = \Yii::$app->authManager;
        $coursePhase = $twTool->getCoursePhaseProgress($course_id)->all();
        return $this->render('progress', [
            'twTool' => $twTool,
            'rbacManager' => $rbacManager,
            'course_id' => $course_id,
            'coursePhase' => $coursePhase,
        ]);
    }

    /**
     * Displays a single CourseLink model.
     * @param integer $id
     * @return mixed
     
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }*/

    /**
     * 新增阶段和环节
     * Creates a new CourseLink model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($course_id)
    {
        $phaseModel = new CoursePhase();
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        /* @var $rbacManager RbacManager */  
        $rbacManager = \Yii::$app->authManager;
        $phaseModel->loadDefaultValues();
        $post = Yii::$app->request->post();
        $phaseModel->course_id = $course_id;
        
        if(!($phaseModel->course->coursePrincipal->u_id == \Yii::$app->user->id
           || $rbacManager->isRole(RbacName::ROLE_TEAMWORK_DEVELOP_MANAGER, \Yii::$app->user->id)))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$phaseModel->course->getIsNormal())
            throw new NotAcceptableHttpException('该课程'.$phaseModel->course->getStatusName().'！');
        
        if ($phaseModel->load($post)){
            CoursePhase::updateAll(['is_delete'=> 'N', 'weights' => ArrayHelper::getValue($post, 'CoursePhase.weights')], [
                        'id' => ArrayHelper::getValue($post, 'CoursePhase.id')]);
            CourseLink::updateAll (['is_delete'=> 'N'], ['id' => ArrayHelper::getValue($post, 'CourseLink.id')]);
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
        $phaseModel = CoursePhase::findOne($id);
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        /* @var $rbacManager RbacManager */  
        $rbacManager = \Yii::$app->authManager;
        $post = Yii::$app->request->post();
        
       if(!($phaseModel->course->coursePrincipal->u_id == \Yii::$app->user->id
           || $rbacManager->isRole(RbacName::ROLE_TEAMWORK_DEVELOP_MANAGER, \Yii::$app->user->id)))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$phaseModel->course->getIsNormal())
            throw new NotAcceptableHttpException('该课程'.$phaseModel->course->getStatusName().'！');
        
        if ($phaseModel->load($post) && $phaseModel->save()) {
            $number = CourseLink::updateAll(['is_delete'=> 'N'], ['id' => ArrayHelper::getValue($post, 'CourseLink.id')]);
            return $this->redirect(['index', 'course_id' => $phaseModel->course_id]);
        } else {
            return $this->render('update', [
                'phaseModel' => $phaseModel,
                'phase' => $this->getCoursePhase(['course_id' => $phaseModel->course_id, 'is_delete' => 'N']),
                'link' => $this->getCourseLink(['course_id' => $phaseModel->course_id, 
                'course_phase_id' => $phaseModel->id, 'is_delete' => 'Y']),
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
        $twTool = TeamworkTool::getInstance();
        /* @var $rbacManager RbacManager */  
        $rbacManager = \Yii::$app->authManager;
        if(!($model->course->coursePrincipal->u_id == \Yii::$app->user->id 
           || $rbacManager->isRole(RbacName::ROLE_TEAMWORK_DEVELOP_MANAGER, \Yii::$app->user->id)))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->course->getIsNormal() )
            throw new NotAcceptableHttpException('该课程'.$model->course->getStatusName().'！');
        
        $model->is_delete = 'Y';
        if($model->update() !== false){
            CourseLink::updateAll(['is_delete'=> 'Y'], ['course_id' => $model->course_id, 'course_phase_id' => $model->id]);
            $this->redirect(['index', 'course_id' => $model->course_id]);
        }
    }
    
    /**
     * LinkDelete an existing CourseLink model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionLinkDelete($id)
    {
        $model = $this->findModel($id);
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        /* @var $rbacManager RbacManager */  
        $rbacManager = \Yii::$app->authManager;
        if(!($model->course->coursePrincipal->u_id == \Yii::$app->user->id 
           || $rbacManager->isRole(RbacName::ROLE_TEAMWORK_DEVELOP_MANAGER, \Yii::$app->user->id)))
            throw new NotAcceptableHttpException('无权限操作！');
        
        $model = $this->findModel($id);
        if(!$model->course->getIsNormal() )
            throw new NotAcceptableHttpException('该课程'.$model->course->getStatusName().'！');
        
        $model->is_delete = 'Y';
        $model->update();
        $this->redirect(['index', 'course_id' => $model->course_id]);
            
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
        $twTool = TeamworkTool::getInstance();
        /* @var $rbacManager RbacManager */  
        $isUserBelongProducer = $twTool->getIsUserBelongProducer($model->course_id);
        $rbacManager = \Yii::$app->authManager;
        if(!($isUserBelongProducer|| $model->course->coursePrincipal->u_id == \Yii::$app->user->id 
            || $rbacManager->isRole(RbacName::ROLE_TEAMWORK_DEVELOP_MANAGER, \Yii::$app->user->id)))
            throw new NotAcceptableHttpException('无权限操作！');
        if(!$model->course->getIsNormal() )
            throw new NotAcceptableHttpException('该课程'.$phaseModel->course->getStatusName().'！');

        if(Yii::$app->getRequest()->isPost){
            
            Yii::$app->getResponse()->format = 'json';
            $type = '';
            /** 开启事务 */
            $trans = Yii::$app->db->beginTransaction();
            try
            {  
                if ($model->load(Yii::$app->request->post()) && $model->save()){
                   $type = 1;
                   $trans->commit();  //提交事务
                }else{
                    $type = 0;
                    $trans ->rollBack(); //回滚事务
                    //throw new \Exception($model->getErrors());
                }

                //Yii::$app->getSession()->setFlash('success','操作成功！');
            }catch (\Exception $ex) {
                //Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
            }
            return ['types' => $type];        
        }else {
            return $this->renderAjax('entry', [
                'model' => $model,
            ]);
        }
    }
   
    /**
     * 获取该课程阶段下的所有环节
     * @param type $id
     * @return type JSON
     */
    public function actionSearch($id)
    {
        Yii::$app->getResponse()->format = 'json';
        $link = CourseLink::find()
                ->where(['course_phase_id' => $id, 'is_delete' => 'Y'])
                ->with('course')
                ->with('coursePhase')
                ->all();
        $errors = [];
        $items = [];
        try
        {
            foreach ($link as $value) {
                /* @var $value CourseLink */
                $items[] = ['link' => [ 
                    'id' => $value->id,
                    'name' => $value->name
                ], 'weights' => $value->coursePhase->weights];
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
     * Deletes an existing CourseLink model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
    
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    } */

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
                //->with('course')
                ->all();
        return ArrayHelper::map($phase, 'id', 'name');
        
    }
    
    /**
     * 获取所有课程环节
     * @param type $condition   条件
     * @return array
     */
    public function getCourseLink($condition)
    {
        $phase = CourseLink::find()
                ->where($condition)
                ->with('course')
                ->all();
        return ArrayHelper::map($phase, 'id', 'name');
        
    }
    
    /**
     * 获取权重总和是否小于or大于比例 1
     * @param integer $courseId             课程ID
     * @return boolean                      true为条件成立
     */
    public function getWeightTotalIsSmallOrBig($courseId)
    {
        $results = (new Query())
                    ->select(['SUM(Tw_course_phase.weights) AS weight_total'])
                    ->from(['Tw_course_phase' => CoursePhase::tableName()])
                    ->where(['course_id' => $courseId, 'is_delete' => 'N'])
                    ->one();
        $weightTotal = ArrayHelper::getValue($results, 'weight_total');      
        if($weightTotal < 1 || $weightTotal > 1)
            return true;
        
        return false;
    }
}
