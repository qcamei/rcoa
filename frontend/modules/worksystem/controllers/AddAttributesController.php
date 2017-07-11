<?php

namespace frontend\modules\worksystem\controllers;

use common\models\worksystem\searchs\WorksystemAddAttributesSearch;
use common\models\worksystem\WorksystemAddAttributes;
use common\models\worksystem\WorksystemAttributes;
use common\models\worksystem\WorksystemAttributesTemplate;
use frontend\modules\worksystem\utils\WorksystemTool;
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
 * AddAttributesController implements the CRUD actions for WorksystemAddAttributes model.
 */
class AddAttributesController extends Controller
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
     * Lists all WorksystemAddAttributes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorksystemAddAttributesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WorksystemAddAttributes model.
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
     * Creates a new WorksystemAddAttributes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param WorksystemTool $_wsTool
     * @param integer $task_type_id
     * @return mixed
     */
    public function actionCreate($task_type_id = null)
    {
        /*$model = new WorksystemAddAttributes();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }*/
        if(!\Yii::$app->user->can(RbacName::PERMSSION_WORKSYSTEM_TASK_CREATE))
            throw new NotAcceptableHttpException('无权限操作！');
        
        $_wsTool = WorksystemTool::getInstance();
        $items = $this->getWorksystemAttributes($task_type_id);
        $datas = $_wsTool->WorksystemAttributesFormat($items);
        
        return $this->renderAjax('create', [
            //'model' => $model,
            'datas' => $datas,
        ]);
    }

    /**
     * Updates an existing WorksystemAddAttributes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param WorksystemTool $_wsTool
     * @param integer $task_id
     * @return mixed
     */
    public function actionUpdate($task_id)
    {
        /*$model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }*/
        
        $_wsTool = WorksystemTool::getInstance();
        $items = $_wsTool->getWorksystemTaskAddAttributes($task_id);
        $datas = $_wsTool->WorksystemAttributesFormat($items);
        
        return $this->renderAjax('update', [
            //'model' => $model,
            'datas' => $datas,
        ]);
    }

    /**
     * Deletes an existing WorksystemAddAttributes model.
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
     * Finds the WorksystemAddAttributes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorksystemAddAttributes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorksystemAddAttributes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 获取所有工作系统基础附加属性
     * @param integer $taskTypeId               工作系统任务类型id
     * @return array
     */
    public function getWorksystemAttributes($taskTypeId)
    {
        $templates = (new Query())
                ->select(['id', 'worksystem_attributes_id'])
                ->from(WorksystemAttributesTemplate::tableName())
                ->where(['worksystem_task_type_id' => $taskTypeId])
                ->all();
        
        $attributes = (new Query())
                   ->select(['id', 'name', 'type', 'input_type', 'value_list', 'index', 'is_delete'])
                   ->from(WorksystemAttributes::tableName())
                   ->where(['id' => ArrayHelper::getColumn($templates, 'worksystem_attributes_id')])
                   ->all();
           
        return $attributes;
    }
}
