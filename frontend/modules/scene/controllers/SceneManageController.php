<?php

namespace frontend\modules\scene\controllers;

use common\models\scene\SceneBook;
use common\models\scene\SceneBookUser;
use common\models\scene\SceneSite;
use common\models\scene\SceneSiteDisable;
use common\models\scene\searchs\SceneSiteDisableSearch;
use common\models\scene\searchs\SceneSiteSearch;
use common\models\User;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * SceneManage controller for the `scene` module
 */
class SceneManageController extends Controller
{
    public $layout = 'scene';
    
    /**
     * Renders View the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $search = new SceneSiteSearch();
        $sceneItem = $search->dataSearceh($params);             //场地搜索的结果
        
        return $this->render('index',[
            'sceneItem' => $sceneItem,
        ]);
    }
    
    /**
     * Renders View the index view for the module
     * @return string
     */
    public function actionView($id)
    {
        return $this->render('view',[
            'sceneData' => $this->getSceneData($id),
            'registerNum' => count(SceneBook::findAll(['site_id' => $id])),
        ]);
    }
    
    /**
     * Renders View the index view for the module
     * @return string
     */
    public function actionDisable()
    {
        $params = Yii::$app->request->queryParams;
        $searchModel = new SceneSiteDisableSearch();
        $sceneSite = $this->getSceneSite();
        $firstSite = array_keys(reset($sceneSite));       //获取场景的第一个场地
        $results = $searchModel->searchModel($params, $firstSite);
        
        $site_id = ArrayHelper::getValue($params, 'site_id');       //场地ID
        $date = ArrayHelper::getValue($params, 'date');             //日期
        $time_index = ArrayHelper::getValue($params, 'time_index'); //时段
//        var_dump($this->getSiteDisable($site_id, $date, $time_index));exit;
        return $this->render('disable',[
            //'searchModel' => $searchModel,
            'siteDisable' => $this->getSiteDisable($site_id, $date, $time_index),
            'filter' => $results['filters'],
            'dataProvider' => $results['data'],
            'sceneSite' => $sceneSite,
            'firstSite' => $firstSite,
            'sceneBookUser' => $this->getExistSceneBookUserAll(ArrayHelper::getColumn($results['data']->allModels, 'id')),
        ]);
    }

    /**
     * 禁用该日期时段下的场地
     * @param integer $site_id      场地ID
     * @param type $date            日期
     * @param integer $time_index   时段
     * @return mixed
     */
    public function actionSiteDiable($site_id, $date, $time_index)
    {
//        $saveData = SceneSiteDisable::findOne(['site_id' => $site_id, 'date' => $date, 'time_index' => $time_index]);
//        if($saveData == null) {
            $saveData = new SceneSiteDisable(['site_id' => $site_id, 'date' => $date, 'time_index' => $time_index, 'is_disable' => 1]);
            $saveData->save();
            return $this->redirect(['disable']);
//        }
        
    }

    /**
     * ExitCreate a new SceneBook model.
     * 清除临时预约数据
     * @return mixed
     */
    public function actionExitCreate($id, $date_switch = 'month')
    {
        $model = $this->findModel($id);
        if($model->created_by == Yii::$app->user->id){
            $model->setScenario(SceneBook::SCENARIO_TEMP_CREATE);
            $model->status = SceneBook::STATUS_DEFAULT;
            $model->save();
            if(Yii::$app->request->isAjax){
                Yii::$app->getResponse()->format = 'json';
                return [
                    'code' => 200,
                    'data' => [],
                    'message' => '执行成功'
                ];
            }else{
                return $this->redirect(array_merge(['index', 'date_switch' => $date_switch]));
            }
        }
      
    }
    
    /**
     * Finds the SceneBook model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SceneBook the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SceneSiteDisable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 查询场地信息
     * @param integer $id
     * @return array
     */
    public function getSceneData($id)
    {
        $sceneData = (new Query())
                ->select(['SceneSite.id', 'SceneSite.name', 'SceneSite.op_type', 'SceneSite.area', 'SceneSite.price',
                        'SceneSite.contact', 'SceneSite.address', 'SceneSite.des', 'SceneSite.content',
                        'SceneSite.content_type', 'SceneSite.img_path', 'AsText(location)'])
                ->from(['SceneSite' => SceneSite::tableName()])
                ->where(['id' => $id])
                ->one();

        return $sceneData;
    }
    
    public function getSiteDisable($site_id, $date, $time_index)
    {
        $query = (new Query())->select(['site_id', 'date', 'time_index', 'is_disable'])
                ->from(SceneSiteDisable::tableName());
        $query->filterWhere([
            'site_id' => $site_id,
            'date' => $date,
            'time_index' => $time_index,]);
        $result = $query->one();
        var_dump($result);exit;
        return $result;
    }

    /**
     * 获取场景场地
     * @param integer $site_id
     * @return array
     */
    public function getSceneSite($site_id = null)
    {
        $query = (new Query())->select(['id', 'name', 'area', 'content_type'])
            ->from(SceneSite::tableName());
        $query->filterWhere(['id' => $site_id]);
        $results = $query->all();
        
        if($site_id == null){
            return ArrayHelper::map($results, 'id', 'name', 'area');
        }else {
            $contentTypeMap = [];
            $content_type = isset($results[0]) ? ArrayHelper::getValue($results[0], 'content_type') : "";
            $contents = explode(',', $content_type);
            foreach ($contents as $value) {
                $contentTypeMap[$value] = $value;
            }
           
            return $contentTypeMap;
        }
    }
    
    /**
     * 获取场景预约任务已存在的所有接洽人or摄影师
     * @param string|array $book_id
     * @return array
     */
    protected function getExistSceneBookUserAll($book_id)
    {
        $results = [];
        $query = (new Query())->select([
            'SceneBookUser.book_id', 'SceneBookUser.role', 'User.id', 'User.nickname','SceneBookUser.is_primary', 'User.phone'
        ])->from(['SceneBookUser' => SceneBookUser::tableName()]);
        $query->leftJoin(['User' => User::tableName()], 'User.id = SceneBookUser.user_id AND User.status = 10');
        $query->where(['SceneBookUser.book_id' => $book_id, 'SceneBookUser.is_delete' => 0]);
        $query->groupBy('SceneBookUser.id');
        $query->orderBy(['SceneBookUser.sort_order' => SORT_ASC]);
        //组装返回的预约任务用户信息
        foreach ($query->all() as $value) {
            $book_id = $value['book_id'];
            unset($value['book_id']);
            $results[$book_id][] = $value;
        }
       
        return $results;
    }
}
