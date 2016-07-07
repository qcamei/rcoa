<?php

namespace frontend\modules\teamwork\controllers;

use common\models\teamwork\ItemManage;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\ItemType;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for ItemManage model.
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
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
     * Index all ItemManage models.
     * @return mixed
     */
    public function actionIndex()
    {
       return $this->render('index');
    }
    
    /**
     * Statistics all ItemManage models.
     * @return mixed
     */
    public function actionStatistics()
    {
       return $this->render('statistics');
    }
    
    /**
     * Lists all ItemManage models.
     * @return mixed
     */
    public function actionList()
    {
        $model = new ItemManage();
        $dataProvider = new ActiveDataProvider([
            'query' => ItemManage::find(),
        ]);
        return $this->render('list', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ItemManage model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            //'statusName' => $this->AgainStatusName($model),
        ]);
    }

    /**
     * Creates a new ItemManage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ItemManage();
        if(!$model->getIsLeader())
            throw new NotAcceptableHttpException('只有队长才可以【创建项目】');
            
        $model->loadDefaultValues();
        $model->create_by = \Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'itemType' => $this->getItemType(),
                'items' => $this->getCollegesForSelect(),
                'itemChilds' => [],
            ]);
        }
    }

    /**
     * Updates an existing ItemManage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if(!$model->getIsLeader() || $model->create_by !== \Yii::$app->user->id)
            throw new NotAcceptableHttpException('只有队长才可以【编辑】项目 or 该项目隶属于自己');
        
        if(!$model->getIsNormal())
            throw new NotAcceptableHttpException('该项目现在状态为：'.$model->getStatusName());
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'itemType' => $this->getItemType(),
                'items' => $this->getCollegesForSelect(),
                'itemChilds' => $this->getFwItemForSelect($model->item_id),
            ]);
        }
    }
    
    /**
     * 更改状态为【暂停】
     * TimeOut an existing ItemManage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionTimeOut($id)
    {
        $model = $this->findModel($id);
        
        if ($model != null && $model->getIsNormal() && $model->getIsLeader() && $model->create_by == \Yii::$app->user->id) 
        {
            $model->status = ItemManage::STATUS_TIME_OUT;
            $model->save();
        }
        $this->redirect(['view', 'id' => $model->id]);
    }
    
    /**
     * 更改状态为【正常】
     * Normal an existing ItemManage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionNormal($id)
    {
        $model = $this->findModel($id);
        
        if ($model != null && $model->getIsTimeOut() && $model->getIsLeader() && $model->create_by == \Yii::$app->user->id) 
        {
            $model->status = ItemManage::STATUS_NORMAL;
            $model->save();
        }
        $this->redirect(['view', 'id' => $model->id]);
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
        
        if ($model != null && $model->getIsNormal() && $model->getIsLeader() && $model->getIsCoursesStatus() && $model->create_by == \Yii::$app->user->id) 
        {
            $model->status = ItemManage::STATUS_CARRY_OUT;
            $model->save();
        }else
            throw new NotAcceptableHttpException('该项目下有课程未完成！');
        $this->redirect(['view', 'id' => $model->id]);
    }

    /**
     * Deletes an existing ItemManage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model =  $this->findModel($id);
        if ($model != null && $model->getIsNormal() && $model->getIsLeader() && $model->create_by == \Yii::$app->user->id)
            $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the ItemManage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ItemManage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ItemManage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 获取项目类别
     * @return type
     */
    public function getItemType()
    {
        $itemType = ItemType::find()->all();
        return ArrayHelper::map($itemType, 'id', 'name');
    }
    
    /**
     * 获取项目
     * @return type
     */
    public function getCollegesForSelect()
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getColleges(), 'id', 'name');
    }
    
    /**
     * 获取子项目
     * @param int $itemId
     */
    public function getFwItemForSelect($itemId)
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getChildren($itemId), 'id', 'name');
    }
    
    /**
     * 重组 $model->statusName 数组
     * @param type $model
     * @return type
     */
    /*public function AgainStatusName($model){
        $statusName = [];
        /* @var $model ItemManage 
        foreach ($model->statusName as $value) {
            $statusName[] = $model->statusName[$model->status] == $value ? 
                    '<span style="color:red">'.$value.'</span>' : $value;
        }
        return $statusName;
    }*/
    
    public function getCourseLessionTimesSum($model)
    {
        $courses = [];
        /* @var $model ItemManage */
        foreach ($model->courseManages as $value) 
            $courses[] =  $value->lession_time;
        
        return $courses;
    }
}
