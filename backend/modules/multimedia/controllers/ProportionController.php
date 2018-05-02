<?php

namespace backend\modules\multimedia\controllers;

use common\models\multimedia\MultimediaContentType;
use common\models\multimedia\MultimediaTypeProportion;
use common\models\multimedia\searchs\MultimediaTypeProportionSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ProportionController implements the CRUD actions for MultimediaTypeProportion model.
 */
class ProportionController extends Controller
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
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    /**
     * Lists all MultimediaTypeProportion models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MultimediaTypeProportionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single MultimediaTypeProportion model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MultimediaTypeProportion model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($content_type)
    {
        if($this->getIsExistenceTargetMonth($content_type))
            throw new NotFoundHttpException(\Yii::t('rcoa/multimedia', 'Do not repeat the creation of the same month in the same month'));
        
        $model = new MultimediaTypeProportion();
        $model->target_month = date('Y-m', time());
        if ($model->load(Yii::$app->request->post()) &&  $model->save()) {
            return $this->redirect(['contenttype/view', 'id' => $model->content_type]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'contentType' => $content_type,
                'contentTypes' => $this->getContentType(),
            ]);
        }
    }

    /**
     * Updates an existing MultimediaTypeProportion model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['contenttype/view', 'id' => $model->content_type]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'contentTypes' => $this->getContentType(),
            ]);
        }
    }

    /**
     * Deletes an existing MultimediaTypeProportion model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        return $this->redirect(['contenttype/view', 'id' => $model->content_type]);
    }

    /**
     * Finds the MultimediaTypeProportion model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MultimediaTypeProportion the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MultimediaTypeProportion::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException(\Yii::t('rcoa', 'The requested page does not exist.'));
        }
    }
    
    /**
     * 获取内容类型
     * @return type
     */
    public function getContentType()
    {
        $contentType = MultimediaContentType::find()->all();
        return ArrayHelper::map($contentType, 'id', 'name');
    }
    
    /**
     * 判断同一类型是否存在相同的月份
     * @param type $contentType     任务内容类型
     * @return type
     * @throws NotFoundHttpException
     */
    public function getIsExistenceTargetMonth($contentType)
    {
        $proportion = MultimediaTypeProportion::findAll(['content_type' => $contentType]);
        $targetMonth = [];
        foreach ($proportion as $value) 
            $targetMonth[] = $value->target_month;
        
        if(in_array(date('Y-m', time()), $targetMonth))
            return true;
        else
            return false;
    }
}
