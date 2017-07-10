<?php

namespace wskeee\rbac\controllers;

use common\models\System;
use common\models\User;
use Exception;
use wskeee\rbac\models\AuthItem;
use wskeee\rbac\models\searchs\AuthItemSearch;
use wskeee\rbac\RbacManager;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * RoleController implements the CRUD actions for AuthItem model.
 */
class RoleManagerController extends Controller
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
        
        $searchModel = new AuthItemSearch(['type'=>  Item::TYPE_ROLE]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categorys' => $this->getCategory(),
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $name
     * @return mixed
     */
    public function actionView($name)
    {        
        return $this->render('view', [
            'model' => $this->findModel($name),
            'roleCategorys' => $this->getRoleCategory($name, false),
            'childRoles' => Yii::$app->authManager->getChildRoles($name),
            'permissions' => Yii::$app->authManager->getPermissionsByRole($name),
            'users' => $this->getAssignmentUsers($name),
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem(null);
        $model->type = Item::TYPE_ROLE;
        /* @var $rbacManager RbacManager */
        $rbacManager = Yii::$app->authManager;
        
        if ($model->load(Yii::$app->getRequest()->post()) && $model->save()) {
            $rbacManager->invalidateCache();
            return $this->redirect(['view', 'name' => $model->name]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'categorys' => $this->getCategory(),
            ]);
        }
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($name)
    {
        $model = $this->findModel($name);
        /* @var $rbacManager RbacManager */
        $rbacManager = Yii::$app->authManager;
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $rbacManager->invalidateCache();
            return $this->redirect(['view', 'name' => $model->name]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'categorys' => $this->getCategory(),
            ]);
        }
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $name
     * @return mixed
     */
    public function actionDelete($name)
    {
        $post = Yii::$app->request->post();
        /* @var $rbacManager RbacManager */
        $rbacManager = Yii::$app->authManager;
        $rbacManager->invalidateCache();
        
        $child = ArrayHelper::getValue($post, 'child');
        Yii::$app->db->createCommand()->delete('ccoa_auth_item_child', [
            'parent' => $name, 'child' => $child])->execute();
        
        return $this->redirect(['view', 'name' => $name]);
    }
    
    /**
     * 为该角色分配权限
     * @return mixed
     */
    public function actionAssignmentPermission($name)
    {
        $post = Yii::$app->request->post();
        /* @var $rbacManager RbacManager */
        $rbacManager = Yii::$app->authManager;
        $rbacManager->invalidateCache();
        
        if (\Yii::$app->getRequest()->isPost) {
            $this->saveAuthAssignment($name, $post, 1);
            return $this->redirect(['view', 'name' => $name]);
        } else {
            return $this->renderAjax('_permission', [
                'roleCategorys' => $this->getRoleCategory(),
                'permissions' => Yii::$app->authManager->getPermissions(),
            ]);
        }
    }
    
    /**
     * 为该角色分配用户
     * @return mixed
     */
    public function actionAssignmentUser($name)
    {
        $post = Yii::$app->request->post();
        /* @var $rbacManager RbacManager */
        $rbacManager = Yii::$app->authManager;
        $rbacManager->invalidateCache();
        
        if (\Yii::$app->getRequest()->isPost) {
            $this->saveAuthAssignment($name, $post, 2);
            return $this->redirect(['view', 'name' => $name]);
        } else {
            return $this->renderAjax('_user', [
                'users' => $this->getUsers(),
            ]);
        }
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
        $item = \Yii::$app->authManager->getRole($id);
        if($item !== null)
            return new AuthItem($item);
        else
            throw new NotFoundHttpException('The requested page does not exist.');
    } 
    
    /**
     * 获取所有模块分类
     * @return type
     */
    public function getCategory()
    {
        $public[] = ['id' => 0, 'name' => '公共'];
        $categorys = (new Query())
                    ->select(['id', 'name'])
                    ->from(['System' => System::tableName()])
                    ->where(['is_delete' => 'N'])
                    ->orderBy('System.index')
                    ->all();
        
        $category = ArrayHelper::merge($public, $categorys);
        
        return ArrayHelper::map($category, 'id', 'name');
    }


    /**
     * 获取角色类别
     * @param string $name            角色名
     * @param string $is_null
     * @return array
     */
    public function getRoleCategory($name = null, $is_null = true)
    {
        if($is_null)
            $items = Yii::$app->authManager->getPermissions();
        else{
            $childRoles = Yii::$app->authManager->getChildRoles($name);
            $permissions = Yii::$app->authManager->getPermissionsByRole($name);
            $items = ArrayHelper::merge($childRoles, $permissions);
        }
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
     * 获取所有用户
     * @return array
     */
    public function getUsers()
    {
        $users = (new Query())
                ->select(['id', 'nickname'])
                ->from(User::tableName())
                ->all();
        
        return $users;
    }
    
    /**
     * 获取所有已分配该角色的用户
     * @param string $name
     * @return array
     */
    public function getAssignmentUsers($name)
    {
        /* @var $rbacManager RbacManager */
        $rbacManager = Yii::$app->authManager;
        $userItems = $rbacManager->getItemUsers($name);
        $users = [];
        foreach ($userItems as $value) {
            $users[] = [
                'id' => $value->id,
                'nickname' => $value->nickname,
            ];
        }
        
        return $users;
    }
    
    /**
     * 保存数据到分配表
     * @param string $param               参数
     * @param type $post
     * @param integer $type               传参类型
     */
    public function saveAuthAssignment($param, $post, $type)
    {
        $assignment = [];
        if($type == 1)    
            $values = ArrayHelper::getValue($post, 'child');
        else
            $values = ArrayHelper::getValue($post, 'user_id');
        
        if($values != null){
            foreach ($values as $value) {
                $assignment[] = $type == 1 ?  ['parent' => $param,'child' => $value] : 
                        ['item_name' => $param,'user_id' => $value,'created_at' => time()];
            }
        }
        
        /** 开启事务 */
        $trans = Yii::$app->db->beginTransaction();
        try
        {  
            if($type == 1){
                Yii::$app->db->createCommand()->delete('ccoa_auth_item_child', [
                    'child' => $values, 'parent' => $param])->execute();   
                /** 添加$assignment数组到表里 */
                $numInsert = Yii::$app->db->createCommand()->batchInsert('ccoa_auth_item_child',[
                    'parent', 'child'], $assignment)->execute();
            }else{
                Yii::$app->db->createCommand()->delete('ccoa_auth_assignment', [
                    'user_id' => $values, 'item_name' => $param])->execute();   
                /** 添加$assignment数组到表里 */
                $numInsert = Yii::$app->db->createCommand()->batchInsert('ccoa_auth_assignment',[
                    'item_name', 'user_id', 'created_at'], $assignment)->execute();
            }
            if($numInsert > 0){
                
            }else
                throw new Exception($model->getErrors());
            $trans->commit();  //提交事务
        }catch (Exception $ex) {
            $trans ->rollBack(); //回滚事务
        }
    }
}