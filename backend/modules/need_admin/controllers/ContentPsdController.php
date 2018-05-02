<?php

namespace backend\modules\need_admin\controllers;

use backend\components\BaseController;
use common\models\need\NeedContentPsd;
use common\models\need\searchs\NeedContentPsdSearch;
use common\models\workitem\Workitem;
use common\models\workitem\WorkitemType;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/**
 * ContentPsdController implements the CRUD actions for NeedContentPsd model.
 */
class ContentPsdController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            //access验证是否有登录
            'access' => [
                'class' => AccessControl::class,
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
     * Lists all NeedContentPsd models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NeedContentPsdSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'contentType' => $this->getWorkitemTypeByContentPsd(),  //根据内容模板查找的类型
            'workitem' => $this->getWorkitemByContentPsd(),         //根据内容模板查找的工作项
        ]);
    }

    /**
     * Displays a single NeedContentPsd model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new NeedContentPsd model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new NeedContentPsd();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'workitemType' => $this->getAllWorkitemType(),      //所有类型
            'workitem' => $this->getAllWorkitem(),              //所有工作项
        ]);
    }

    /**
     * Updates an existing NeedContentPsd model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'workitemType' => $this->getAllWorkitemType(),      //所有类型
            'workitem' => $this->getAllWorkitem(),              //所有工作项
        ]);
    }

    /**
     * Deletes an existing NeedContentPsd model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the NeedContentPsd model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return NeedContentPsd the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NeedContentPsd::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
    /**
     * 查找所有类型
     * @return array
     */
    public function getAllWorkitemType()
    {
        $contentType = (new Query())->select(['id', 'name'])
                ->from(['WorkitemType' => WorkitemType::tableName()])
                ->all();
        
        return ArrayHelper::map($contentType, 'id', 'name');
    }
    
    /**
     * 根据内容模板查找类型
     * @return array
     */
    public function getWorkitemTypeByContentPsd()
    {
        $contentType = (new Query())
                ->select(['WorkitemType.id', 'WorkitemType.name'])
                ->from(['NeedContentPsd' => NeedContentPsd::tableName()])
                ->leftJoin(['WorkitemType' => WorkitemType::tableName()], 'WorkitemType.id = NeedContentPsd.workitem_type_id')
                ->all();

        return ArrayHelper::map($contentType, 'id', 'name');
    }

    /**
     * 查找所有工作项
     * @return array
     */
    public function getAllWorkitem()
    {
        $workitem = (new Query())->select(['id', 'name'])
                ->from(['Workitem' => Workitem::tableName()])
                ->all();
        
        return ArrayHelper::map($workitem, 'id', 'name');
    }
    
    /**
     * 根据内容模板查找工作项
     * @return array
     */
    public function getWorkitemByContentPsd()
    {
        $contentType = (new Query())
                ->select(['Workitem.id', 'Workitem.name'])
                ->from(['NeedContentPsd' => NeedContentPsd::tableName()])
                ->leftJoin(['Workitem' => Workitem::tableName()], 'Workitem.id = NeedContentPsd.workitem_id')
                ->all();
        
        return ArrayHelper::map($contentType, 'id', 'name');
    }
}
