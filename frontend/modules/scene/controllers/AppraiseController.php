<?php

namespace frontend\modules\scene\controllers;

use common\models\scene\SceneAppraise;
use common\models\scene\SceneAppraiseTemplate;
use common\models\scene\SceneBookUser;
use common\models\scene\searchs\SceneAppraiseSearch;
use frontend\modules\scene\utils\SceneBookAction;
use Yii;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * AppraiseController implements the CRUD actions for SceneAppraise model.
 */
class AppraiseController extends Controller
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
        ];
    }

    /**
     * Lists all SceneAppraise models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SceneAppraiseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single SceneAppraise model.
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
     * Creates a new SceneAppraise model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $book_id = ArrayHelper::getValue(Yii::$app->request->queryParams, 'book_id');
        $model = new SceneAppraise(['book_id' => $book_id]);
        $searchModel = new SceneAppraiseSearch();
        
        if($this->getIsSceneBookUserRole($book_id)){
            if(!$model->book->is_transfer){
                if(!($model->book->getIsStausShootIng() || $model->book->getIsAppraise()))
                    throw new NotAcceptableHttpException('该任务状态为'.$model->book->getStatusName ().'！');
            }else{
                throw new NotAcceptableHttpException('该任务正在进行转让！');
            }
        } else {
            throw new NotAcceptableHttpException('无权限操作！');
        }
        
        if ($model->load(Yii::$app->request->post())) {
            SceneBookAction::getInstance()->CreateSceneAppraise(Yii::$app->request->post());
            return $this->redirect(['scene-book/view', 'id' => $model->book_id]);
        } else {
            return $this->renderAjax('create', [
                'model' => $model,
                'roleSubjects' => $this->getRoleSceneAppraiseTemplate(Yii::$app->request->queryParams),
                'appraiseResults' => $searchModel->search(['book_id' => $book_id]),
            ]);
        }
    }

    /**
     * Updates an existing SceneAppraise model.
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
            ]);
        }
    }

    /**
     * Deletes an existing SceneAppraise model.
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
     * Finds the SceneAppraise model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return SceneAppraise the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SceneAppraise::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 获取被评价角色和评价题目模版
     * @param array $params              
     * @return array
     */
    protected function getRoleSceneAppraiseTemplate($params)
    {
        $role = ArrayHelper::getValue($params, 'role');
        $bookRole = array_keys(SceneBookUser::$roleName);
        $diffRole = array_diff($bookRole, $role);
        if($diffRole == null){
            $userRole = $role;
        }else{
            $userRole = array_values($diffRole);
        }
        //评价题目
        $subjects = SceneAppraiseTemplate::find()->where(['role' => $userRole])->all();
        
        return [
            'role' => $userRole,
            'subject' => $subjects
        ];
    }
    
    /**
     * 获取当前用户是否为该任务的主预约用户
     * @param string $book_id
     * @return boolean
     */
    protected function getIsSceneBookUserRole($book_id)
    {
        $query = (new Query())->select(['SceneBookUser.user_id'])
            ->from(['SceneBookUser' => SceneBookUser::tableName()]);
        $query->where([
            'SceneBookUser.book_id' => $book_id, 
            'SceneBookUser.is_primary' => 1, 
            'SceneBookUser.is_delete' => 0
        ]);
        $userIds = ArrayHelper::getColumn($query->all(), 'user_id');
        
        if(in_array(\Yii::$app->user->id, $userIds)){
            return true;
        }else{
            return false;
        }
    }
}
