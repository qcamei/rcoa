<?php

namespace backend\modules\demand\controllers;

use common\models\demand\DemandTaskAuditor;
use common\models\demand\searchs\DemandTaskAuditorSearch;
use common\models\expert\Expert;
use common\models\team\Team;
use common\models\User;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;



/**
 * DefaultController implements the CRUD actions for DemandTaskAuditor model.
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
            ],
        ];
    }

    /**
     * Lists all DemandTaskAuditor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DemandTaskAuditorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DemandTaskAuditor model.
     * @param integer $team_id
     * @param string  $u_id
     * @return mixed
     */
    public function actionView($team_id, $u_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($team_id, $u_id),
        ]);
    }

    /**
     * Creates a new DemandTaskAuditor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DemandTaskAuditor();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'team_id' => $model->team_id, 'u_id' => $model->u_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'teams' => $this->getTeam(),
                'users' => $this->getUser(),
            ]);
        }
    }

    /**
     * Updates an existing DemandTaskAuditor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $team_id
     * @param string  $u_id
     * @return mixed
     */
    public function actionUpdate($team_id, $u_id)
    {
        $model = $this->findModel($team_id, $u_id);
       
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'team_id' => $model->team_id, 'u_id' => $model->u_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'teams' => $this->getTeam(),
                'users' => $this->getUser(),
            ]);
        }
    }

    /**
     * Deletes an existing DemandTaskAuditor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $team_id
     * @param integer $u_id
     * @return mixed
     */
    public function actionDelete($team_id, $u_id)
    {
        $this->findModel($team_id, $u_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DemandTaskAuditor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $team_id
     * @param string  $u_id
     * @return DemandTaskAuditor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($team_id, $u_id)
    {
        if (($model = DemandTaskAuditor::findOne(['team_id' => $team_id, 'u_id' => $u_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('rcoa', 'The requested page does not exist.'));
        }
    }
    
    /**
     * 获取团队
     * @return array
     */
    public function getTeam()
    {
        return ArrayHelper::map(Team::find()->all(), 'id', 'name');
    }
    
    /**
     * 获取所有用户
     * @return array
     */
    public function getAssignUsers()
    {
        $expert = (new Query())
                ->select(['u_id', 'nickname'])
                ->from(Expert::tableName())
                ->leftJoin(['User' => User::tableName()], 'User.id = u_id')
                ->all();
        $user = (new Query())
                ->select(['id', 'nickname'])
                ->from(User::tableName())
                ->where(['not in', 'id', ArrayHelper::getColumn($expert, 'u_id')])
                ->all();
                
        return ArrayHelper::map($user, 'id', 'nickname');
    }
}
