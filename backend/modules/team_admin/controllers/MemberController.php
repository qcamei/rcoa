<?php

namespace backend\modules\team_admin\controllers;

use common\models\expert\Expert;
use common\models\Position;
use common\models\team\Team;
use common\models\team\TeamMember;
use common\models\User;
use wskeee\team\TeamMemberTool;
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
        $post = Yii::$app->request->post();
        $id = ArrayHelper::getValue($post, 'id');
        $u_id = ArrayHelper::getValue($post, 'TeamMember.u_id');
        $isLeader = ArrayHelper::getValue($post, 'TeamMember.is_leader');
        TeamMemberTool::getInstance()->invalidateCache();
        
        if(isset($id))
            $model = TeamMember::findOne(['team_id' => $team_id, 'u_id' => $u_id]);
        if(!isset($model)){
            $model = new TeamMember();
            $model->loadDefaultValues();
        }
        
        $model->team_id = $team_id;
        $model->is_delete = TeamMember::CANCEL_DELETE;
        if($this->getIsExistLeader($model->team_id, $isLeader))
            throw new NotFoundHttpException(Yii::t('rcoa/team', 'Already exist team leader'));
        
        if ($model->load($post) && $model->save()) {
            return $this->redirect(['/teammanage_admin/team/view', 'id' => $model->team_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'member' => $this->getTeamMember(),
                'isExist' => $this->getIsExistLeader($model->team_id),
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
        $post = Yii::$app->request->post();
        $postTeam = ArrayHelper::getValue($post, 'TeamMember.team_id');
        $isLeader = ArrayHelper::getValue($post, 'TeamMember.is_leader');
        TeamMemberTool::getInstance()->invalidateCache();
        
        if($model->team_id != $postTeam && $this->getIsExistLeader($postTeam, $isLeader))
                throw new NotFoundHttpException(Yii::t('rcoa/team', 'Change department already exist team leader'));
        if ($model->load($post) && $model->save()) {
            return $this->redirect(['/teammanage_admin/team/view', 'id' => $model->team_id]);
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
        TeamMemberTool::getInstance()->invalidateCache();
        
        if($model->update() != false)
            return $this->redirect(['team/view','id' => $model->team_id]);
        else
            throw new NotFoundHttpException('删除失败！');
    }

    /**
     * Finds the TeamMember model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
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
                //->where(['not in', 'id', ArrayHelper::getColumn($expert, 'u_id')])
                ->all();
        return ArrayHelper::map($member, 'id', 'nickname');
    }

    /**
     * 获取团队下是否已经存在队长
     * @param type $teamId         团队ID
     * @param type $isLeader       是否是队长
     * @return integer             1表示已存在, 0表示不存在
     */
    public function getIsExistLeader($teamId, $isLeader = TeamMember::TEAMLEADER)
    {
        $teamMember = TeamMember::find()
                      ->where(['team_id' => $teamId, 'is_leader' => TeamMember::TEAMLEADER])
                      ->andWhere(['!=', 'is_delete', TeamMember::SURE_DELETE])
                      ->all();
        if(!empty($teamMember) || isset($teamMember)){
            $isExist = ArrayHelper::getColumn($teamMember, 'is_leader');
            if(in_array($isLeader, $isExist))
                return 1;
        }
        return 0;
    }
}
