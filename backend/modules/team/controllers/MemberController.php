<?php

namespace backend\modules\team\controllers;

use common\models\expert\Expert;
use common\models\Position;
use common\models\team\Team;
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
            'query' => TeamMember::find()->where(['!=', 'is_delete', 'Y']),
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
        $model->loadDefaultValues();
        $model->team_id = $team_id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/teammanage/team/view', 'id' => $model->team_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'member' => $this->getTeamMember(),
                'isExist' => $this->getIsExistLeader($team_id),
                'position' => ArrayHelper::map(Position::find()->all(), 'id', 'name')
            ]);
        }
    }

    /**
     * Updates an existing TeamMember model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param type $id
     * @return type
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/teammanage/team/view', 'id' => $model->team_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'team' => $this->getTeam(),
                'member' => $this->getTeamMember(),
                'isExist' => $this->getIsExistLeader($model->team_id),
                'position' => ArrayHelper::map(Position::find()->all(), 'id', 'name')
            ]);
        }
    }

    /**
     * Deletes an existing TeamMember model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param type $id
     * @return type
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_delete = TeamMember::SURE_DELETE;
        if($model->update() != false)
            return $this->redirect(['team/view','id' => $model->team_id]);
        else
            throw new NotFoundHttpException('删除失败！');
    }

    /**
     * Finds the TeamMember model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $team_id
     * @param string $u_id
     * @return TeamMember the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TeamMember::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('rcoa', 'The requested page does not exist'));
        }
    }
    
    /**
     * 获取团队
     * @return type
     */
    public function getTeam()
    {
        $team = Team::find()
                ->where(['!=', 'is_delete', 'Y'])
                ->all();
        return ArrayHelper::map($team, 'id', 'name');
    }

    /**
     * 获取团队成员
     * @return type
     */
    public function getTeamMember(){
        $expert = Expert::find()->all();
        $member = User::find()
                ->where(['not in', 'id', ArrayHelper::getColumn($expert, 'u_id')])
                ->all();
        return ArrayHelper::map($member, 'id', 'nickname');
    }

    /**
     * 获取团队下是否已经存在队长
     * @param type $teamId  团队ID
     * @return type
     */
    public function getIsExistLeader($teamId)
    {
        $teamMember = TeamMember::findAll(['team_id' => $teamId]);
        $isExist = [];
        if(!empty($teamMember) || isset($teamMember)){
            foreach ($teamMember as $value){
                $isExist[] = $value->is_leader;
            }
            if(in_array('Y', $isExist))
                return 1;
        }
        return 0;
    }
}
