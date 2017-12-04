<?php

use common\models\mconline\McbsCourse;
use common\models\mconline\McbsCourseUser;
use mconline\modules\mcbs\assets\McbsAssets;
use mconline\modules\mcbs\utils\McbsAction;
use wskeee\rbac\components\ResourceHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model McbsCourse */

$this->title = Yii::t(null, '{Courses}{Make}',['Courses'=> Yii::t('app', 'Courses'),'Make'=> Yii::t('app', 'Make')]);

$this->params['breadcrumbs'][] = ['label' => Yii::t(null, '{Mcbs}{Courses}', [
    'Mcbs' => Yii::t('app', 'Mcbs'),
    'Courses' => Yii::t('app', 'Courses'),
]), 'url' => ['index']];

$this->params['breadcrumbs'][] = $this->title;

?>
<div class="mcbs-course-view mcbs default-view">

    <h4><?= Html::encode($this->title) ?></h4>

    <p>
        <?php
            /**
            * $menuItems = [
            *   [
            *      controllerId => 控制器ID,                          
            *      name  => 菜单名称，
            *      url  =>  菜单url，
            *      options  => 菜单属性，
            *      conditions  => 菜单显示条件，
            *   ],
            * ]
            */
            $controllerId = Yii::$app->controller->id;          //当前控制器
            $actionId = Yii::$app->controller->action->id;      //当前行为方法
            $menuItems = [
                [
                    'controllerId' => 'default',
                    'name' => Yii::t('app', 'Edit').Yii::t('app', 'Courses'),
                    'url' => ['update', 'id' => $model->id],
                    'options' => ['class' => 'btn btn-primary'],
                    'conditions' => McbsAction::getIsPermission($model->id, McbsCourseUser::OWNERSHIP) 
                                    && $model->status == McbsCourse::NORMAL_STATUS
                                    && $model->is_publish == 0,
                ],
                [
                    'controllerId' => 'default',
                    'name' => Yii::t('app', 'Close').Yii::t('app', 'Courses'),
                    'url' => ['close', 'id' => $model->id],
                    'options' => ['id'=>'close-courses','class' => 'btn btn-danger'],
                    'conditions' => McbsAction::getIsPermission($model->id, McbsCourseUser::OWNERSHIP)
                                    && $model->status == McbsCourse::NORMAL_STATUS
                                    && $model->is_publish == 0,
                ],
                [
                    'controllerId' => 'default',
                    'name' => Yii::t('app', 'Open').Yii::t('app', 'Courses'),
                    'url' => ['open', 'id' => $model->id],
                    'options' => ['id'=>'open-courses','class' => 'btn btn-success'],
                    'conditions' => McbsAction::getIsPermission($model->id, McbsCourseUser::OWNERSHIP)
                                    && $model->status == McbsCourse::CLOSE_STATUS
                                    && $model->is_publish == 0,
                ],
                [
                    'controllerId' => 'default',
                    'name' => Yii::t('app', 'Publish').Yii::t('app', 'Courses'),
                    'url' => ['publish', 'id' => $model->id],
                    'options' => ['id'=>'publish-courses','class' => 'btn btn-info'],
                    'conditions' => McbsAction::getIsPermission($model->id, McbsCourseUser::OWNERSHIP)
                                    && $model->status == McbsCourse::NORMAL_STATUS
                                    && $model->is_publish == 0,
                ],
                [
                    'controllerId' => 'default',
                    'name' => Yii::t('app', 'Cancel').Yii::t('app', 'Attention'),
                    'url' => ['cancel-attention', 'id' => $model->id],
                    'options' => ['id' => 'cancel_attention', 'class' => 'btn btn-danger'],
                    'conditions' => $attModel != null,
                ],
                [
                    'controllerId' => 'default',
                    'name' => Yii::t('app', 'Attention').Yii::t('app', 'Courses'),
                    'url' => ['attention', 'id' => $model->id],
                    'options' => ['class' => 'btn btn-success'],
                    'conditions' => $attModel == null,
                ],
                [
                    'controllerId' => 'activity-file',
                    'name' => Yii::t('app', 'File').Yii::t('app', 'List'),
                    'url' => ['activity-file/index', 'course_id' => $model->id],
                    'options' => ['class' => 'btn btn-default'],
                    'conditions' => true,
                ],
                
            ];

            foreach ($menuItems AS $item){
                if($item['conditions']){
                    echo Html::a($item['name'], $item['url'], $item['options']).' ';
                }
            }

        ?>        
    </p>
    
    <div class="col-md-6 col-xs-12 frame frame-left">
        <div class="col-xs-12 frame-title">
            <span><?= Yii::t('app', 'Course Info') ?></span>
        </div>
        <?= DetailView::widget([
            'model' => $model,
            //'options' => ['class' => 'table table-bordered detail-view '],
            'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
            'attributes' => [
                //['label' => '<span class="viewdetail-th-head">'.Yii::t('app', 'Course Info').'</span>', 'value' => ''],
                [
                    'attribute' => 'item_type_id',
                    'value' => !empty($model->item_type_id) ? $model->itemType->name : null,
                ],
                [
                    'attribute' => 'item_id',
                    'value' => !empty($model->item_id) ? $model->item->name : null,
                ],
                [
                    'attribute' => 'item_child_id',
                    'value' => !empty($model->item_child_id) ? $model->itemChild->name : null,
                ],
                [
                    'attribute' => 'course_id',
                    'value' => !empty($model->course_id) ? $model->course->name : null,
                ],
                [
                    'attribute' => 'created_by',
                    'value' => !empty($model->created_by) ? $model->createBy->nickname : null,
                ],
                [
                    'attribute' => 'created_at',
                    'value' => date('Y-m-d H:i', $model->created_at),
                ],
                [
                    'attribute' => 'close_time',
                    'value' => date('Y-m-d H:i', $model->close_time),
                ],
                [
                    'label' => Yii::t('app', 'Course Des'),
                    'format' => 'raw',
                    'value' => "<div class=\"viewdetail-td-des\">{$model->des}</div>",
                ],
            ],
        ]) ?>
    </div>
    
    <div class="col-md-6 col-xs-12 frame frame-right">
        <div class="col-xs-12 frame-title">
            <span><?= Yii::t('app', 'Help Man') ?></span>
            <div class="framebtn">
                <?php 
                   if(McbsAction::getIsPermission($model->id, McbsCourseUser::OWNERSHIP))         
                        echo Html::a(Yii::t('app', 'Add'),
                        ['course-make/create-helpman', 'course_id' => $model->id], 
                        ['id' => 'add-helpman','class' => 'btn btn-sm btn-success'])
                ?>
            </div>
        </div>
        <div id="help-man" class="col-xs-12 frame-table frame-right-table">
            
        </div>
    </div>
    
    <div class="col-xs-12 frame">
        <div class="col-xs-12 frame-title">
            <span><?= Yii::t('app', 'Course Frame') ?></span>
            <div class="framebtn">
                <?= Html::a(Yii::t('app', '导入'),'javascript:;', [
                    'class' => 'btn btn-sm btn-info disabled'
                ]) ?>
                <?= Html::a(Yii::t('app', '导出'),'javascript:;', [
                    'class' => 'btn btn-sm btn-info disabled'
                ]) ?>
            </div>
        </div>
        <div id="cou-frame" class="col-xs-12 frame-table">
           
        </div>
    </div>
    
    <div class="col-xs-12 frame">
        <div class="col-xs-12 frame-title">
            <span><?= Yii::t('app', 'Action Log') ?></span>
        </div>
        <div id="action-log" class="col-xs-12 frame-table course-make-actlog">
           
        </div>
    </div>
    
</div>

<?= $this->render('/layouts/model') ?>

<?php

$helpman = Url::to(['course-make/helpman-index', 'course_id' => $model->id]);
$couframe = Url::to(['course-make/couframe-index', 'course_id' => $model->id]);
$actlog = Url::to(['course-make/log-index', 'course_id' => $model->id]);

$js = 
<<<JS
    //加载协作人员列表
    $("#help-man").load("$helpman"); 
    //加载课程框架列表
    $("#cou-frame").load("$couframe"); 
    //加载操作记录列表
    $("#action-log").load("$actlog"); 
    //添加协作人弹出框
    $("#add-helpman").click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
    //关闭课程弹出框
    $("#close-courses").click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
    //开启课程弹出框
    $("#open-courses").click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
    //发布课程弹出框
    $("#publish-courses").click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
    //取消关注弹出框
    $("#cancel-attention").click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
            
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>
