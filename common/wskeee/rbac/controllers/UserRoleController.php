<?php

namespace wskeee\rbac\controllers;

use common\models\searchs\UserSearch;
use common\models\System;
use common\models\User;
use wskeee\rbac\models\AuthItem;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * RoleController implements the CRUD actions for AuthItem model.
 */
class UserRoleController extends Controller
{
    public function behaviors()
    {
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

    public $layout = 'basedata';
    
    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $user_id
     * @return mixed
     */
    public function actionView($user_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($user_id),
            'roleCategorys' => $this->getRoleCategory($user_id),
            'roles' => \Yii::$app->authManager->getRolesByUser($user_id),
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($user_id)
    {
        $post = Yii::$app->getRequest()->post();
        
        if (\Yii::$app->getRequest()->isPost) {
            $this->saveAuthAssignment($user_id, $post);
            return $this->redirect(['view', 'user_id' => $user_id]);
        } else {
            return $this->renderAjax('create', [
                'roleCategorys' => $this->getRoleCategory(),
                'roles' => \Yii::$app->authManager->getRoles(),
            ]);
        }
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($user_id = null, $item_name = null, $is_rbacName = false)
    {
        $post = Yii::$app->request->post();
        if(!$is_rbacName)
            $item_name = ArrayHelper::getValue($post, 'item_name');
        else
            $user_id = ArrayHelper::getValue($post, 'user_id');
        
        Yii::$app->db->createCommand()->delete('ccoa_auth_assignment', [
            'user_id' => $user_id, 'item_name' => $item_name])->execute();
        if(!$is_rbacName)
            return $this->redirect(['view', 'user_id' => $user_id]);
        else
            return $this->redirect(['role-manager/view', 'name' => $item_name]);
    }
    
    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $model = User::findOne(['id' => $id]);
        if($model !== null)
            return $model;
        else
            throw new NotFoundHttpException('The requested page does not exist.');
    }
    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
    
    protected function findModel($id)
    {
        $item = \Yii::$app->authManager->getRole($id);
        if($item !== null)
            return new AuthItem($item);
        else
            throw new NotFoundHttpException('The requested page does not exist.');
    } */
    
    /**
     * 获取角色类别
     * @param string $user_id               用户id
     * @return array
     */
    public function getRoleCategory($user_id = null)
    {
        if($user_id != null)
            $items = \Yii::$app->authManager->getRolesByUser($user_id);
        else
            $items = \Yii::$app->authManager->getRoles();
        
        $itemCategory = [];
        foreach ($items as $item) 
            $itemCategory[] = ArrayHelper::getValue($item, 'system_id');
        $public[] = ['id' => 0, 'name' => '公共'];
        $roleCategory = (new Query())
                    ->select(['id', 'name'])
                    ->from(['System' => System::tableName()])
                    ->where(['id' => array_values($itemCategory)])
                    ->orderBy('System.index')
                    ->all();
        
        return ArrayHelper::merge($public, $roleCategory);
    }
    
    /**
     * 保存数据到分配表
     * @param string $user_id               用户id
     * @param type $post
     */
    public function saveAuthAssignment($user_id, $post)
    {
        $values = ArrayHelper::getValue($post, 'item_name');
        $assignment = [];
        if($values != null){
            foreach ($values as $value) {
                $assignment[] = [
                    'item_name' => $value, 
                    'user_id' => $user_id,
                    'created_at' => time(),
                ];
            }
        }
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            Yii::$app->db->createCommand()->delete('ccoa_auth_assignment', [
                'user_id' => $user_id, 'item_name' => $values])->execute();   
            /** 添加$assignment数组到表里 */
            $numInsert = Yii::$app->db->createCommand()->batchInsert('ccoa_auth_assignment',[
                'item_name', 'user_id', 'created_at'], $assignment)->execute();
            if($numInsert > 0){
                
            }else
                throw new \Exception($model->getErrors());
            $trans->commit();  //提交事务
        }catch (\Exception $ex) {
            $trans ->rollBack(); //回滚事务
        }
    }
}