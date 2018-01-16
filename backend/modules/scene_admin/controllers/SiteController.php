<?php

namespace backend\modules\scene_admin\controllers;

use backend\components\BaseController;
use common\models\scene\SceneSite;
use common\models\scene\searchs\SceneSiteSearch;
use common\models\User;
use Yii;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\NotFoundHttpException;

/**
 * SiteController implements the CRUD actions for SceneSite model.
 */
class SiteController extends BaseController
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
     * Lists all SceneSite models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SceneSiteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'area' => $this->getArea(),
            'manager' => $this->getManager(),
        ]);
    }

    /**
     * Displays a single SceneSite model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'point' => $this->getPoint($id),
        ]);
    }

    /**
     * Creates a new SceneSite model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new SceneSite();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'area' => $this->getArea(),
                'manager' => $this->getManager(),
            ]);
        }
    }

    /**
     * Updates an existing SceneSite model.
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
                'area' => $this->getArea(),
                'manager' => $this->getManager(),
            ]);
        }
    }

    /**
     * Deletes an existing SceneSite model.
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
     * Function output the site that you selected.
     * @param int $parent_id
     * @param int $level
     */
    public function actionSearchAddress($parent_id, $level = 0)
    {
        $model = new SceneSite();
        $data = $model->getCityList($parent_id);

        if($level == 1){
            $aa="--选择市--";
        }elseif($level == 2 && $data){
            $aa = "--选择区--";
        }elseif ($level == 3 && $data) {
            $aa = "--选择镇--";
        }

        echo Html::tag('option', $aa, ['value'=>'empty']) ;

        foreach($data as $value => $name)
        {
            echo Html::tag('option', Html::encode($name), ['value' => $value]);
        }
    }

    /**
     * Finds the SceneSite model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SceneSite the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = SceneSite::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * 查询经纬度
     * @param integer $id
     * @return type
     */
    public function getPoint($id){
        $point = (new Query())
                ->select(['AsText(location)'])
                ->from(['Site' => SceneSite::tableName()])
                ->where(['id' => $id])
                ->one();

        return $point;
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
     * 查询管理员
     * @return array
     */
    public function getManager() 
    {
        $manager = (new Query())
                ->select(['Site.manager_id'])
                ->from(['Site' => SceneSite::tableName()])
                //关联查询管理员
                ->leftJoin(['CreateBy' => User::tableName()], 'CreateBy.id = Site.manager_id')
                ->addSelect(['CreateBy.nickname AS username'])
                ->distinct()
                ->all();
        
        return ArrayHelper::map($manager, 'manager_id', 'username');
    }
    
}
