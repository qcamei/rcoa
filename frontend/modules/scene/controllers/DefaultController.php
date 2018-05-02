<?php

namespace frontend\modules\scene\controllers;

use common\models\scene\SceneSite;
use common\models\scene\searchs\SceneSiteSearch;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

/**
 * Default controller for the `scene` module
 */
class DefaultController extends Controller
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
            //access验证是否有登录
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ]
        ];
    }
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $this->layout = 'scene';
        $params = Yii::$app->request->queryParams;
        $search = new SceneSiteSearch();
        $sceneItem = $search->dataSearceh($params);             //场地搜索的结果
        $filterItem = $search->filterSearch($params);           //场地搜索的条件

        return $this->render('index',[
            'filter' => $params,
            'sceneItem' => $sceneItem,
            'filterItem' => $filterItem,
            'area' => $this->getArea(),
        ]);
    }
    
    /**
     * 查询场地所在区域
     * @return array
     */
    public function getArea() 
    {
        $manager = (new Query())
                ->select(['id', 'area'])
                ->from(['Site' => SceneSite::tableName()])
                ->where(['is_publish' => 1])            //过滤未发布的场地
                ->groupBy('area')
                ->all();
        
        return ArrayHelper::map($manager, 'id', 'area');
    }
        
}
