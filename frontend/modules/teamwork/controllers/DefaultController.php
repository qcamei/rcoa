<?php

namespace frontend\modules\teamwork\controllers;

use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\teamwork\CourseManage;
use common\models\teamwork\ItemManage;
use frontend\modules\teamwork\utils\TeamworkTool;
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
        $twTool = TeamworkTool::getInstance();
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
        $twTool = TeamworkTool::getInstance();
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
        $twTool = TeamworkTool::getInstance();
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
        /* @var $twTool TeamworkTool */
        $twTool = TeamworkTool::getInstance();
        /* @var $model ItemManage */
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
        $twTool = TeamworkTool::getInstance();
        if(!($twTool->getIsAuthority('is_leader', 'Y') || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
            throw new NotAcceptableHttpException('无权限操作！');
        
        $model = new ItemManage();
        $model->loadDefaultValues();
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
        $twTool = TeamworkTool::getInstance();
        if(!($twTool->getIsAuthority('is_leader', 'Y') || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
            throw new NotAcceptableHttpException('无权限操作！');
        
        $model = $this->findModel($id);
        $itemChild = $this->getItemChild($model->item_id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'itemType' => $this->getItemType(),
                'items' => $this->getCollegesForSelect(),
                'itemChilds' => ArrayHelper::merge([$model->item_child_id => $model->itemChild->name], $itemChild),
            ]);
        }
    }
    
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
                ->andFilterWhere(['NOT IN','id',$itemChildId])
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
        if ($model != null && $model->getIsNormal() && $model->getIsAuthority('is_leader', 'Y') && $model->create_by == Yii::$app->user->id)
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
    public function getItemChild($itemId)
    {
        $itemChild = ItemManage::find() ->where(['item_id' => $itemId])
                ->with('itemChild')->with('item')->all();
        
        $item = Item::find()  
                ->where(['AND', 
                   ['parent_id'=>$itemId], 
                   ['NOT IN','id', ArrayHelper::getColumn($itemChild, 'item_child_id')]
                ])
                ->all(); 
        
        return ArrayHelper::map($item, 'id', 'name');
    }
}
