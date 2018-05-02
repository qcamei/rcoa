<?php

namespace frontend\modules\need\controllers;

use common\models\need\NeedContent;
use common\models\need\NeedContentPsd;
use common\models\need\NeedTask;
use common\models\need\searchs\NeedContentSearch;
use frontend\modules\need\utils\ActionUtils;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ContentController implements the CRUD actions for NeedContent model.
 */
class ContentController extends Controller
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
                    //'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
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
     * Lists all NeedContent models.
     * @return mixed
     */
    public function actionIndex()
    {
        $totalCost = 0;
        $need_task_id = ArrayHelper::getValue(Yii::$app->request->queryParams, 'need_task_id');
        $searchModel = new NeedContentSearch();
        $dataProvider = $searchModel->search(['need_task_id' => $need_task_id]);
        //计算总成本
        foreach($dataProvider->models as $model){
            /* @var $model NeedContent */
            $totalCost += $model->plan_num * $model->price;
        }
       
        return $this->renderAjax('index', [
            'need_task_id' => $need_task_id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'totalCost' => $totalCost,
        ]);
    }

    /**
     * Displays a single NeedContent model.
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
     * Creates a new NeedContent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param string $need_task_id
     * @param boolean  $isNewRecord     是否为新建数据，默认是true
     * @return mixed
     */
    public function actionCreate($need_task_id, $isNewRecord = true)
    {
        $totalCost = 0;
        $model = new NeedContent(['need_task_id' => $need_task_id]);
        $allContent = NeedContent::findAll(['need_task_id' => $need_task_id, 'is_del' => 0]);
        $workitemIds = [];
        $modelContents = [];
        //获取workitemIds 和计算实际内容总成本
        foreach($allContent as $modelItem){
            /* @var $modelItem NeedContent */
            $workitemIds[] =$modelItem->workitem_id . '_' . $modelItem->is_new; //获取workitemIds
            $totalCost += $modelItem->reality_num * $modelItem->price;  //计算实际内容总成本
            //组装已经存在的内容预计数量 or 实际数量
            $number = $isNewRecord ? $modelItem->plan_num : $modelItem->reality_num;
            $modelContents[$modelItem->workitem_type_id][$modelItem->workitem_id][$modelItem->is_new] = $number;
        }
        
        if (\Yii::$app->request->isPost) {
            if($isNewRecord){
                Yii::$app->getResponse()->format = 'json';
                return ActionUtils::getInstance()->CreateNeedContent($model, Yii::$app->request->post(), $workitemIds);
            }else{
                ActionUtils::getInstance()->UpdateNeedContent($model, Yii::$app->request->post(), $workitemIds);
                return $this->redirect(['task/view', 'id' => $model->need_task_id]);
            }
        }
       
        //返回页面的参数
        $params = [
            'need_task_id' => $model->need_task_id,
            'contentPsds' => NeedContentPsd::find()->all(),
            'modelContents' => $modelContents,
        ];
        
        if($isNewRecord){
            return $this->renderAjax('create', $params);
        }else{
            return $this->renderAjax('update', array_merge($params, ['totalCost' => $totalCost]));
        }
    }
    
    /**
     * Updates an existing NeedContent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if(\Yii::$app->request->isAjax) {
            Yii::$app->getResponse()->format = 'json';
            
            /** 开启事务 */
            $trans = Yii::$app->db->beginTransaction();
            try
            {  
                $model->plan_num = ArrayHelper::getValue(Yii::$app->request->post(), 'plan_num');
                if ($model->save()){
                    $trans->commit();  //提交事务
                    return [
                        'code' => 200,
                        'data' => '',
                        'message' => '修改成功！'
                    ];
                }
            }catch (Exception $ex) {
                $trans ->rollBack(); //回滚事务
                return [
                    'code' => 404,
                    'data' => '',
                    'message' => '修改失败！' . $ex->getMessage()
                ];
            }
        }

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * 提交验收
     * @param string $id
     * @return mixed
     */
    public function actionSubmit($id)
    {
        $model = NeedTask::findOne($id);
       
        if($model->receive_by == \Yii::$app->user->id){
            if(!($model->getIsDeveloping() || $model->getIsChangeCheck())){
                throw new NotFoundHttpException('该任务为' . $model->getStatusName());
            }
            if($model->is_del){
                throw new NotFoundHttpException('该任务已取消');
            }
        }else{
            throw new NotFoundHttpException('无权限访问');
        }
        
        if($model->load(Yii::$app->request->post())){
            ActionUtils::getInstance()->SubmitCheckNeedTask($model);
        }
        
        return $this->redirect(['task/view', 'id' => $model->id]);
    }
    
    /**
     * Deletes an existing NeedContent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_del = 1;
        
        Yii::$app->getResponse()->format = 'json';
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if (\Yii::$app->request->isPost && $model->update()){
                $trans->commit();  //提交事务
                return [
                    'code' => 200,
                    'data' => '',
                    'message' => '删除成功！'
                ];
            }
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
            return [
                'code' => 404,
                'data' => '',
                'message' => '删除失败！' . $ex->getMessage()
            ];
        }
    }

    /**
     * Finds the NeedContent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return NeedContent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NeedContent::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
