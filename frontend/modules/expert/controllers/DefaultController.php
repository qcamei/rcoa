<?php

namespace frontend\modules\expert\controllers;

use common\models\expert\Expert;
use common\models\expert\ExpertType;
use Yii;
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
        $modelType = ExpertType::find()->all();
        return $this->render('index', [
            'modelType' => $modelType,
        ]);
    }

    /**
     * 显示专家类型.
     * @param integer $id
     * @return mixed
     */
    public function actionType($id)
    {   
        return $this->render('type', [
            'model' => $this->findModel(['type' => $id]),
            'pageCount' => $this->ExpertCount(['type' => $id]),   
            'modelExpert' => $this->findExpert(['type' => $id], 0, 10),
        ]);
               
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
    public function actionSearchs()
    {
        $fieldName = Yii::$app->request->queryParams['fieldName'];
        $key = Yii::$app->request->queryParams['key'];
        $model = $this->findCategories(($fieldName != 'all' ? $fieldName : $fieldName = null ), $key);
        if($key == '' && $key == null)
            throw new UnauthorizedHttpException('无权操作！');
        
        return $this->render('searchs', [
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
     * ajax下拉加载更多
     * @param integer $page    当前页
     * @param integer $showNum 每页显示数量
     * @return type json
     */
    public function actionDropdown()
    {
        $id = Yii::$app->getRequest()->getBodyParams();
        
        \Yii::$app->getResponse()->format = 'json';
        $url = Yii::$app->request->hostInfo;
        $post = Yii::$app->getRequest()->post();
        $page = $post['page'];          
        $showNum = $post['showNum'];    
        $modelExpert = $this->findExpert(['type' => $id],$page, $showNum);
       
        return [
            'result' => 0,      //是否请求正常 0:为不正常请求
            'data' => [
                'url' => $url,
                'page' => $page,
                'showNum' => $showNum,
                'modelExpert' =>$modelExpert,
            ],
        ];
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
     * @param integer $jump     从第几条数据开始查询
     * @param integer $showNum  显示多少条数据
     * @return Expert the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findExpert($cons, $jump, $showNum)
    {
        $modelExpert = Expert::find()
                ->where($cons)
                ->offset($jump*$showNum)
                ->limit($showNum)
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
     * 计算Expert数据总数
     * @param type $id
     * @return type
     */
    protected function ExpertCount($cons)
    {
        $count = Expert::find()
            ->where($cons)
            ->count();
        return $count;
    }

    /**
     * 关键字搜索
     
     * @param type $fieldName 字段名
     * @param type $key 搜索关键字
     * @return type
     * @throws NotFoundHttpException
     */
    protected function findCategories($fieldName = null, $key){
        $categories = Expert::find();
        $categories->joinWith(['user'])
            ->joinWith(['expertType']);
        if($fieldName != null){
           $categories->FilterWhere(['like', $fieldName, $key]); 
        }else{
            $categories->orFilterWhere(['like', 'job_title', $key])
                        ->orFilterWhere(['like', 'job_name', $key])
                        ->orFilterWhere(['like', 'nickname', $key])
                        ->orFilterWhere(['like', 'name', $key])
                        ->orFilterWhere(['like', 'employer', $key])
                        ->orFilterWhere(['like', 'attainment', $key]);
        }
        $data = $categories -> all();
        if ($data !== null) {
            return $data;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
