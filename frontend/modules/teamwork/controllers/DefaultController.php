<?php

namespace frontend\modules\teamwork\controllers;

use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\teamwork\CourseManage;
use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\TeamworkTool;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use wskeee\rbac\RbacName;
use Yii;
use yii\data\ArrayDataProvider;
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
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $completedHours = $twTool->getCourseLessionTimesSum(['status' => CourseManage::STATUS_CARRY_OUT]);
        $undoneHours = $twTool->getCourseLessionTimesSum(['status' => CourseManage::STATUS_NORMAL]);
        $completedDoor = CourseManage::find()->where(['status' => CourseManage::STATUS_CARRY_OUT])->count();
        $undoneDoor = CourseManage::find()->where(['status' => CourseManage::STATUS_NORMAL])->count();
        $scienceFactory = Team::findOne(['type' => 2]);
        $teamMember = Team::find()->where(['type' => 1])->with('courseManages')->all();
         
        return $this->render('index',[
            'twTool' => $twTool,
            'completedHours' => $completedHours,
            'undoneHours' => $undoneHours,
            'completedDoor' => $completedDoor,
            'undoneDoor' => $undoneDoor,
            'scienceFactory' => $scienceFactory,
            'teamMember' => $teamMember,
        ]);
    }
    
    /**
     * Member all ItemManage models.
     * @return mixed
     */
    public function actionMember($team_id)
    {
        $team = Team::findOne(['id' => $team_id]);
        $teamMember = TeamMember::find()
                ->where(['team_id' => $team_id])
                ->with('team')
                ->orderBy('index asc')
                ->all();
       
        return $this->render('member', [
            'team' => $team,
            'teamMember' => $teamMember,
        ]);
    }
    
    /**
     * Lists all ItemManage models.
     * @return mixed
     */
    public function actionList()
    {
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $dataProvider = new ArrayDataProvider([
            'allModels' => $twTool->getItemProgressAll(), 
        ]);
        
        return $this->render('list', [
            'twTool' => $twTool,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    /**
     * Search all ItemManage models.
     * @return mixed
     */
    public function actionSearch($keyword)
    {
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $dataProvider = new ArrayDataProvider([
            'allModels' => $twTool->getItemProgressAll($keyword), 
        ]);
        return $this->render('list', [
            'twTool' => $twTool,
            'keyword' => $keyword,
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
        /* @var $model ItemManage */
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        $model = $twTool->getItemProgressOne($id);
        return $this->render('view', [
            'model' => !empty($model->progress) ? $model : $this->findModel($id),
            'twTool' => $twTool,
            'lessionTime' => $twTool->getCourseLessionTimesSum(['project_id' => $id])
        ]);
    }

    /**
     * Creates a new ItemManage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        if(!($twTool->getIsLeader() || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
            throw new NotAcceptableHttpException('无权限操作！');
        
        $model = new ItemManage();
        $model->loadDefaultValues();
        //$model->team_id = $twTool->getHotelTeam(Yii::$app->user->id);
        $model->create_by = Yii::$app->user->id;
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
        /* @var $twTool TeamworkTool */
        $twTool = Yii::$app->get('twTool');
        if(!($twTool->getIsLeader() || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
            throw new NotAcceptableHttpException('无权限操作！');
        
        $model = $this->findModel($id);
        /*if(!$model->getIsNormal())
            throw new NotAcceptableHttpException('该项目'.$model->getStatusName().'！');*/

        $itemChild = $this->getFwItemForSelect($model->item_id);
        $existedItemChild = $this->getExistedItemForSelect($model->item_id);
        $existedItemChildOne = $this->getExistedItemChild($model->item_child_id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'itemType' => $this->getItemType(),
                'items' => $this->getCollegesForSelect(),
                'itemChilds' => ArrayHelper::merge($existedItemChildOne, array_diff($itemChild, $existedItemChild)),
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
    /*public function actionTimeOut($id)
    {
        $model = $this->findModel($id);
        /* @var $twTool TeamworkTool
        $twTool = Yii::$app->get('twTool');
        if ($model != null && $model->getIsNormal() && ($twTool->getIsLeader() || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER))) 
        {
            $model->status = ItemManage::STATUS_TIME_OUT;
            $model->save();
        }
        $this->redirect(['view', 'id' => $model->id]);
    }*/
    
    /**
     * 更改状态为【在建】
     * Normal an existing ItemManage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    /*public function actionNormal($id)
    {
        $model = $this->findModel($id);
        /* @var $twTool TeamworkTool 
        $twTool = Yii::$app->get('twTool');
        if ($model != null && $model->getIsTimeOut() && ($twTool->getIsLeader() || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER))) 
        {
            $model->status = ItemManage::STATUS_NORMAL;
            $model->save();
        }
        $this->redirect(['view', 'id' => $model->id]);
    }*/
    
    /**
     * 更改状态为【已完成】
     * CarryOut an existing ItemManage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    /*public function actionCarryOut($id)
    {
        $model = $this->findModel($id);
        /* @var $twTool TeamworkTool
        $twTool = Yii::$app->get('twTool');
        if ($model != null && $model->getIsNormal() && ($twTool->getIsLeader() || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)) && $model->getIsCoursesStatus()) 
        {
            $model->status = ItemManage::STATUS_CARRY_OUT;
            $model->save();
        }else
            throw new NotAcceptableHttpException('该项目下有课程未完成！');
        $this->redirect(['view', 'id' => $model->id]);
    }*/
    
    /**
     * 获取专业/工种
     * @param type $id
     * @return type JSON
     */
    public function actionSearchSelect($id, $mark = null)
    {
        Yii::$app->getResponse()->format = 'json';
        $itemChildId = $mark == null ? ItemManage::find()  
                        ->select('item_child_id')  
                        ->where(['item_id'=> $id]) : null;         
        $errors = [];
        $items = [];
        try
        {
            $items = Item::find()  
                ->where(['parent_id'=>$id])
                ->andFilterWhere(['not in','id',$itemChildId])
                ->all(); 
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
     * Deletes an existing ItemManage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model =  $this->findModel($id);
        if ($model != null && $model->getIsNormal() && $model->getIsLeader() && $model->create_by == Yii::$app->user->id)
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
     * 获取行业
     * @return type
     */
    public function getItemType()
    {
        $itemType = ItemType::find()->with('itemManages')->all();
        return ArrayHelper::map($itemType, 'id', 'name');
    }
    
    /**
     * 获取层次/类型
     * @return type
     */
    public function getCollegesForSelect()
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getColleges(), 'id', 'name');
    }
    
    /**
     * 获取专业/工种
     * @param int $itemId
     */
    public function getFwItemForSelect($itemId)
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getChildren($itemId), 'id', 'name');
    }
    
    /**
     * 获取项目已存在的所有专业/工种
     * @param type $itemId
     * @return type
     */
    public function getExistedItemForSelect($itemId)
    {
        $itemChild = ItemManage::find() ->where(['item_id' => $itemId])
                ->with('itemChild')->with('item')->all();
        
        return ArrayHelper::map($itemChild, 'item_child_id', 'itemChild.name');
    }
    
    /**
     * 获取已经存在的单条专业/工种
     * @param type $itemChildId
     * @return type
     */
    public function getExistedItemChild($itemChildId)
    {
        $itemChild = ItemManage::find()->where(['item_child_id' => $itemChildId])
                ->with('itemChild')->all();
       
        return ArrayHelper::map($itemChild, 'item_child_id', 'itemChild.name');
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
}
