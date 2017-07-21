<?php

namespace frontend\modules\worksystem\controllers;

use common\models\worksystem\WorksystemTask;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Default controller for the `worksystem` module
 */
class DefaultController extends Controller
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
                        'roles' => [],
                    ]
                ],
            ],
        ];
    }
    
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->redirect([
            'task/index', 
            'create_by' => Yii::$app->user->id, 
            'producer' => Yii::$app->user->id, 
            'assign_people' => Yii::$app->user->id,
            'status' => WorksystemTask::STATUS_DEFAULT,
            'mark' => false,
        ]);
        //return $this->render('index');
    }
}
