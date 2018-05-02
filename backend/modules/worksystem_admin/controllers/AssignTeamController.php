<?php

namespace backend\modules\worksystem_admin\controllers;

use common\models\expert\Expert;
use common\models\team\Team;
use common\models\User;
use common\models\worksystem\searchs\WorksystemAssignTeamSearch;
use common\models\worksystem\WorksystemAssignTeam;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * AssignTeamController implements the CRUD actions for WorksystemAssignTeam model.
 */
class AssignTeamController extends Controller
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
     * Lists all WorksystemAssignTeam models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WorksystemAssignTeamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WorksystemAssignTeam model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new WorksystemAssignTeam model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WorksystemAssignTeam();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'teams' => $this->getAssignTeams(),
                'users' => $this->getAssignUsers(),
            ]);
        }
    }

    /**
     * Updates an existing WorksystemAssignTeam model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
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
                'teams' => $this->getAssignTeams(),
                'users' => $this->getAssignUsers(),
            ]);
        }
    }

    /**
     * Deletes an existing WorksystemAssignTeam model.
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
     * Finds the WorksystemAssignTeam model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WorksystemAssignTeam the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorksystemAssignTeam::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 获取所有团队
     * @return array
     */
    public function getAssignTeams()
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
