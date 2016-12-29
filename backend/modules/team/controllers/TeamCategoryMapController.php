<?php

namespace backend\modules\team\controllers;

use common\models\team\searchs\TeamCategoryMapSearch;
use common\models\team\Team;
use common\models\team\TeamCategory;
use common\models\team\TeamCategoryMap;
use wskeee\team\TeamMemberTool;
use Yii;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TeamCategoryMapController implements the CRUD actions for TeamCategoryMap model.
 */
class TeamCategoryMapController extends Controller
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
     * Lists all TeamCategoryMap models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TeamCategoryMapSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TeamCategoryMap model.
     * @param string $category_id
     * @param integer $team_id
     * @return mixed
     */
    public function actionView($category_id, $team_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($category_id, $team_id),
        ]);
    }

    /**
     * Creates a new TeamCategoryMap model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($category_id=null)
    {
        $post = Yii::$app->request->post();
        /* 避免重复数据 */
        if(isset($post['TeamCategoryMap']))
        {   
            $map = $post['TeamCategoryMap'];
            $model = TeamCategoryMap::findOne(['category_id'=>$map['category_id'],'team_id'=>$map['team_id']]);
        }
        if(!isset($model) || $model == null)
            $model = new TeamCategoryMap(['category_id'=>$category_id]);
        
        if ($model->load($post) && $model->save()) {
            //清除缓存
            TeamMemberTool::getInstance()->invalidateCache();
            if(isset($post['callback']))
                return $this->redirect($post['callback']);
            return $this->redirect(['view', 'category_id' => $model->category_id, 'team_id' => $model->team_id]);
        } else {
            //加载默认值
            $model->loadDefaultValues();
            
            return $this->render('create', [
                'model' => $model,
                'teams'=> $this->getTeams($category_id),
                'teamCategorys'=> $this->getCategorys(),
            ]);
        }
    }

    /**
     * Updates an existing TeamCategoryMap model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $category_id
     * @param integer $team_id
     * @return mixed
     */
    public function actionUpdate($category_id, $team_id, $callback=null)
    {
        $model = $this->findModel($category_id, $team_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //清缓存
            TeamMemberTool::getInstance()->invalidateCache();
            //指定跳转
            if($callback != null)
                return $this->redirect([$callback]);
            return $this->redirect(['view', 'category_id' => $model->category_id, 'team_id' => $model->team_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'teams'=> [$team_id => $model->team->name],
                'teamCategorys'=> [$category_id => $model->teamCategory->name],
            ]);
        }
    }

    /**
     * Deletes an existing TeamCategoryMap model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $category_id
     * @param integer $team_id
     * @return mixed
     */
    public function actionDelete($category_id, $team_id, $callback=null)
    {
        $model = $this->findModel($category_id, $team_id);
        $model->is_delete = 'Y';
        if($model->save()){
            //清缓存
            TeamMemberTool::getInstance()->invalidateCache();
        }
        return $this->redirect([$callback ? $callback : 'index']);
    }

    /**
     * Finds the TeamCategoryMap model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $category_id
     * @param integer $team_id
     * @return TeamCategoryMap the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($category_id, $team_id)
    {
        if (($model = TeamCategoryMap::findOne(['category_id' => $category_id, 'team_id' => $team_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 获取类别下的团队
     * @param type $category_id         类别id
     * @param array $onlyCanchoice      仅仅可以选择的团队，已经选择的不出现
     */
    private function getTeams($category_id,$onlyCanchoice=true){
        //获取已添加的团队id
        $hasTeams = ArrayHelper::getColumn((new Query())
                ->select(['team_id'])
                ->from(TeamCategoryMap::tableName())
                ->where(['category_id'=>$category_id,'is_delete'=>'N'])
                ->all(),'team_id');

        //获取可以添加的团队
        $teams = (new Query())
                ->select(['id','name'])
                ->from(Team::tableName())
                ->where(['is_delete'=>Team::CANCEL_DELETE])
                ->andFilterWhere(['not in','id',$onlyCanchoice ? $hasTeams : null])
                ->orderBy('index asc')
                ->all();
        return ArrayHelper::map($teams, 'id', 'name');
    }
    
    /**
     * 获取团队分类
     * @return array [id=>name]
     */
    private function getCategorys(){
        //获取所有分类
        $categorys = (new Query())
                ->select(['id','name'])
                ->from(TeamCategory::tableName())
                ->where(['is_delete'=>'N'])
                ->all();
        return ArrayHelper::map($categorys, 'id', 'name');
    }
}
