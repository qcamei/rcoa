<?php

namespace backend\modules\scene_admin\controllers;

use common\models\expert\Expert;
use common\models\scene\SceneAppraise;
use common\models\scene\SceneBook;
use common\models\scene\SceneBookUser;
use common\models\scene\SceneSite;
use common\models\scene\SceneSiteDisable;
use common\models\scene\searchs\SceneBookSearch;
use common\models\User;
use frontend\modules\scene\utils\SceneBookAction;
use frontend\modules\scene\utils\SceneBookNotice;
use wskeee\framework\FrameworkManager;
use wskeee\framework\models\Item;
use wskeee\framework\models\ItemType;
use wskeee\rbac\RbacManager;
use wskeee\rbac\RbacName;
use Yii;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * BookController implements the CRUD actions for SceneBook model.
 */
class BookController extends Controller
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
     * Lists all SceneBook models.
     * @return mixed
     */
    public function actionIndex()
    {
        $params = Yii::$app->request->queryParams;
        $searchModel = new SceneBookSearch();
        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'params' => $params,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'siteName' => $this->getSiteName(),
            'courseName' => $this->getCourseName(),
            'booker' => $this->getBooker(),
        ]);
    }

    /**
     * Displays a single SceneBook model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'sceneBookUser' => $this->getSceneBookUser($id),
        ]);
    }

    /**
     * Creates a new SceneBook model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {        
        return $this->redirect('index');
        
//        $model = new SceneBook();
//        
//        if ($model->load(Yii::$app->request->post()) && $model->save()) {
//            return $this->redirect(['view', 'id' => $model->id]);
//        } else {
//            return $this->render('create', [
//                'model' => $model,
//            ]);
//        }
    }

    /**
     * Updates an existing SceneBook model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $post = \Yii::$app->request->post();
        //把字符串类型改为int整形
        if(isset($post['SceneBook'])){
            $post['SceneBook']['is_photograph'] = intval($post['SceneBook']['is_photograph']);
            $post['SceneBook']['lession_time'] = intval($post['SceneBook']['lession_time']);
            $post['SceneBook']['camera_count'] = intval($post['SceneBook']['camera_count']);
        }
        if ($model->load($post)) {
            $this->getIsUpdate($model, $post);
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'siteName' => $this->getAllSite(),
                'business' => $this->getBasicDataBusiness(),
                'levels' => $this->getBasicDataLevel(),
                'professions' => $this->getBasicDataItem($model->level_id),
                'courses' => $this->getBasicDataItem($model->profession_id),
                'teachers' => $this->getExpert(),
                'contentTypeMap' => $this->getSceneSite($model->site_id),
                'createSceneBookUser' => $this->getCreateSceneBookUser($model),
                'existSceneBookUser' => $this->getExistSceneBookUser($model),
            ]);
        }
    }
    
    /**
     * 取消预约
     * @param indeger $id
     * @return mixed
     */
    public function actionCancel($id)
    {
        $notice = SceneBookNotice::getInstance();
        $model = $this->findModel($id);
        $status = $model->status;
        $contacter = []; $shootMan = [];
        //获取接洽人
        $contacterUser = $this->getOldNewSceneUser($id, null);
        //获取摄影师
        $shootManUser = $this->getOldNewSceneUser($id, null, 2);
        //组装接洽人用户
        foreach ($contacterUser['oldBookUser'] as $items) {
            $contacter[]= [
                'nickname' => $items['nickname']."（{$items['phone']}）",
                'guid' => $items['guid'],
                'email' => $items['email']
            ];
        }
        //组装摄影师用户
        foreach ($shootManUser['oldBookUser'] as $items) {
            $shootMan[] = [
                'nickname' => $items['nickname']."（{$items['phone']}）",
                'guid' => $items['guid'],
                'email' => $items['email']
            ];
        }   

        $model->status = SceneBook::STATUS_CANCEL;               //更改预约状态为取消
        if($model->save(false, ['status'])){
            //发送消息通知
            $notice->sendAllManNotification($model, $status, '无', $contacter, $shootMan, '取消预约-'.$model->course->name, 'scene/_canel_scene_book_html');
            Yii::$app->getSession()->setFlash('success','取消成功！');
            
            return $this->redirect(['index']);
        } else {
            throw new NotAcceptableHttpException('取消失败！');
        }        
    }

    /**
     * Assign an existing SceneBook model.
     * If assign is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionAssign($id)
    {
        $model = $this->findModel($id);
        
        if(date('Y-m-d H:i:s', strtotime($model->date.$model->start_time)) > date('Y-m-d H:i:s', time())){
            if(!($model->getIsAssign() || $model->getIsStausShootIng()))
                throw new NotAcceptableHttpException('该任务状态为'.$model->getStatusName ().'！');
        }else{
            throw new NotAcceptableHttpException('该任务已超过预约的时段！');
        }
        
        if ($model->load(Yii::$app->request->post())) {
            SceneBookAction::getInstance()->AssignSceneBook($model, Yii::$app->request->post());
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->renderAjax('assign', [
                'model' => $model,
                'createSceneBookUser' => $this->getShootManUser($model),
                'existSceneBookUser' => $this->getExistSceneBookUser($model, 2),
            ]);
        }
    }
    
    /**
     * Deletes an existing SceneBook model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * 获取场景场地类型
     * @param integer $site_id
     * @return array
     */
    public function actionSceneSite($site_id = null)
    {
        $query = (new Query())->select(['id', 'name', 'area', 'content_type'])
            ->from(SceneSite::tableName());
        $query->filterWhere(['id' => $site_id]);
        $results = $query->all();
        
        $contentTypeMap = [];
        $content_type = isset($results[0]) ? ArrayHelper::getValue($results[0], 'content_type') : "";
        $contents = explode(',', $content_type);
        foreach ($contents as $value) {
            $contentTypeMap[$value] = $value;
        }
           
        echo Html::radioList('SceneBook[content_type]', null, $contentTypeMap, [
            'separator'=>'',
            'itemOptions'=>[
                'labelOptions'=>[
                    'style'=>[
                         'margin-right'=>'30px'
                    ]
                ]
            ],
        ]);
    }
    
    /**
     * Finds the SceneBook model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return SceneBook the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $statusMap = [SceneBook::STATUS_DEFAULT, SceneBook::STATUS_CANCEL];
        $model = SceneBook::find()->where(['id' => $id])
            ->andWhere(['NOT IN', 'status', $statusMap])->one();
        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 判断是否能更新成功
     * @param object $model     需要修改的预约模型
     * @param array $post       post传的值
     * @return boolean  true|false
     * @throws NotAcceptableHttpException
     */
    public function getIsUpdate($model, $post)
    {
        $newAttr = $model->getDirtyAttributes();        //获取所有新属性值
        $newSite_id = ArrayHelper::getValue($newAttr, 'site_id');               //修改后的场地ID
        $newDate = ArrayHelper::getValue($newAttr, 'date');                     //修改后的日期
        $newTime_index = ArrayHelper::getValue($newAttr, 'time_index');         //修改后的时段
        
        if($newSite_id != null || $newDate != null || $newTime_index != null) {
            $postSite_id = ArrayHelper::getValue($post, 'SceneBook.site_id');           //post传过来的场地ID
            $postDate = ArrayHelper::getValue($post, 'SceneBook.date');                 //post传过来的日期
            $postTime_index = ArrayHelper::getValue($post, 'SceneBook.time_index');     //post传过来的时段
            $dayTomorrow = date('Y-m-d H:i:s',strtotime("+1 days"));            //date('d')+1 明天预约时间
            $dayEnd = date('Y-m-d H:i:s',strtotime("+31 days"));                //30天后预约时间
            $date = date('Y-m-d H:i:s', strtotime($postDate.SceneBook::$startTimeIndexMap[$postTime_index]));//修改后的预约时间
            if($dayTomorrow < $date && $date < $dayEnd){
                $isBook = $this->getIsBook($postSite_id, $postDate, $postTime_index);           //是否已被预约
                $isDisable = $this->getIsDisable($postSite_id, $postDate, $postTime_index);     //是否已被禁用
                if ($isBook != null || $isDisable != null) {
                    throw new NotAcceptableHttpException('该场地的场次已被预约或被禁用！！！');
                }else{
                    $this->getSendUpdateContent($model, $post);
                    return $model->save();
                }
            }else{
                throw new NotAcceptableHttpException('预约的时间必须是明天开始的31天以内！！！');
            }
        }
        $this->getSendUpdateContent($model, $post);
        return $model->save();
    }

    /**
     * 预约更新后把需要发送的内容组装好
     * @param type $model
     * @param type $post
     */
    public function getSendUpdateContent($model, $post)
    {
        $notice = SceneBookNotice::getInstance();
        $contacter = []; $shootMan = [];
        $status = $model->status;
        $content = $this->getUpdateContent($model, $post);      //修改预约后的内容
        //获取接洽人
        $contacterUser = $this->getOldNewSceneUser($model->id, null);
        //获取摄影师
        $shootManUser = $this->getOldNewSceneUser($model->id, null, 2);
        //组装接洽人用户
        foreach ($contacterUser['oldBookUser'] as $items) {
            $contacter[]= [
                'nickname' => $items['nickname']."（{$items['phone']}）",
                'guid' => $items['guid'],
                'email' => $items['email']
            ];
        }
        //组装摄影师用户
        foreach ($shootManUser['oldBookUser'] as $items) {
            $shootMan[] = [
                'nickname' => $items['nickname']."（{$items['phone']}）",
                'guid' => $items['guid'],
                'email' => $items['email']
            ];
        }   
        
        //修改的内容不为空时发送通知
        if($content != null){
            $notice->sendAllManNotification($model, $status, $content, $contacter, $shootMan, '更新预约-'.$model->course->name, 'scene/_update_scene_book_html'); 
        }
    }
    
    /**
     * 判断是否已被预约
     * @param integer $site_id      场地ID
     * @param integer $date         日期
     * @param integer $time_index   时段
     * @return boolean  true|false
     */
    public function getIsBook($site_id, $date, $time_index)
    {
        $notStatus = [SceneBook::STATUS_DEFAULT, SceneBook::STATUS_CANCEL];
        $query = SceneBook::find();
        $query->andFilterWhere(['site_id' => $site_id])
                ->andFilterWhere(['date' => $date])
                ->andFilterWhere(['time_index' => $time_index])
                ->andFilterWhere(['NOT IN', 'status', $notStatus]);

        if (count($query->all()) > 0) {
            return true;
        }
        return false;
        
    }

    /**
     * 判断场地是否已被禁用
     * @param integer $site_id      场地ID
     * @param integer $date         日期
     * @param integer $time_index   时段
     * @return boolean  true|false
     */
    public function getIsDisable($site_id, $date, $time_index)
    {
        $query = SceneSiteDisable::find();
        $query->andFilterWhere(['site_id' => $site_id])
                ->andFilterWhere(['date' => $date])
                ->andFilterWhere(['time_index' => $time_index])
                ->andFilterWhere(['is_disable' => 1]);

        if (count($query->all()) > 0) {
            return true;
        }
        return false;
        
    }
    
    /**
     * 获取场景场地
     * @param integer $site_id
     * @return array
     */
    public function getSceneSite($site_id = null)
    {
        $query = (new Query())->select(['id', 'name', 'area', 'content_type'])
            ->from(SceneSite::tableName());
        $query->filterWhere(['id' => $site_id]);
        $query->andFilterWhere(['is_publish' => 1]);
        $results = $query->all();
        
        if($site_id == null){
            return ArrayHelper::map($results, 'id', 'name', 'area');
        }else {
            $contentTypeMap = [];
            $content_type = isset($results[0]) ? ArrayHelper::getValue($results[0], 'content_type') : "";
            $contents = explode(',', $content_type);
            foreach ($contents as $value) {
                $contentTypeMap[$value] = $value;
            }
           
            return $contentTypeMap;
        }
    }
    
    /**
     * 获取行业
     * @return array
     */
    protected function getBasicDataBusiness()
    {
        $business = ItemType::find()->all();
        return ArrayHelper::map($business, 'id','name');
    }
    
    /**
     * 获取层次/类型
     * @return array
     */
    protected function getBasicDataLevel()
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getColleges(), 'id', 'name');
    }
    
    
    /**
     * 获取专业/工种 or 课程
     * @param integer $itemId
     * @return array
     */
    protected function getBasicDataItem($itemId)
    {
        /* @var $fwManager FrameworkManager */
        $fwManager = Yii::$app->get('fwManager');
        return ArrayHelper::map($fwManager->getChildren($itemId), 'id', 'name');
    }  
    
    /**
     * 获取专家库
     * @return array
     */
    protected function getExpert()
    {
        $expert = Expert::find()->with('user')->all();
        return ArrayHelper::map($expert, 'u_id','user.nickname');
    }
    
    /**
     * 获取预约人和接洽人用户列表
     * @param SceneBook $model
     * @return array
     */
    protected function getCreateSceneBookUser($model)
    {
        $bookUser = [];
        $query = (new Query())->select(['id', 'nickname'])
            ->from(['User' => User::tableName()]);
        $query->where(['NOT IN', 'id', array_keys($this->getExpert())]);
        $query->andWhere(['status' => 10]);
        
        $bookUsers = $this->getSceneBookUser($model->id);
        if(isset($bookUsers[$model->id])){
            foreach ($bookUsers[$model->id] as $user) {
                if($user['role'] == 1)
                    $bookUser[$user['id']] = $user['nickname'];
            }
        }
        
        $createUser = ArrayHelper::map($query->all(), 'id', 'nickname');
        $existUser = $this->getExistSceneBookUser($model);
        
        return ArrayHelper::merge($bookUser, array_diff($createUser, $existUser));
    }
    
    /**
     * 获取摄影师用户列表
     * @param SceneBook $model
     * @return array
     */
    protected function getShootManUser($model)
    {
        $bookUser = [];
        /* @var $rbacManager RbacManager */
        $rbacManager = Yii::$app->authManager;
        //获取角色为摄影师的所有用户
        $roleUser = $rbacManager->getItemUsers(RbacName::ROLE_SHOOT_MAN);
        
        $bookUsers = $this->getSceneBookUser($model->id);
        if(isset($bookUsers[$model->id])){
            foreach ($bookUsers[$model->id] as $user) {
                if($user['role'] == 2)
                    $bookUser[$user['id']] = $user['nickname'];
            }
        }
        $createUser = ArrayHelper::map($roleUser, 'id', 'nickname');
        $existUser = $this->getExistSceneBookUser($model, 2);
        
        return ArrayHelper::merge($bookUser, array_diff($createUser, $existUser));
    }
    
    /**
     * 获取同一时间段已存在场景预约用户
     * @param SceneBook $model
     * @param integer $role
     * @return array
     */
    protected function getExistSceneBookUser($model, $role = 1)
    {
        //查询同一时间段所有数据
        $sceneBook = (new Query())->select(['SceneBook.id'])
            ->from(['SceneBook' => SceneBook::tableName()]);
        $sceneBook->where(['between', 'SceneBook.date', $model->date, date('Y-m-d',strtotime('+1 days'.$model->date))]);
        $sceneBook->andWhere(['SceneBook.time_index' => $model->time_index]);
        //查询同一时间段是否已存在指派用户数据
        $query = (new Query())->select(['User.id AS user_id', 'User.nickname'])
            ->from(['SceneBookUser' => SceneBookUser::tableName()]);
        $query->leftJoin(['User' => User::tableName()], 'User.id = SceneBookUser.user_id AND User.status = 10');
        $query->where(['book_id' => $sceneBook, 'role' => $role]);
        $query->orderBy(['SceneBookUser.sort_order' => SORT_ASC]);
        
        return ArrayHelper::map($query->all(), 'user_id', 'nickname');
    }
    
    /**
     * 获取场景预约任务的所有接洽人or摄影师
     * @param string|array $book_id
     * @return array
     */
    protected function getSceneBookUser($book_id)
    {
        $results = [];
        //查询预约用户信息
        $query = (new Query())->select([
            'SceneBookUser.book_id', 'SceneBookUser.role', 'SceneBookUser.user_id',
            'User.id', 'User.nickname','SceneBookUser.is_primary', 'User.phone',
            'FORMAT((SUM(SceneAppraise.user_value)/SUM(SceneAppraise.q_value) * COUNT(SceneAppraise.q_value)), 2) AS score'
        ])->from(['SceneBookUser' => SceneBookUser::tableName()]);
        $query->leftJoin(['User' => User::tableName()], '(User.id = SceneBookUser.user_id AND User.status = 10)');
        $query->leftJoin(['SceneAppraise' => SceneAppraise::tableName()], '(SceneAppraise.book_id = SceneBookUser.book_id AND SceneAppraise.user_id = SceneBookUser.user_id)');
        $query->where(['SceneBookUser.book_id' => $book_id, 'SceneBookUser.is_delete' => 0]);
        $query->groupBy('SceneBookUser.id');
        $query->orderBy(['SceneBookUser.sort_order' => SORT_ASC]);
        //组装返回的预约任务用户信息
        foreach ($query->all() as $value) {
            $results[$value['book_id']][] = $value;
            unset($results[$value['book_id']]['book_id']);
        }
        
        return $results;
    }
    
    /**
     * 查询所有场地
     * @return array
     */
    public function getAllSite()
    {
        $query = (new Query())
                ->select(['id', 'name'])
                ->from(['Book' => SceneSite::tableName()])
                ->where(['is_publish' => 1])    //过滤未发布的场地
                ->all();
        
        return ArrayHelper::map($query, 'id', 'name');
    }
    
    /**
     * 查询场地名称
     * @return array
     */
    public function getSiteName()
    {
        $query = (new Query())
                ->select(['Site.id', 'Site.name'])
                ->from(['Book' => SceneBook::tableName()])
                ->leftJoin(['Site' => SceneSite::tableName()], 'Site.id = Book.site_id')    //关联查询场地名
                ->all();
        
        return ArrayHelper::map($query, 'id', 'name');
    }
    
    /**
     * 查询课程名
     * @return array
     */
    public function getCourseName()
    {
        $query = (new Query())
                ->select(['ItemCourse.id', 'ItemCourse.name AS course_name'])
                ->from(['Book' => SceneBook::tableName()])
                ->leftJoin(['ItemCourse' => Item::tableName()], 'ItemCourse.id = Book.course_id')   //关联查询课程名
                ->all();
        
        return ArrayHelper::map($query, 'id', 'course_name');
    }
    
    /**
     * 查询预约人
     * @return array
     */
    public function getBooker()
    {
        $query = (new Query())
                ->select(['SceneBook.booker_id AS id', 'User.nickname AS name'])
                ->from(['SceneBook' => SceneBook::tableName()])
                ->leftJoin(['User' => User::tableName()], 'User.id = SceneBook.booker_id')
                ->all();
        
        return ArrayHelper::map($query, 'id', 'name');
    }

    /**
     * 获取更改预约前后的内容
     * @param SceneBook $model
     * @param array $post
     * @return type
     */
    public function getUpdateContent($model, $post)
    {
        $content = [];
        //获取所有新属性值
        $newAttr = $model->getDirtyAttributes();
        //获取所有旧属性值
        $oldAttr = $model->getOldAttributes();
        //获取接洽人
        $contacterUser = $this->getOldNewSceneUser($oldAttr['id'], ArrayHelper::getValue($post, 'SceneBookUser.user_id'));
        $oldBookUser = implode('、', ArrayHelper::getColumn($contacterUser['oldBookUser'], 'nickname'));
        $newBookUser = implode('、', ArrayHelper::getColumn($contacterUser['newBookUser'], 'nickname'));
        
        if($newAttr != null){
            $oldModel = SceneBook::findOne(['id' => $oldAttr['id']]);
            //修改内容
            $content = [
                'site_name' => (isset($newAttr['site_id']) ? "场地名称：【旧】{$oldModel->sceneSite->name}>>【新】{$model->sceneSite->name}，\n\r" : null),
                'date' => (isset($newAttr['date']) ? "日期：【旧】{$oldModel->date}>>【新】{$model->date}，\n\r" : null),
                'time_index' => (isset($newAttr['time_index']) ? "时段：【旧】{$oldModel->time_index}>>【新】{$model->time_index}，\n\r" : null),
                'start_time' => (isset($newAttr['start_time']) ? "开始时间：【旧】{$oldAttr['start_time']}>>【新】{$newAttr['start_time']}，\n\r" : null),
                'course_name' => (isset($newAttr['course_id']) ? "课程名称：【旧】{$oldModel->course->name}>>【新】{$model->course->name}，\n\r" : null),
                'lession_time' => (isset($newAttr['lession_time']) ? "课时：【旧】{$oldAttr['lession_time']}>>【新】{$newAttr['lession_time']}，\n\r" : null),
                'content_type' => (isset($newAttr['content_type']) ? "内容类型：【旧】{$oldAttr['content_type']}>>【新】{$newAttr['content_type']}，\n\r" : null),
                'is_photograph' => (isset($newAttr['is_photograph']) ? "是否拍照：【旧】".($oldAttr['is_photograph'] ? "需要" : "不需要").">>【新】".($newAttr['is_photograph'] ? "需要" : "不需要")."，\n\r" : null),
                'camera_count' => (isset($newAttr['camera_count']) ? "机位数：【旧】{$oldAttr['camera_count']}>>【新】{$newAttr['camera_count']}，\n\r" : null),
                'teacher_id' => (isset($newAttr['teacher_id']) ? "老师：【旧】{$oldModel->teacher->user->nickname}>>【新】{$model->teacher->user->nickname}，\n\r" : null),
                'booker_id' => (isset($newAttr['booker_id']) ? "预约人：【旧】{$oldModel->booker->nickname}>>【新】{$model->booker->nickname}，\n\r" : null),
                'contacter' => (($oldBookUser != $newBookUser) ? "接洽人：【旧】{$oldBookUser}>>【新】{$newBookUser}" : null),
            ];
        }
        
        return $content;
    }
    
    /**
     * 获取旧新预约用户
     * @param string $old_book_id           
     * @param array $post_user_id           
     * @param integer $role             角色：1接洽人，2摄影师
     * @return array
     */
    protected function getOldNewSceneUser($old_book_id, $post_user_id = null, $role = 1)
    {
        $oldBookUser = [];
        $newBookUser = [];
        //旧预约用户
        $oldBookUser = (new Query())->select([
                'SceneBookUser.user_id', 'SceneBookUser.is_primary',
                'User.nickname', 'User.guid', 'User.phone', 'User.email'
            ])->from(['SceneBookUser' => SceneBookUser::tableName()])
            ->leftJoin(['User' => User::tableName()], 'User.id = SceneBookUser.user_id')
            ->where(['SceneBookUser.book_id' => $old_book_id])
            ->andWhere(['SceneBookUser.is_delete' => 0])
            ->andFilterWhere(['SceneBookUser.role' => $role])
            ->orderBy(['sort_order' => SORT_ASC])->all();
        if($post_user_id != null){
            //新预约用户
            $newBookUser = (new Query())->select(['User.nickname', 'User.guid', 'User.phone', 'User.email'])
                ->from(['User' => User::tableName()])
                ->where(['User.id' => $post_user_id])->all();
        }
        
        return [
            'oldBookUser' => $oldBookUser,
            'newBookUser' => $newBookUser
        ];
    }
}
