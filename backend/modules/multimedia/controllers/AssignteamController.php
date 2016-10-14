<?php

namespace backend\modules\multimedia\controllers;

use common\models\expert\Expert;
use common\models\multimedia\MultimediaAssignTeam;
use common\models\multimedia\searchs\MultimediaAssignTeamSearch;
use common\models\team\Team;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * AssignteamController implements the CRUD actions for MultimediaAssignTeam model.
 */
class AssignteamController extends Controller
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
     * Lists all MultimediaAssignTeam models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MultimediaAssignTeamSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MultimediaAssignTeam model.
     * @param integer $team_id
     * @param string $u_id
     * @return mixed
     */
    public function actionView($team_id, $u_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($team_id, $u_id),
        ]);
    }

    /**
     * Creates a new MultimediaAssignTeam model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new MultimediaAssignTeam();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'team_id' => $model->team_id, 'u_id' => $model->u_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'team' => $this->getAssignTeam(),
                'user' => $this->getAssignUser(),
            ]);
        }
    }

    /**
     * Updates an existing MultimediaAssignTeam model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $team_id
     * @param string $u_id
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
                'team' => $this->getAssignTeam(),
                'user' => $this->getAssignUser(),
            ]);
        }
    }

    /**
     * Deletes an existing MultimediaAssignTeam model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $team_id
     * @param string $u_id
     * @return mixed
     */
    public function actionDelete($team_id, $u_id)
    {
        $this->findModel($team_id, $u_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the MultimediaAssignTeam model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $team_id
     * @param string $u_id
     * @return MultimediaAssignTeam the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($team_id, $u_id)
    {
        if (($model = MultimediaAssignTeam::findOne(['team_id' => $team_id, 'u_id' => $u_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 获取团队
     * @return type
     */
    public function getAssignTeam()
    {
        return ArrayHelper::map(Team::find()->all(), 'id', 'name');
    }
    
    /**
     * 获取指派人
     * @return type
     */
    public function getAssignUser()
    {
        $expert = Expert::find()->all();
        $uId = ArrayHelper::getColumn($expert, 'u_id');
        $user = User::find()->where(['not in', 'id', $uId])->all();
        return ArrayHelper::map($user, 'id', 'nickname');
    }
}
