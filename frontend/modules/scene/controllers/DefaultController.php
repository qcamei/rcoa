<?php

namespace frontend\modules\scene\controllers;

use common\models\scene\SceneBook;
use common\models\scene\SceneSite;
use common\models\scene\searchs\SceneSiteSearch;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Default controller for the `scene` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $search = new SceneSiteSearch();
        $sceneItem = $search->dataSearceh($params);             //场地搜索的结果
        $filterItem = $search->filterSearch($params);           //场地搜索的条件
        
        return $this->render('index',[
            'sceneItem' => $sceneItem,
            'filterItem' => $filterItem,
            'area' => $this->getArea(),
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

    public function actionSceneList()
    {
        $params = Yii::$app->request->queryParams;
        $search = new SceneSiteSearch();
        $sceneItem = $search->dataSearceh($params);             //场地搜索的结果
        
        return $this->render('scene-list',[
            'sceneItem' => $sceneItem,
        ]);
    }

        /**
     * 查询场地所在区域
     * @return array
     */
    public function getArea() 
    {
        $manager = (new Query())
                ->select(['area'])
                ->from(['Site' => SceneSite::tableName()])
                ->all();
        
        return ArrayHelper::map($manager, 'area', 'area');
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
    
}
