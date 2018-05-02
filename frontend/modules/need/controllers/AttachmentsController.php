<?php

namespace frontend\modules\need\controllers;

use Yii;
use common\models\need\NeedAttachments;
use common\models\need\searchs\NeedAttachmentsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AttachmentsController implements the CRUD actions for NeedAttachments model.
 */
class AttachmentsController extends Controller
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
        ];
    }

    /**
     * Lists all NeedAttachments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NeedAttachmentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
