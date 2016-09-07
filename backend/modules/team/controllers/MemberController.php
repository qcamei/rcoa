<?php

namespace backend\modules\team\controllers;

use common\models\Position;
use common\models\team\TeamMember;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * MemberController implements the CRUD actions for TeamMember model.
 */
class MemberController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
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
     * Lists all TeamMember models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => TeamMember::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TeamMember model.
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
     * Creates a new TeamMember model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($team_id)
    {
        $model = new TeamMember();
        $model->team_id = $team_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/teammanage/team/view', 'id' => $model->team_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'member' => ArrayHelper::map(User::find()->all(), 'id', 'nickname'),
                'position' => ArrayHelper::map(Position::find()->all(), 'id', 'name')
            ]);
        }
    }

    /**
     * Updates an existing TeamMember model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $team_id
     * @param string $u_id
     * @return mixed
     */
    public function actionUpdate($team_id, $u_id)
    {
        $model = $this->findModel($team_id, $u_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/teammanage/team/view', 'id' => $model->team_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'member' => ArrayHelper::map(User::find()->all(), 'id', 'nickname'),
                'position' => ArrayHelper::map(Position::find()->all(), 'id', 'name')
            ]);
        }
    }

    /**
     * Deletes an existing TeamMember model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $team_id
     * @param string $u_id
     * @return mixed
     */
    public function actionDelete($team_id, $u_id)
    {
        $this->findModel($team_id, $u_id)->delete();

        return $this->redirect(['team/view','id' => $team_id]);
    }

    /**
     * Finds the TeamMember model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $team_id
     * @param string $u_id
     * @return TeamMember the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($team_id, $u_id)
    {
        if (($model = TeamMember::findOne(['team_id' => $team_id, 'u_id' => $u_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
