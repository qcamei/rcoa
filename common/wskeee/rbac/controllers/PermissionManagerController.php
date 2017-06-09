<?php

namespace wskeee\rbac\controllers;

use common\models\System;
use common\models\User;
use wskeee\rbac\models\AuthItem;
use wskeee\rbac\models\searchs\AuthItemSearch;
use wskeee\rbac\RbacManager;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\rbac\Item;
use yii\rbac\Permission;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * PremissionController implements the CRUD actions for Permission model.
 */
class PermissionManagerController extends Controller
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
     * Lists all Permission models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemSearch(['type'=> Item::TYPE_PERMISSION]);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'categorys' => $this->getCategory(),
        ]);
    }

    /**
     * Displays a single Permission model.
     * @param string $name
     * @return mixed
     */
    public function actionView($name)
    {
        return $this->render('view', [
            'model' => $this->findModel($name),
            'roleCategorys' => $this->getRoleCategory($name, false),
            'byRoles' => $this->getItemByRole($name)->all(),
            'byRoleCategorys' => $this->getItemByRoleCategory($name),
            'byUsers' => $this->getItemByUser($name),
        ]);
    }

    /**
     * Creates a new Permission model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem(null,['type'=>  Item::TYPE_PERMISSION]);
        /* @var $rbacManager RbacManager */
        $rbacManager = Yii::$app->authManager;
            
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $rbacManager->invalidateCache();
            return $this->redirect(['view', 'name' => $model->name]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'categorys' => $this->getCategory(),
                'rules' => $this->getRulesForSelect()
            ]);
        }
    }

    /**
     * Updates an existing Permission model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $name
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
                'rules' => $this->getRulesForSelect()
            ]);
        }
    }

    /**
     * Deletes an existing Permission model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        Yii::$app->authManager->remove($model->item);
        return $this->redirect(['index']);
    }
    
    /**
     * Finds the Permission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Permission the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $item = \Yii::$app->authManager->getPermission($id);
        if ($item !== null) {
            return new AuthItem($item);
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
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
     * @param boolean $is_null
     * @return array
     */
    public function getRoleCategory($name = null, $is_null = true)
    {
        $itemCategory = [];
        if($is_null){
            $items = Yii::$app->authManager->getPermissions();
            foreach ($items as $item) 
                $itemCategory[] = ArrayHelper::getValue($item, 'system_id');
        }
        else{
            $items = Yii::$app->authManager->getPermission($name);
            $itemCategory[] = ArrayHelper::getValue($items, 'system_id');
        }
        
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
     * 获取该权限被分配的对应角色
     * @param string $name
     * @return query
     */
    public function getItemByRole($name)
    {
        $items = Yii::$app->authManager->getPermission($name);
        $child = ArrayHelper::getValue($items, 'name');
        
        $query = (new Query())
            ->from('ccoa_auth_item_child')
            ->leftJoin('ccoa_auth_item', 'name = parent')
            ->where(['child' => $child]);
        
        return $query;
    }
    
    /**
     * 获取该权限被分配的对应角色的所属模块
     * @param string $name          权限名
     * @return array
     */
    public function getItemByRoleCategory($name)
    {
        $roleItems = $this->getItemByRole($name)->all();
        $system_id = ArrayHelper::getColumn($roleItems, 'system_id');
        
        $public[] = ['id' => 0, 'name' => '公共'];
        $roleCategory = (new Query())
            ->select(['id', 'name'])
            ->from(System::tableName())
            ->where(['id' => $system_id])
            ->all();
        
        return ArrayHelper::merge($public, $roleCategory);
    }
    
    /**
     * 获取该权限被分配的对应用户
     * @param string $name         权限名
     * @return array
     */
    public function getItemByUser($name)
    {
        $roleItems = $this->getItemByRole($name)->all();
        $itemName = ArrayHelper::getColumn($roleItems, 'name');
        $userItems = (new Query())
            ->from('ccoa_auth_assignment')
            ->leftJoin(User::tableName(), 'id = user_id')
            ->where(['item_name' => $itemName])
            ->all();
       
        return $userItems;
    }


    protected function getRulesForSelect()
    {
        Yii::trace(ArrayHelper::map(Yii::$app->authManager->getRules(), 'name', 'name'));
        return ArrayHelper::map(Yii::$app->authManager->getRules(), 'name', 'name');
    }
}
