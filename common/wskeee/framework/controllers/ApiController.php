<?php

namespace wskeee\framework\controllers;

use wskeee\framework\FrameworkManager;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\rbac\Item;
use yii\web\Controller;

class ApiController extends Controller
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
    
    public function actionIndex()
    {
        return $this->render('index');
    }
    
    public function actionList()
    {
        Yii::$app->getResponse()->format = 'json';
        
        $errors = [];
        $items = [];
        try
        {
            $items = Item::find()->asArray()->all();
        } catch (Exception $ex) {
            $errors [] = $ex->getMessage();
        }
        return [
            'type'=>'S',
            'data' => $items,
            'error' => $errors
        ];
    }
    
    /**
     * 获取项目子项
     * @param type $id
     * @return type JSON
     */
    public function actionSearch($id)
    {
        Yii::$app->getResponse()->format = 'json';
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        
        $errors = [];
        $items = [];
        try
        {
            $items = $fwManager->getChildren($id);
        } catch (Exception $ex) {
            $errors [] = $ex->getMessage();
        }
        return [
            'type'=>'S',
            'data' => $items,
            'error' => $errors
        ];
    }
    
    /**
     * 手动清取项目缓存数据
     */
    public function actionClearCache(){
        /* @var $fwManager FrameworkManager */
        $fwManager = \Yii::$app->fwManager;
        $fwManager->invalidateCache();
    }
}
