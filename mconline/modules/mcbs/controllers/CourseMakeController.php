<?php

namespace mconline\modules\mcbs\controllers;

use common\models\mconline\McbsActionLog;
use common\models\mconline\McbsActivityFile;
use common\models\mconline\McbsActivityType;
use common\models\mconline\McbsCourse;
use common\models\mconline\McbsCourseActivity;
use common\models\mconline\McbsCourseBlock;
use common\models\mconline\McbsCourseChapter;
use common\models\mconline\McbsCoursePhase;
use common\models\mconline\McbsCourseSection;
use common\models\mconline\McbsCourseUser;
use common\models\mconline\McbsMessage;
use common\models\mconline\McbsRecentContacts;
use common\models\mconline\searchs\McbsActionLogSearch;
use common\models\mconline\searchs\McbsCourseActivitySearch;
use common\models\mconline\searchs\McbsCourseBlockSearch;
use common\models\mconline\searchs\McbsCourseChapterSearch;
use common\models\mconline\searchs\McbsCoursePhaseSearch;
use common\models\mconline\searchs\McbsCourseSectionSearch;
use common\models\mconline\searchs\McbsCourseUserSearch;
use common\models\mconline\searchs\McbsMessageSearch;
use common\models\User;
use mconline\modules\mcbs\utils\McbsAction;
use wskeee\webuploader\models\Uploadfile;
use Yii;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotAcceptableHttpException;
use yii\web\NotFoundHttpException;

/**
 * CourseMakeController implements the CRUD actions for McbsCourse model.
 */
