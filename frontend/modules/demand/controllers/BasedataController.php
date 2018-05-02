<?php

namespace frontend\modules\demand\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class BasedataController extends Controller
{
    /* 重构 layout */
    public $layout = 'basedata';
    
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
                'only' => ['create','update','delete'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }
    /*
    public function actionCreate(){
        if(!$this->canCreate()){
            throw new HttpException(403,'无权操作');
            return;
        }
    }
    
    public function actionUpdate($id){
        if(!$this->canUpdate()){
            throw new HttpException(403,'无权操作');
            return;
        }
    }
    
    public function actionDelete($id){
        if(!$this->canDelete()){
            throw new HttpException(403,'无权操作');
            return;
        }
    }
    
    public function getRbac(){
        return [
                'create' => $this->canCreate(),
                'update' => $this->canUpdate(),
                'delete' => $this->canDelete(),
            ];
    }*/
    
    /**
     * 是否有 创建 基础数据权限
     * @return boolean
     
    public function canCreate(){
        return Yii::$app->user->can(RbacName::PERMSSION_FRONTEND_DEMAND_BASEDATA_CREATE);
    }*/
    
    /**
     * 是否有 更新 基础数据权限
     * @return boolean
     
    public function canUpdate(){
        return Yii::$app->user->can(RbacName::PERMSSION_FRONTEND_DEMAND_BASEDATA_UPDATE);
    }*/
    
    /**
     * 是否有 删除 基础数据权限
     * @return boolean
     
    public function canDelete(){
        return Yii::$app->user->can(RbacName::PERMSSION_FRONTEND_DEMAND_BASEDATA_DELETE);
    }*/

}
