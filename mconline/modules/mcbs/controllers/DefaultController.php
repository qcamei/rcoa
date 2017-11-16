<?php

namespace mconline\modules\mcbs\controllers;

use common\models\mconline\McbsCourse;
use common\models\mconline\searchs\McbsCourseSearch;
use common\models\User;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\ItemType;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * DefaultController implements the CRUD actions for McbsCourse model.
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index','attention','lookup','create','view','update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all McbsCourse models.
     * @param integer $page                     页数
     * @return mixed
     */
    public function actionIndex()
    {
        $searchResult = new McbsCourseSearch();
        $results = $searchResult->searchMyCourse(Yii::$app->request->queryParams);
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $results['result'],
        ]);  
        
        return $this->render('index', [
            'param' => $results['param'],
            'dataProvider' => $dataProvider,
            'totalCount' => $results['totalCount'],
        ]);
    }

    /**
     * Displays a single McbsCourse model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new McbsCourse model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new McbsCourse(['id' => md5(rand(1,10000) + time())]);
        $model->loadDefaultValues();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'itemTypes' => $this->getItemTypes(),
                'items' => $this->getCollegesForSelects(),
                'itemChilds' => !empty($model->item_id) ? $this->getChildrens($model->item_id) : [],
                'courses' => !empty($model->item_child_id) ? $this->getChildrens($model->item_child_id) : [],
            ]);
        }
    }

    /**
     * Updates an existing McbsCourse model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'itemTypes' => $this->getItemTypes(),
                'items' => $this->getCollegesForSelects(),
                'itemChilds' => $this->getChildrens($model->item_id),
                'courses' => $this->getChildrens($model->item_child_id),
            ]);
        }
    }

    /**
     * Deletes an existing McbsCourse model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * 跳转到我的关注
     * @return array
     */
    public function actionAttention()
    {
        $searchResult = new McbsCourseSearch();
        $results = $searchResult->searchMyAttention(Yii::$app->request->queryParams);
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $results['result'],
        ]);  
        
        return $this->render('attention', [
            'param' => $results['param'],
            'dataProvider' => $dataProvider,
            'totalCount' => $results['totalCount'],
        ]);
    }
    
    /**
     * 跳转到查找课程
     * @return array
     */
    public function actionLookup()
    {
        $searchResult = new McbsCourseSearch();
        $results = $searchResult->searchCourseInfo(Yii::$app->request->queryParams);
        
        $dataProvider = new ArrayDataProvider([
            'allModels' => $results['result'],
        ]);  
        
        return $this->render('lookup', [
            'param' => $results['param'],
            'dataProvider' => $dataProvider,
            'totalCount' => $results['totalCount'],
            //条件
            'itemTypes' => $this->getItemTypes(),
            'items' => $this->getCollegesForSelects(),
            'itemChilds' => $this->getChildrens(ArrayHelper::getValue($results['param'], 'item_id')),
            'createBys' => ArrayHelper::getValue($this->getCourseCreateBy(), 'createBy'),
        ]);
    }
    
    /**
     * Finds the McbsCourse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return McbsCourse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = McbsCourse::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 获取所有行业
     * @return type
     */
    public function getItemTypes()
    {
        $itemType = ItemType::find()->with('itemManages')->all();
        return ArrayHelper::map($itemType, 'id', 'name');
    }
    
    /**
     * 获取所有层次/类型
     * @return type
     */
    public function getCollegesForSelects()
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getColleges(), 'id', 'name');
    }
    
    /**
     * 获取所有专业/工种 or 课程
     * @param type $itemId              层次/类型ID
     * @return type
     */
    protected function getChildrens($itemId)
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getChildren($itemId), 'id', 'name');
    }
    
    /**
     * 获取所有课程的创建者
     * @return array
     */
    public function getCourseCreateBy()
    {
        $query = (new Query())
                ->select(['McbsCourse.id', "CONCAT(McbsCourse.create_by, '_', User.nickname) AS create_by"])
                ->from(['McbsCourse' => McbsCourse::tableName()])
                ->leftJoin(['User' => User::tableName()], 'User.id = McbsCourse.create_by');
        
        $results = (new Query())
                ->select(['CreateBy.create_by'])
                ->from(['CreateBy' => $query])
                ->all();
        
        $createBys = [];
        foreach ($results as $item) {
            $createBys[explode('_', $item['create_by'])[0]] = isset(explode('_', $item['create_by'])[1]) ? explode('_', $item['create_by'])[1] : '';
        }
        
        return [
            'createBy' => array_filter($createBys),
        ];
    }
}
