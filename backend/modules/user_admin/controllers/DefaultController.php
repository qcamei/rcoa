<?php

namespace backend\modules\user_admin\controllers;

use common\models\searchs\UserSearch;
use common\models\User;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use wskeee\notification\UserManager;
use wskeee\notification\core\TxlApi;

class DefaultController extends Controller {

    public function behaviors() {
        return [
            //验证delete时为post传值
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

    public function actionIndex() {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(\Yii::$app->getRequest()->getQueryParams());
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'searchModel' => $searchModel
        ]);
    }

    /**
     * Displays a single User model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate() {
        $model = new User();
        $model->scenario = User::SCENARIO_CREATE;

        if (Yii::$app->request->isPost) {
            if ($model->load(\Yii::$app->request->post()) && $model->save()) {
                return $this->redirect('index');
            }
        }
        $model->loadDefaultValues();
        return $this->render('create', ['model' => $model]);
    }

    public function actionDelete($id) {
        /* @var $model User */

        if (($model = $this->findModel($id)) !== null) {
            if ($id !== Yii::$app->getUser()->getId()) {
                $model->delete();
                return $this->redirect(['index']);
            } else
                throw new Exception('自己不可以删除自己');
        } else
            throw new Exception('找不到对应用户！');
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $model->scenario = User::SCENARIO_UPDATE;

        if ($model->load(Yii::$app->getRequest()->post())) {
            if ($model->save())
                return $this->redirect(['index']);
            else
                Yii::error($model->errors);
        }else {
            $model->password = '';
            return $this->render('update', ['model' => $model]);
        }
    }

    /**
     * 查找用户模型
     * @param integer $id   用户模型id
     * @return User 用户模型
     * @throws NotFoundHttpException
     */
    private function findModel($id) {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException();
        }
    }

    /**
     * 同步功能企业微信成员ID
     * @return type
     */
    public function actionTongbu() {

        $api = new TxlApi();
        $userInfo = $this->getUserId($api);
        $arr_userInfo = json_decode($userInfo, true);
        print_r($arr_userInfo);exit;
        if($arr_userInfo != NULL){
            foreach ($arr_userInfo as $userid => $values) {
                print_r($values['userlist']);
            }
        }
        exit;
        print_r($arr_userInfo);
        exit;
        return $this->render('tongbu');
    }

    /**
     * 获取企业微信成员基本信息
     * @param TxlApi $instance
     * queryUsersByDepartmentIdd的数据为： 1 [Number]  $depId    查询的部门ID
     * queryUsersByDepartmentIdd的数据为： 1 [integer] $fetchChild 是否遍历子部门
     * queryUsersByDepartmentIdd的数据为： 1 [boolean] $simple   是否只查询用户的基本信息
     * @return type
     */
    public static function getUserId($instance) {

        return $instance->queryUsersByDepartmentId();
    }

}