<?php

namespace frontend\modules\need\controllers;

use common\models\need\NeedTaskLog;
use common\models\need\searchs\NeedTaskLogSearch;
use common\models\User;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * LogController implements the CRUD actions for NeedTaskLog model.
 */
class LogController extends Controller
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
     * Lists all NeedTaskLog models.
     * @return mixed
     */
    public function actionIndex($need_task_id)
    {
        $searchModel = new NeedTaskLogSearch();
        $results = $searchModel->search(Yii::$app->request->queryParams);
        $logs = $this->getNeedTaskLogs($need_task_id);
        
        if(Yii::$app->request->isPost){
            Yii::$app->getResponse()->format = 'json';
            return [
                'code'=> $results ? 200 : 404,
                'data' => Yii::$app->request->post(),
                'url' => Url::to(array_merge(['index'], Yii::$app->request->post())),
                'message' => ''
            ];
        }
        
        return $this->renderAjax('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $results['dataProvider'],
            'filter' => $results['filter'],
            'action' => $logs['action'],
            'title' => $logs['title'],
            'createdBy' => $logs['created_by'],
        ]);
    }

    /**
     * Displays a single NeedTaskLog model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderAjax('view', [
            'model' => $this->findModel($id),
        ]);
    }
    
    /**
     * Finds the NeedTaskLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return NeedTaskLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NeedTaskLog::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    
    /**
     * 获取该任务下的所有记录
     * @param string $need_task_id                             
     * @return array
     */
    protected function getNeedTaskLogs($need_task_id)
    {
        $query = (new Query())->select(['action','title','created_by', 'User.nickname']);
        $query->from(NeedTaskLog::tableName());
        $query->leftJoin(['User' => User::tableName()], 'User.id = created_by');
        $query->where(['need_task_id' => $need_task_id]);
        
        return [
            'action' => ArrayHelper::map($query->all(), 'action', 'action'),
            'title' => ArrayHelper::map($query->all(), 'title', 'title'),
            'created_by' => ArrayHelper::map($query->all(), 'created_by', 'nickname'),
        ];
    }
}
