<?php

namespace frontend\modules\teamwork\controllers;

use common\models\expert\Expert;
use common\models\teamwork\CourseManage;
use common\models\teamwork\CourseSummary;
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
        
        return $this->render('view', [
            'model' => $model,
            'statusName' => $this->AgainStatusName($model),
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
        
        if ($model->load($post) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'courses' => $this->getCourses($model->project->item_child_id),
                'teachers' => $this->getExpert(),
                'teams' => [],
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
        if(!$model->project->getIsLeader() || $model->create_by !== \Yii::$app->user->id)
            throw new NotAcceptableHttpException('只有队长才可以【编辑】课程 or 该课程隶属于自己');
        
        if(!$model->project->getIsNormal())
            throw new NotAcceptableHttpException('该项目现在状态为：'.$model->project->getStatusName());
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'courses' => $this->getCourses($model->project->item_child_id),
                'teachers' => $this->getExpert(),
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
