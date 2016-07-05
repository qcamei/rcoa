<?php

namespace frontend\modules\teamwork\controllers;

use common\models\expert\Expert;
use common\models\team\TeamMember;
use common\models\teamwork\CourseManage;
use common\models\teamwork\CourseProducer;
use common\models\teamwork\ItemManage;
use wskeee\framework\models\Item;
use Yii;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * CourseController implements the CRUD actions for CourseManage model.
 */
class CourseController extends Controller
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
     * Index all CourseManage models.
     * @return mixed
     */
    public function actionIndex()
    {
        /* @var $model CourseManage */
        $params = Yii::$app->request->queryParams;
        $model = new CourseManage();
        if(isset($params['project_id'])){    
            $dataProvider = new ArrayDataProvider([
                'allModels' => $this->findItemModel($params['project_id']),
            ]);
        }else {
            $dataProvider = new ActiveDataProvider([
                'query' => CourseManage::find(),
            ]);
        }
        
        return $this->render('index', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Lists all CourseManage models.
     * @return mixed
     */
    public function actionList($project_id)
    {
        $allModels = $this->findItemModel($project_id);
        foreach ($allModels as $value)
            $model = $this->findModel($value->id);
        
        return $this->render('list', [
            'allModels' => $allModels,
            'model' => empty($allModels) ? new CourseManage() : $model,
            'project_id' => $project_id,
        ]);
    }

    /**
     * Displays a single CourseManage model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $assignProducer = $this->getAssignProducers(['course_id' => $model->id]);
        var_dump($assignProducer);exit;
        $producers = [];
        foreach ($assignProducer as $key => $producer){
            $producers[] = $key == \Yii::$app->user->id ? 
                    '<span style="margin:5px;color:red;">'.$producer.'(队长)</span>'    :
                    '<span style="margin:5px;">'.$producer.'</span>';
        }
       
        return $this->render('view', [
            'model' => $model,
            'statusName' => $this->AgainStatusName($model),
            'producer' => $producers,
        ]);
    }

    /**
     * Creates a new CourseManage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $params = Yii::$app->request->queryParams;
        $post = Yii::$app->request->post();
        $model = new CourseManage();
        $model->loadDefaultValues();
        $model->project_id = $params['project_id'];
        $model->create_by = \Yii::$app->user->id;
        if(!$model->project->getIsLeader())
            throw new NotAcceptableHttpException('只有队长才可以【添加课程】');
        
        if ($model->load($post)) {
            /** 开启事务 */
            $trans = Yii::$app->db->beginTransaction();
            try
            {  
                if($model->save())
                    $this->saveCourseProducer($model->id, $post['producer']);
                $trans->commit();  //提交事务
                Yii::$app->getSession()->setFlash('success','操作成功！');
                return $this->redirect(['view', 'id' => $model->id]);
            }catch (Exception $ex) {
                $trans ->rollBack(); //回滚事务
                Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
                $this->render(['create', 'id' => $model->project_id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'courses' => $this->getCourses($model->project->item_child_id),
                'teachers' => $this->getExpert(),
                'producerList' => $this->getTeamMemberList(),
                'producer' => $this->getSameTeamMember(\Yii::$app->user->id)
            ]);
        }
    }

    /**
     * Updates an existing CourseManage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if(!$model->project->getIsLeader() || $model->create_by !== \Yii::$app->user->id)
            throw new NotAcceptableHttpException('只有队长才可以【编辑】课程 or 该课程隶属于自己');
        
        if(!$model->project->getIsNormal())
            throw new NotAcceptableHttpException('该项目现在状态为：'.$model->project->getStatusName());
        
        if ($model->load($post)) {
            /** 开启事务 */
            $trans = Yii::$app->db->beginTransaction();
            try
            {  
                if($model->save()){
                    CourseProducer::deleteAll(['course_id' => $model->id]);
                    $this->saveCourseProducer($model->id, $post['producer']);
                }
                $trans->commit();  //提交事务
                Yii::$app->getSession()->setFlash('success','操作成功！');
                return $this->redirect(['view', 'id' => $model->id]);
            }catch (Exception $ex) {
                $trans ->rollBack(); //回滚事务
                Yii::$app->getSession()->setFlash('error','操作失败::'.$ex->getMessage());
                $this->render(['update', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'courses' => $this->getCourses($model->project->item_child_id),
                'teachers' => $this->getExpert(),
                'producerList' => $this->getTeamMemberList(),
                'producer' => $this->getAssignProducers(['course_id' => $model->id]),
            ]);
        }
        
    }
    
    /**
     * 更改状态为【完成】
     * CarryOut an existing ItemManage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionCarryOut($id)
    {
        $model = $this->findModel($id);
        
        if ($model != null && $model->project->getIsNormal() && $model->project->getIsLeader() && $model->create_by == \Yii::$app->user->id) 
        {
            $model->status = ItemManage::STATUS_CARRY_OUT;
            $model->save();
        }
        $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing CourseManage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id, $project_id)
    {
        //$this->findModel($id)->delete();

        $this->redirect(['list','project_id' => $project_id]);
    }

    /**
     * Finds the CourseManage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CourseManage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CourseManage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 该项目下的所有课程
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $project_id
     * @return CourseManage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findItemModel($project_id)
    {
        if (($model = CourseManage::findAll(['project_id' => $project_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 保存数据到表里
     * @param type $course_id  任务id
     * @param type $post 
     */
    public function saveCourseProducer($course_id, $post){
        $values = [];
        /** 重组提交的数据为$values数组 */
        foreach($post as $value)
        {
            $values[] = [
                'course_id' => $course_id,
                'producer' => $value,
            ];
        }
        
        /** 添加$values数组到表里 */
        Yii::$app->db->createCommand()->batchInsert(CourseProducer::tableName(), 
        [
            'course_id',
            'producer',
        ], $values)->execute();
    }
    
    
    /**
     * 获取课程
     * @param type $model
     * @return type
     */
    public function getCourses($model)
    {
        $courses = Item::findAll(['parent_id' => $model]);
        return ArrayHelper::map($courses, 'id', 'name');
    }
    
    /**
     * 获取专家库
     * @return type
     */
    public function getExpert(){
        $expert = Expert::find()
                ->with('user') 
                ->all();
        return ArrayHelper::map($expert, 'u_id','user.nickname');
    }
    
    /**
     * 获取团队成员
     * @return type
     */
    public function getTeamMemberList()
    {
        /* @var $model CourseManage */
        $producers = TeamMember::find()
                    ->with('u')
                    ->all();
        return ArrayHelper::map($producers, 'u_id','u.nickname');
    }
    
    /**
     * 获取当前用户下的所有团队成员
     * @param type $u_id    用户ID
     * @return type
     */
    public function getSameTeamMember($u_id)
    {
        $teamMember = TeamMember::find()->where(['u_id' => $u_id])->one();
        $sameTeamMember = TeamMember::find()
                        ->where(['team_id' => $teamMember->team_id])
                        ->with('u')
                        ->all();
        return ArrayHelper::map($sameTeamMember, 'u_id','u.nickname');
    }
    
    /**
     * 获取已分配的制作人
     * @param type $condition   条件
     * @return type
     */
    public function getAssignProducers($condition){
        $assignProducers = CourseProducer::find()
                           ->select(['course_id','is_leader','producer','u_id'])
                           ->where($condition)
                           ->with('producerOne')
                           ->asArray()
                           ->all();
                   var_dump($assignProducers);exit;
        $v = ArrayHelper::multisort($assignProducers, 'is_leader', 'SORT_ASC');
        var_dump($v);exit;
        return ;
    }

    /**
     * 重组 $model->statusName 数组
     * @param type $model
     * @return type
     */
    public function AgainStatusName($model){
        $statusName = [];
        /* @var $model CourseManage */
        foreach ($model->project->statusName as $key => $value) {
            //var_dump(array_pop($value));
            $statusName[] = $model->project->statusName[$model->status] == $value ? 
                    '<span style="color:red">'.$value.'</span>' : $value;
        }
        $array_pop = array_pop($statusName);
        unset($array_pop);
        return $statusName;
    }
}
