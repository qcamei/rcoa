<?php

namespace frontend\modules\expert\controllers;

use common\models\expert\Expert;
use common\models\expert\ExpertType;
use Yii;
use yii\data\ArrayDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * DefaultController implements the CRUD actions for Expert model.
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
        ];
    }

    /**
     * Lists all Expert models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = ExpertType::find()->all();
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * 显示专家类型.
     * @param integer $id
     * @return mixed
     */
    public function actionType($id)
    {   
        /** 是否为ajax请求 */
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
            \Yii::$app->getResponse()->format = 'json';
            $post = Yii::$app->getRequest()->post();
            $page = $post['page'];          //当前页
            $pageNum = $post['pageNum'];    //每页显示数量
            $modelExpert = $this->findExpert(['type' => $id],$page, $pageNum);
            return [
                'result' => 1,      //是否请求正常 1:为正常请求
                'data' => [
                    'page' => $page,
                    'pageNum' => $pageNum,
                    'modelExpert' =>$modelExpert,
                ],
            ];
        }  else {
            /** 数据总数 */
            $pageCount = Expert::find()
                ->where(['type' => $id])
                ->count();
            
            return $this->render('type', [
                'model' => $this->findModel(['type' => $id]),
                'pageCount' => $pageCount,   
                'modelExpert' => $this->findExpert(['type' => $id], 0, 15),
            ]);
        }
        
        
    }
    
    /**
     * Displays a single Expert model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $expertProjects = $model->expertProjects;
        return $this->render('view', [
            'model' => $model,
            'expertProjects' => $expertProjects,
        ]);
    }
    
    /**
     * 搜索关键字.
     * @param string  $key
     * @return mixed
     */
    public function actionCategories($key)
    {
        $key = Yii::$app->request->queryParams['key'];
        $model = $this->findCategories($key);
        if($key == '' && $key == null)
            throw new UnauthorizedHttpException('无权操作！');
        
        return $this->render('categories', [
            'categories' => $key,
            'modelKey' => $model,
        ]);
    }
    
    /**
     * Creates a new Expert model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Expert();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->u_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Expert model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->u_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Expert model.
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
     * 用ajax调用专家库 
     * @param type $id
     * @return type
     */
    public function actionSearch($id)
    {
        \Yii::$app->getResponse()->format = 'json';
        
        $expert = Expert::findOne($id);
        
        return [
            'result' => 0/1,
            'data'=>[
                'img' => $expert->personal_image,
                'phone' => $expert->user->phone,
                'email' => $expert->user->email,
            ]
        ];
    }
    
    /**
     * Finds the Expert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Expert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Expert::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Finds the Expert model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $cons     条件
     * @param integer $page     当前分页数
     * @param integer $pageNum  每页显示数量
     * @return Expert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findExpert($cons, $page, $pageNum)
    {
        $modelExpert = Expert::find()
                ->where($cons)
                ->offset($page*$pageNum)
                ->limit($pageNum)
                ->with('user')
                ->asArray()
                ->all();
        if ($modelExpert !== null) {
            return $modelExpert;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    /**
     * 关键字搜索
     * @param type $key
     * @return type
     * @throws NotFoundHttpException
     */
    protected function findCategories($key){
        $categories = Expert::find();
        $categories->joinWith(['user'])
            ->joinWith(['expertType']);
        $categories->orFilterWhere(['like', 'job_title', $key])
                ->orFilterWhere(['like', 'job_name', $key])
                ->orFilterWhere(['like', 'employer', $key])
                ->orFilterWhere(['like', 'attainment', $key])
                ->orFilterWhere(['like', 'nickname', $key])
                ->orFilterWhere(['like', 'name', $key]);
        $data = $categories -> all();
        if ($data !== null) {
            return $data;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
