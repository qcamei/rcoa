<?php

namespace frontend\modules\worksystem\controllers;

use common\models\worksystem\WorksystemContent;
use common\models\worksystem\WorksystemContentinfo;
use common\models\worksystem\WorksystemTask;
use frontend\modules\worksystem\utils\WorksystemAction;
use frontend\modules\worksystem\utils\WorksystemTool;
use wskeee\rbac\RbacName;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * ContentinfoController implements the CRUD actions for WorksystemContentinfo model.
 */
class ContentinfoController extends Controller
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
     * Lists all WorksystemContentinfo models.
     * @return mixed
     */
    public function actionIndex()
    {
        if(\Yii::$app->request->isPost){
            Yii::$app->getResponse()->format = 'json';
            $post = Yii::$app->request->post();
            $message = '';          //消息
            $type = 0;              //是否成功：0为否，1为是
            $items = [];            //数据
            $errors = [];           //错误
            $content = $this->findWorksystemContent(ArrayHelper::getValue($post, 'content_id'));
            try
            {
                if($content != null){
                    $items = $content;
                    $type = 1;
                }else{
                    $message = '未找到相关数据';
                }

            } catch (Exception $ex) {
                $errors [] = $ex->getMessage();
            }
            return [
                'type'=> $type,
                'data' => $items,
                'message' => $message,
                'error' => $errors
            ];
        }
        
        return $this->renderAjax('_form');
    }

    /**
     * Displays a single WorksystemContentinfo model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new WorksystemContentinfo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($task_type_id)
    {
        
        /* $model = new WorksystemContentinfo();
         if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            
        }*/
        return $this->renderAjax('create', [
            //'model' => $model,
            'typeNames' => $this->getWorksystemContentTypeNames($task_type_id),
        ]);
    }

    /**
     * Updates an existing WorksystemContentinfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($task_id)
    {
        /* $model = $this->findModel($id);
         if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            
        }*/
        $_wsTaskModel = $this->findWorksystemTask($task_id);
        return $this->renderAjax('update', [
            'model' => $_wsTaskModel,
            'infos' => $this->getWorksystemContentinfos($task_id),
        ]);
    }
    
    /**
     * Deletes an existing WorksystemContentinfo model.
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
     * Submit an existing WorksystemContentinfo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param WorksystemAction $_wsAction
     * @param WorksystemTool $_wsTool
     * @param integer $id
     * @return mixed
     */
    public function actionSubmit($task_id)
    {
        $_wsTaskModel = $this->findWorksystemTask($task_id);
        $_wsTool = WorksystemTool::getInstance();
        $is_producer = $_wsTool->getIsProducer($_wsTaskModel->id);
        if($is_producer){
            if(!($_wsTaskModel->getIsStatusWorking() || $_wsTaskModel->getIsStatusUpdateing()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        }else{
            throw new NotAcceptableHttpException('无权限操作！');
        }
        $post = Yii::$app->request->post();
        $_wsAction = WorksystemAction::getInstance();
        
        if ($_wsTaskModel->load($post)) {
            $_wsAction->SubmitAcceptanceTask($_wsTaskModel, $post);
            return $this->redirect(['task/index', 'producer' => Yii::$app->user->id, 'status' => WorksystemTask::STATUS_DEFAULT, 'mark' => false]);
        } else {
            return $this->renderAjax('_submit', [
                'model' => $_wsTaskModel,
                'infos' => $this->getWorksystemContentinfos($task_id),
            ]);
        }
    }

    /**
     * Finds the WorksystemContentinfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorksystemContentinfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorksystemContentinfo::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Finds the WorksystemContentinfo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorksystemContentinfo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findWorksystemTask($id)
    {
        if (($model = WorksystemTask::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Finds the WorksystemContent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorksystemContent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findWorksystemContent($id)
    {
        if (($model = WorksystemContent::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 获取所有工作系统基础内容信息类型
     * @param integer $taskTypeId                      工作系统任务分类id
     * @return type
     */
    public function getWorksystemContentTypeNames($taskTypeId)
    {
        $contents = (new Query())
                ->select(['Ws_content.id', 'Ws_content.type_name'])
                ->from(['Ws_content' => WorksystemContent::tableName()])
                ->where(['Ws_content.worksystem_task_type_id' => $taskTypeId])
                ->all();
        
        return ArrayHelper::map($contents, 'id', 'type_name');
    }
    
    /**
     * 获取所有工作系统任务内容信息
     * @param integer $taskId                   工作系统任务id
     * @return type
     */
    public function getWorksystemContentinfos($taskId)
    {
        $infos = (new Query())
                ->select([
                    'Ws_Contentinfos.id AS info_id', 'Ws_Contentinfos.worksystem_content_id', 'Ws_Contentinfos.is_new', 'Ws_Contentinfos.price',
                    'Ws_Contentinfos.budget_number', 'Ws_Contentinfos.budget_cost', 'Ws_Contentinfos.reality_number', 'Ws_Contentinfos.reality_cost',
                    'Ws_Content.id', 'Ws_Content.type_name', 'Ws_Content.icon', 'Ws_Content.price_new', 
                    'Ws_Content.price_remould', 'Ws_Content.unit'
                ])
                ->from(['Ws_Contentinfos' => WorksystemContentinfo::tableName()])
                ->leftJoin(['Ws_Content' => WorksystemContent::tableName()], 'Ws_Content.id = Ws_Contentinfos.worksystem_content_id')
                ->where(['Ws_Contentinfos.worksystem_task_id' => $taskId])
                ->all();
        
        return $infos; 
    }
}
