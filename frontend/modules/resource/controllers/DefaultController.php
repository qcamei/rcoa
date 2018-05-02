<?php

namespace frontend\modules\resource\controllers;

use common\models\expert\Expert;
use common\models\resource\Resource;
use common\models\resource\ResourcePath;
use common\models\resource\ResourceType;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * DefaultController implements the CRUD actions for Resource model.
 */
class DefaultController extends Controller
{
    public function behaviors()
    {
        return [
            //验证delete时为post传值
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
     * Lists all Resource models.
     * @return mixed
     */
    public function actionIndex()
    {
       $model = ResourceType::find()->all();
        return $this->render('index', [
            'model' => $model,
        ]);
    }
    
    /**
     * 资源类型
     * @param type $id
     * @return type
     */
    public function actionType($id) {
        return $this->render('type', [
            'model' => $this->findModel(['type' => $id]),
            'resource' => $this->findResource(['type' => $id]),
        ]);
    }

    /**
     * Displays a single Resource model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderPartial('view', [
            'model' => $this->findModel($id),
            'path' => ResourcePath::find()->where(['r_id'=>$id])->all(),
        ]);
    }

    /**
     * 搜索关键字.
     * @param string  $key
     * @return mixed
     */
    public function actionSearchs()
    {
        $key = Yii::$app->request->queryParams['key'];
        $model = $this->findCategories($key);
        if($key == '' && $key == null)
            throw new UnauthorizedHttpException('无权操作！');
        
        return $this->render('searchs', [
            'categories' => $key,
            'modelKey' => $model,
        ]);
    }
    
    /**
     * Creates a new Resource model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Resource();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Resource model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
            ]);
        }
    }

    /**
     * Deletes an existing Resource model.
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
     * Finds the Resource model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Resource the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Resource::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Finds the Expert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $cons     条件
     * @param integer $jump     从第几条数据开始查询
     * @param integer $showNum  显示多少条数据
     * @return Expert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findResource($cons)
    {
        $resource = Resource::find()
                ->where($cons)
                //->offset($jump*$showNum)
                //->limit($showNum)
                //->asArray()
                ->all();
        if ($resource !== null) {
            return $resource;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 关键字搜索
     * @param type $fieldName 字段名
     * @param type $key 搜索关键字
     * @return type
     * @throws NotFoundHttpException
     */
    protected function findCategories($key){
        $categories = Resource::find()
                    ->FilterWhere(['like','name', $key]);
        $data = $categories -> all();
        if ($data !== null) {
            return $data;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
