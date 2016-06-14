<?php

namespace common\wskeee\filemanage\controllers;

use common\wskeee\filemanage\models\searchs\FileManageSearch;
use wskeee\filemanage\FileManageTool;
use wskeee\filemanage\models\FileManage;
use wskeee\filemanage\models\FileManageDetail;
use wskeee\filemanage\models\FileManageOwner;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

/**
 * DefaultController implements the CRUD actions for FileManage model.
 */
class DefaultController extends Controller
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

    /**
     * Lists all FileManage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FileManageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single FileManage model.
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
     * Creates a new FileManage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FileManage();
        $post = Yii::$app->request->post();
        /* @var $fileManage FileManageTool */
        $fileManage = Yii::$app->get('fileManage');
        if ($model->load($post)) {
            $owners = $post['FileManageOwner']['owner'];
            $content = $post['FileManageDetail']['content'];
            unset($post['FileManageOwner']);
            unset($post['FileManageDetail']);
            $fileManage->createTask($model, $owners, $content);
            return $this->redirect([
                $post['FileManage']['type'] == FileManage::FM_FILE ? 'detail/view' : 'view', 
                'id' => $model->id
            ]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'owner' => new FileManageOwner(),
                'detail' => new FileManageDetail(),
                'ownerName' => $this->getAuthManagerRoleName(),
                'fmList' => $this->getFmList(),
            ]);
        }
    }

    /**
     * Updates an existing FileManage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        /* @var $fileManage FileManageTool */
        $fileManage = Yii::$app->get('fileManage');
        if (!$fileManage->isFmOwner($id))
            throw new UnauthorizedHttpException('无权操作！');
        $model = $this->findModel($id);
        $post = Yii::$app->request->post();
        if ($model->load($post)) {
            $owners = $post['FileManageOwner']['owner'];
            $content = $post['FileManageDetail']['content'];
            unset($post['FileManageOwner']);
            unset($post['FileManageDetail']);
            $fileManage->updateTask($model, $owners, $content);
            return $this->redirect([
                $post['FileManage']['type'] == FileManage::FM_FILE ? 'detail/view' : 'view', 
                'id' => $model->id
            ]);
        } else {
            $ownerValue = [];
            foreach ($this->getOwner($id) as $key => $value) 
                $ownerValue[] = $value['owner'];
            return $this->render('update', [
                'model' => $model,
                'owner' => $this->findOwner($id),
                'detail' => $model->type == FileManage::FM_LIST ? new FileManageDetail() :$this->findDetail($id),
                'ownerName' => $this->getAuthManagerRoleName(),
                'ownerValue' => $ownerValue,
                'fmList' => $this->getFmList(),
            ]);
        }
    }

    /**
     * Deletes an existing FileManage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        /* @var $fileManage FileManageTool */
        $fileManage = Yii::$app->get('fileManage');
        if (!$fileManage->isFmOwner($id))
            throw new UnauthorizedHttpException('无权操作！');
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the FileManage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FileManage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FileManage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Finds the FileManage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FileManage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findOwner($id)
    {
        if (($owner = FileManageOwner::findOne(['fm_id'=> $id])) !== null) {
            return $owner;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * Finds the FileManage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FileManage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findDetail($id)
    {
        if (($detail = FileManageDetail::findOne(['fm_id'=> $id])) !== null) {
            return $detail;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 获取角色
     * @return type
     */
    public function getAuthManagerRoleName()
    {
        /* @var $authManager authManager */
        $authManager = \Yii::$app->authManager;
        $roleName = $authManager->getRoles();
        return ArrayHelper::map($roleName, 'name', 'description');
    }
    
    public function getOwner($id){
        $owner = FileManageOwner::find()->where(['fm_id' => $id])->all();
        return $owner;
    }

    /**
     * 获取所有目录
     * @return type
     */
    public function getFmList()
    {
        /* @var $fileManage FileManageTool */
        $fileManage = Yii::$app->get('fileManage');
        return ArrayHelper::map($fileManage->getFileManageList(), 'id', 'name');
    }
}