class CourseMakeController extends Controller
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
     * 是否拥有权限
     */
    private static function IsPermission($course_id, $status, $is_array = true)
    {
        if($is_array)
            $privilege = McbsCourseUser::OWNERSHIP;
        else
            $privilege = [McbsCourseUser::EDIT, McbsCourseUser::OWNERSHIP];
        
        return McbsAction::getIsPermission($course_id, $privilege) && $status == McbsCourse::NORMAL_STATUS;
    }


    /**
     * Lists all McbsCourseUser models.
     * @return mixed
     */
    public function actionHelpmanIndex($course_id)
    {
        $model = $this->findMcbsCourseModel($course_id);
        $searchModel = new McbsCourseUserSearch();
        
        return $this->renderAjax('helpman-index', [
            'dataProvider' => $searchModel->search(['course_id'=>$model->id]),
            'isPermission' => self::IsPermission($model->id, $model->status)
        ]);
    }

    /**
     * Creates a new McbsCourseUser model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateHelpman($course_id)
    {
        $model = new McbsCourseUser(['course_id' => $course_id]);
        $model->loadDefaultValues();
        
        if(!self::IsPermission($course_id,$model->course->status))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->CreateHelpman($model, Yii::$app->request->post());
            return [
                'code'=> $result ? 200 : 404,
                'message' => ''
            ];
            //return $this->redirect(['default/view', 'id' => $course_id]);
        } else {
            return $this->renderAjax('create-helpman', [
                'model' => $model,
                'contacts' => $this->getRecentContacts(),
                'helpmans' => $this->getHelpManList($course_id,$model->course->created_by),
            ]);
        }
    }

    /**
     * Updates an existing McbsCourseUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdateHelpman($id)
    {
        $model = McbsCourseUser::findOne($id);
        
        if(!self::IsPermission($model->course_id,$model->course->status))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->UpdateHelpman($model);
            return [
                'code'=> $result ? 200 : 404,
                'message' => ''
            ];
            //return $this->redirect(['default/view', 'id' => $model->course_id]);
        } else {
            return $this->renderAjax('update-helpman', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing McbsCourseUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDeleteHelpman($id)
    {
        $model = McbsCourseUser::findOne($id);
        if(!self::IsPermission($model->course_id,$model->course->status))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->DeleteHelpman($model);
            return [
                'code'=> $result ? 200 : 404,
                'message' => ''
            ];
            //return $this->redirect(['default/view', 'id' => $model->course_id]);
        } else {
            return $this->renderAjax('delete-helpman',[
                'model' => $model
            ]);
        }
    }

    /**
     * Lists all CouserFrame.
     * @return mixed
     */
    public function actionCouframeIndex($course_id)
    {
        
        $model = $this->findMcbsCourseModel($course_id);
        $phaseSearch = new McbsCoursePhaseSearch();
        $blockSearch = new McbsCourseBlockSearch();
        $chapterSearch = new McbsCourseChapterSearch();
        $sectionSearch = new McbsCourseSectionSearch();
        $activitySearch = new McbsCourseActivitySearch();
        
        return $this->renderAjax('couframe-index', [
            'course_id' => $model->id,
            'isPermission' => self::IsPermission($model->id, $model->status, false),
            'dataCouphase' => $phaseSearch->search(['course_id'=>$course_id]),
            'dataCoublock' => $blockSearch->search(['course_id'=>$course_id]),
            'dataCouchapter' => $chapterSearch->search(['course_id'=>$course_id]),
            'dataCousection' => $sectionSearch->search(['course_id'=>$course_id]),
            'dataCouactivity' => $activitySearch->search(['course_id'=>$course_id]),
        ]);
    }
    
    /**
     * Creates a new McbsCoursePhase model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateCouphase($course_id)
    {        
        $model = new McbsCoursePhase(['id' => md5(rand(1,10000) + time()), 'course_id' => $course_id]);
        $model->loadDefaultValues();
        if(!self::IsPermission($model->course->id, $model->course->status, false))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->CreateCouFrame($model,Yii::t('app', 'Phase'),$course_id);
            return [
                'code'=> $result ? 200 : 404,
                'data' =>$result ? [
                    'frame_name'=>'phase',
                    'sub_frame'=>'block',
                    'id'=>$model->id,
                    'parent_id'=>'',
                    'name'=>$model->name,
                    'value_percent'=>'（'. number_format($model->value_percent,2).'分）',
                ] : [],
                'message' => ''
            ];
            //return $this->redirect(['default/view', 'id' => $course_id]);
        } else {
            return $this->renderAjax('create-couframe', [
                'model' => $model,
                'course_id'=>$model->course_id,
                'title' => Yii::t('app', 'Phase')
            ]);
        }
    }
    
    /**
     * Updates an existing McbsCoursePhase model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdateCouphase($id)
    {
        $model = McbsCoursePhase::findOne($id);
        if(!self::IsPermission($model->course_id, $model->course->status, false))
            throw new NotAcceptableHttpException('无权限操作！');
        
        $post = Yii::$app->request->post();
        if(isset($post['McbsCoursePhase']))
            $post['McbsCoursePhase']['value_percent'] = (float)$post['McbsCoursePhase']['value_percent'];
        
        if ($model->load($post)) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->UpdateCouFrame($model,Yii::t('app', 'Phase'),$model->course_id);
            return [
                'code'=> $result ? 200 : 404,
                'data'=> $result ? [
                    'id'=>$model->id,
                    'name'=>$model->name,
                    'value_percent'=>'（'. number_format($model->value_percent,2).'分）',
                ] : [],
                'message' => ''
            ];
            //return $this->redirect(['default/view', 'id' => $model->course_id]);
        } else {
            return $this->renderAjax('update-couframe', [
                'model' => $model,
                'course_id' => $model->course_id,
                'title' => Yii::t('app', 'Phase')
            ]);
        }
    }

    /**
     * Deletes an existing McbsCoursePhase model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDeleteCouphase($id)
    {
        $model = McbsCoursePhase::findOne($id);
        if(!self::IsPermission($model->course_id, $model->course->status, false))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->DeleteCouFrame($model,Yii::t('app', 'Phase'),$model->course_id);
            return [
                'code'=> $result ? 200 : 404,
                'message' => ''
            ];
            //return $this->redirect(['default/view', 'id' => $model->course_id]);
        } else {
            return $this->renderAjax('delete-couframe',[
                'model' => $model,
                'course_id' => $model->course_id,
                'title' => Yii::t('app', 'Phase')
            ]);
        }
    }
    
    /**
     * Creates a new McbsCourseBlock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateCoublock($phase_id)
    {
        $model = new McbsCourseBlock(['id' => md5(rand(1,10000) + time()), 'phase_id' => $phase_id]);
        $model->loadDefaultValues();
        if(!self::IsPermission($model->phase->course_id, $model->phase->course->status, false))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->CreateCouFrame($model,Yii::t('app', 'Block'),$model->phase->course_id);
            return [
                'code'=> $result ? 200 : 404,
                'data'=> $result ? [
                    'frame_name'=>'block',
                    'sub_frame'=>'chapter',
                    'id'=>$model->id,
                    'parent_id'=>$model->phase_id,
                    'name'=>$model->name,
                ] : [],
                'message' => ''
            ];
            //return $this->redirect(['default/view', 'id' => $model->phase->course_id]);
        } else {
            return $this->renderAjax('create-couframe', [
                'model' => $model,
                'course_id' => $model->phase->course_id,
                'title' => Yii::t('app', 'Block')
            ]);
        }
    }
    
    /**
     * Updates an existing McbsCourseBlock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdateCoublock($id)
    {
        $model = McbsCourseBlock::findOne($id);
        if(!self::IsPermission($model->phase->course_id, $model->phase->course->status, false))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->UpdateCouFrame($model,Yii::t('app', 'Block'),$model->phase->course_id);
            return [
                'code'=> $result ? 200 : 404,
                'data'=> $result ? [
                    'id'=>$model->id,
                    'name'=>$model->name,
                ] : [],
                'message' => ''
            ];
            //return $this->redirect(['default/view', 'id' => $model->phase->course_id]);
        } else {
            return $this->renderAjax('update-couframe', [
                'model' => $model,
                'course_id' => $model->phase->course_id,
                'title' => Yii::t('app', 'Block')
            ]);
        }
    }

    /**
     * Deletes an existing McbsCourseBlock model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDeleteCoublock($id)
    {
        $model = McbsCourseBlock::findOne($id);
        if(!self::IsPermission($model->phase->course_id, $model->phase->course->status, false))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->DeleteCouFrame($model,Yii::t('app', 'Block'),$model->phase->course_id);
            return [
                'code'=> $result ? 200 : 404,
                'message' => ''
            ];
            //return $this->redirect(['default/view', 'id' => $model->phase->course_id]);
        } else {
            return $this->renderAjax('delete-couframe',[
                'model' => $model,
                'course_id' => $model->phase->course_id,
                'title' => Yii::t('app', 'Block')
            ]);
        }
    }
    
    /**
     * Creates a new McbsCourseChapter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateCouchapter($block_id)
    {
        $model = new McbsCourseChapter(['id' => md5(rand(1,10000) + time()), 'block_id' => $block_id]);
        $model->loadDefaultValues();
        if(!self::IsPermission($model->block->phase->course_id, $model->block->phase->course->status, false))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->CreateCouFrame($model,Yii::t('app', 'Chapter'),$model->block->phase->course_id);
            return [
                'code'=> $result ? 200 : 404,
                'data'=> $result ? [
                    'frame_name'=>'chapter',
                    'sub_frame'=>'section',
                    'id'=>$model->id,
                    'parent_id'=>$model->block_id,
                    'name'=>$model->name,
                ] : [],
                'message' => ''
            ];
            //return $this->redirect(['default/view', 'id' => $model->block->phase->course_id]);
        } else {
            return $this->renderAjax('create-couframe', [
                'model' => $model,
                'course_id' => $model->block->phase->course_id,
                'title' => Yii::t('app', 'Chapter')
            ]);
        }
    }
    
    /**
     * Updates an existing McbsCourseChapter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdateCouchapter($id)
    {
        $model = McbsCourseChapter::findOne($id);
        if(!self::IsPermission($model->block->phase->course_id, $model->block->phase->course->status, false))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->UpdateCouFrame($model,Yii::t('app', 'Chapter'),$model->block->phase->course_id);
            return [
                'code'=> $result ? 200 : 404,
                'data'=> $result ? [
                    'id'=>$model->id,
                    'name'=>$model->name,
                ] : [],
                'message' => ''
            ];
            //return $this->redirect(['default/view', 'id' => $model->block->phase->course_id]);
        } else {
            return $this->renderAjax('update-couframe', [
                'model' => $model,
                'course_id' => $model->block->phase->course_id,
                'title' => Yii::t('app', 'Chapter')
            ]);
        }
    }

    /**
     * Deletes an existing McbsCourseChapter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDeleteCouchapter($id)
    {
        $model = McbsCourseChapter::findOne($id);
        if(!self::IsPermission($model->block->phase->course_id, $model->block->phase->course->status, false))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->DeleteCouFrame($model,Yii::t('app', 'Chapter'),$model->block->phase->course_id);
            return [
                'code'=> $result ? 200 : 404,
                'message' => ''
            ];
            //return $this->redirect(['default/view', 'id' => $model->block->phase->course_id]);
        } else {
            return $this->renderAjax('delete-couframe',[
                'model' => $model,
                'course_id' => $model->block->phase->course_id,
                'title' => Yii::t('app', 'Chapter')
            ]);
        }
    }
    
    /**
     * Creates a new McbsCourseSection model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateCousection($chapter_id)
    {
        $model = new McbsCourseSection(['id' => md5(rand(1,10000) + time()), 'chapter_id' => $chapter_id]);
        $model->loadDefaultValues();
        if(!self::IsPermission($model->chapter->block->phase->course_id, $model->chapter->block->phase->course->status, false))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->CreateCouFrame($model,Yii::t('app', 'Section'),$model->chapter->block->phase->course_id);
            return [
                'code'=> $result ? 200 : 404,
                'data'=> $result ? [
                    'frame_name'=>'section',
                    'sub_frame'=>'activity',
                    'id'=>$model->id,
                    'parent_id'=>$model->chapter_id,
                    'name'=>$model->name,
                ] : [],
                'message' => ''
            ];
            //return $this->redirect(['default/view', 'id' => $model->chapter->block->phase->course_id]);
        } else {
            return $this->renderAjax('create-couframe', [
                'model' => $model,
                'course_id' => $model->chapter->block->phase->course_id,
                'title' => Yii::t('app', 'Section')
            ]);
        }
    }
    
    /**
     * Updates an existing McbsCourseSection model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdateCousection($id)
    {
        $model = McbsCourseSection::findOne($id);
        if(!self::IsPermission($model->chapter->block->phase->course_id, $model->chapter->block->phase->course->status, false))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->UpdateCouFrame($model,Yii::t('app', 'Section'),$model->chapter->block->phase->course_id);
            return [
                'code'=> $result ? 200 : 404,
                'data'=> $result ? [
                    'id'=>$model->id,
                    'name'=>$model->name,
                ] : [],
                'message' => ''
            ];
            //return $this->redirect(['default/view', 'id' => $model->chapter->block->phase->course_id]);
        } else {
            return $this->renderAjax('update-couframe', [
                'model' => $model,
                'course_id' => $model->chapter->block->phase->course_id,
                'title' => Yii::t('app', 'Section')
            ]);
        }
    }

    /**
     * Deletes an existing McbsCourseSection model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDeleteCousection($id)
    {
        $model = McbsCourseSection::findOne($id);
        if(!self::IsPermission($model->chapter->block->phase->course_id, $model->chapter->block->phase->course->status, false))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->DeleteCouFrame($model,Yii::t('app', 'Section'),$model->chapter->block->phase->course_id);
            return [
                'code'=> $result ? 200 : 404,
                'message' => ''
            ];
            //return $this->redirect(['default/view', 'id' => $model->chapter->block->phase->course_id]);
        } else {
            return $this->renderAjax('delete-couframe',[
                'model' => $model,
                'course_id' => $model->chapter->block->phase->course_id,
                'title' => Yii::t('app', 'Section')
            ]);
        }
    }
    
    /**
     * Displays a single McbsCourseActivity model.
     * @param string $id
     * @return mixed
     */
    public function actionCouactivityView($id)
    {
        $model = McbsCourseActivity::findOne($id);
        $dataProvider = new ArrayDataProvider([
            'allModels' => $this->getUploadedActivityFile($id),
        ]);
        
        $number = (new Query())->from(McbsMessage::tableName())->where(['activity_id'=>$model->id])->count();
        
        return $this->render('activity-view', [
            'model' => $model,
            'isPermission' => self::IsPermission($model->section->chapter->block->phase->course_id, $model->section->chapter->block->phase->course->status, false),
            'dataProvider' => $dataProvider,
            'number' => $number
        ]);
    }
    
    /**
     * Creates a new McbsCourseActivity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreateCouactivity($section_id)
    {
        $model = new McbsCourseActivity(['id' => md5(rand(1,10000) + time()), 'section_id' => $section_id]);
        $model->loadDefaultValues();
        if(!self::IsPermission($model->section->chapter->block->phase->course_id, $model->section->chapter->block->phase->course->status, false))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->CreateCouactivity($model,Yii::$app->request->post());
            return [
                'code'=> $result ? 200 : 404,
                'data'=> $result ? [
                    'frame_name'=>'activity',
                    'id'=>$model->id,
                    'parent_id'=>$model->section_id,
                    'icon_path'=>$model->type->icon_path,
                    'type_name'=>$model->type->name,
                    'name'=>$model->name,
                ] : [],
                'message' => ''
            ];
            //return $this->redirect(['couactivity-view', 'id' => $model->id]);
        } else {
            return $this->renderAjax('create-activity', [
                'model' => $model,
                'course_id'=>$model->section->chapter->block->phase->course_id,
                'actiType'=>$this->getActivityType(),
                'file' => $this->getUploadedActivityFile($model->id),
                'title' => Yii::t('app', 'Activity')
            ]);
        }
    }
    
    /**
     * Updates an existing McbsCourseActivity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdateCouactivity($id)
    {
        $model = McbsCourseActivity::findOne($id);
        if(!self::IsPermission($model->section->chapter->block->phase->course_id, $model->section->chapter->block->phase->course->status, false))
            throw new NotAcceptableHttpException('无权限操作！');
        $post = Yii::$app->request->post();
        if(isset($post['McbsCourseActivity']))
            $post['McbsCourseActivity']['type_id'] = (integer)$post['McbsCourseActivity']['type_id'];
        if ($model->load($post)) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->UpdateCouactivity($model,$post);
            return [
                'code'=> $result ? 200 : 404,
                'data'=> $result ? [
                    'id'=>$model->id,
                    'icon_path'=>$model->type->icon_path,
                    'type_name'=>"【{$model->type->name}】：",
                    'name'=>$model->name,
                ] : [],
                'message' => ''
            ];
            //return $this->redirect(['couactivity-view', 'id' => $model->id]);
        } else {
            return $this->renderAjax('update-activity', [
                'model' => $model,
                'course_id'=>$model->section->chapter->block->phase->course_id,
                'actiType'=>$this->getActivityType(),
                'file' => $this->getUploadedActivityFile($model->id),
                'title' => Yii::t('app', 'Activity')
            ]);
        }
    }

    /**
     * Deletes an existing McbsCourseActivity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDeleteCouactivity($id)
    {
        $model = McbsCourseActivity::findOne($id);
        if(!self::IsPermission($model->section->chapter->block->phase->course_id, $model->section->chapter->block->phase->course->status, false))
            throw new NotAcceptableHttpException('无权限操作！');
        
        if ($model->load(Yii::$app->request->post())) {
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->DeleteCouactivity($model);
            return [
                'code'=> $result ? 200 : 404,
                'message' => ''
            ];
            //return $this->redirect(['default/view', 'id' => $model->section->chapter->block->phase->course_id]);
        } else {
            return $this->renderAjax('delete-activity',[
                'model' => $model,
                'course_id'=> $model->section->chapter->block->phase->course_id,
                'title' => Yii::t('app', 'Activity')
            ]);
        }
    }

    /**
     * Lists all McbsMessage models.
     * @return mixed
     */
    public function actionMesIndex($course_id,$activity_id)
    {
        $searchModel = new McbsMessageSearch();
        
        return $this->renderAjax('mes-index', [
            'dataProvider' => $searchModel->search(['course_id'=>$course_id,'activity_id'=>$activity_id])
        ]);
    }
    
    /**
     * Creates a new McbsMessage model.
     * @return mixed
     */
    public function actionCreateMessage($activity_id)
    {
        $model = new McbsMessage(['activity_id'=>$activity_id]);
        $model->loadDefaultValues();
        
        if(Yii::$app->request->isPost){
            Yii::$app->getResponse()->format = 'json';
            $result = McbsAction::getInstance()->CreateMessage($model,Yii::$app->request->post());
            return [
                'code'=> $result ? 200 : 404,
                'message' => ''
            ];
            //return $this->redirect(['couactivity-view', 'id' => $model->activity_id]);
        } else {
            return $this->goBack(['couactivity-view', 'id' => $activity_id]);
        }
    }
   
    /**
     * Lists all McbsActionLog models.
     * @return mixed
     */
    public function actionLogIndex($course_id,$relative_id=null,$page=null)
    {
        $searchModel = new McbsActionLogSearch();
        
        return $this->renderAjax('log-index', [
            'course_id' => $course_id,
            'relative_id' => $relative_id,
            'page' => $page,
            'dataProvider' => $searchModel->search(['course_id'=>$course_id,'relative_id'=>$relative_id,'page'=>$page]),
        ]);
    }
    
    /**
     * Displays a single McbsActionLog model.
     * @param string $id
     * @return mixed
     */
    public function actionLogView($id)
    {
        return $this->renderAjax('log-view', [
            'model' => McbsActionLog::findOne($id),
        ]);
    }
    
    /**
     * Move a single AllMcbs model.
     * @return mixed
     */
    public function actionMove()
    {
        Yii::$app->getResponse()->format = 'json';
        $num = 0;
        try
        {
            if(Yii::$app->request->isPost){
                $table = ArrayHelper::getValue(Yii::$app->request->post(), 'tableName');
                $oldIndexs = ArrayHelper::getValue(Yii::$app->request->post(), 'oldIndexs');
                $newIndexs = ArrayHelper::getValue(Yii::$app->request->post(), 'newIndexs');
                $oldItems = json_decode(json_encode($oldIndexs), true);
                $newItems = json_decode(json_encode($newIndexs), true);
                foreach ($newItems as $id => $sortOrder)
                    $num += $this->UpdateTableAttribute ($table, $id, $sortOrder);
                
            }     
        } catch (Exception $ex) {
            $errors [] = $ex->getMessage();
        }
        
        return [
            'code' => $num > 0 ? 200 : 404,
            'oldItems' => $oldItems,
            'num' => $num,
            'message' => ''
        ];
    }
    
    /**
     * Finds the McbsCourse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return McbsCourse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findMcbsCourseModel($id)
    {
        if (($model = McbsCourse::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    
    /**
     * 修改表属性值
     * @param string $table                           表名
     * @param string $id                              id
     * @param integer $sortOrder                      顺序
     * @return integer|null
     */
    private function UpdateTableAttribute($table,$id,$sortOrder)
    {
        $number = Yii::$app->db->createCommand()
           ->update("{{%$table}}",['sort_order'=>$sortOrder],['id'=>$id])->execute();
        
        if($number > 0)
            return $number;
        
        return null;
    }
    
    /**
     * 获取和自己关联的最近联系人
     * @return array
     */
    public function getRecentContacts()
    {
        return (new Query())->select(['User.id','User.nickname','User.avatar'])
                ->from(['RecentContacts'=>McbsRecentContacts::tableName()])
                ->leftJoin(['User'=> User::tableName()],'User.id = RecentContacts.contacts_id')
                ->where(['user_id'=> Yii::$app->user->id])
                ->orderBy('RecentContacts.updated_at DESC')->limit(8)->all();        
    }


    /**
     * 获取所有协助人员
     * @param string $user_id                           用户id
     * @return array
     */
    public function getHelpManList($course_id, $user_id)
    {
        //查找已添加的协作人员
        $courUsers = (new Query())->select(['user_id'])
                ->from(McbsCourseUser::tableName())->where(['course_id' => $course_id])
                ->all();
        $courUserIds = ArrayHelper::getColumn($courUsers, 'user_id');
        
        //合并创建者和已添加的协作人员
        $userIds = array_merge([$user_id],$courUserIds);
        //查找所有可以添加的协作人员
        $users = (new Query())->select(['id', 'nickname'])
                ->from(User::tableName())->where(['NOT IN','id',$userIds])
                ->all();
        
        return ArrayHelper::map($users, 'id', 'nickname');
    }
    
    /**
     * 获取活动类型
     * @return array
     */
    public function getActivityType()
    {
        return (new Query())->from([McbsActivityType::tableName()])->all();
    }
    
    /**
     * 获取已上传的活动文件
     * @param string $activity_id               活动id
     * @return array
     */
    public function getUploadedActivityFile($activity_id)
    {
        return (new Query())->select(['ActivityFile.file_id AS id','Uploadfile.name','Uploadfile.is_del','Uploadfile.size'])
                ->from(['ActivityFile'=>McbsActivityFile::tableName()])
                ->leftJoin(['Uploadfile'=> Uploadfile::tableName()], 'Uploadfile.id = ActivityFile.file_id')
                ->where(['activity_id'=>$activity_id])
                ->all();
    }
}
