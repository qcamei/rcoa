<?php

use common\models\mconline\McbsAttention;
use common\models\mconline\McbsCourse;
use common\models\mconline\McbsCourseUser;
use mconline\modules\mcbs\assets\McbsAssets;
use mconline\modules\mcbs\utils\McbsAction;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model McbsCourse */
/* @var $attModel McbsAttention */

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
            *      icon => '按钮图标',
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
                    'icon' => '<i class="fa fa-pencil-square-o"></i> ',
                    'options' => ['class' => 'btn btn-primary'],
                    'conditions' => $isPermission && $model->status == McbsCourse::NORMAL_STATUS,
                ],
                [
                    'controllerId' => 'default',
                    'name' => Yii::t('app', 'Close').Yii::t('app', 'Courses'),
                    'url' => ['close', 'id' => $model->id],
                    'icon' => '<i class="fa fa-power-off"></i> ',
                    'options' => ['id'=>'close-courses','class' => 'btn btn-danger', 
                                'onclick'=>'return showElemModal($(this));'],
                    'conditions' => $isPermission && $model->status == McbsCourse::NORMAL_STATUS,
                ],
                [
                    'controllerId' => 'default',
                    'name' => Yii::t('app', 'Open').Yii::t('app', 'Courses'),
                    'url' => ['open', 'id' => $model->id],
                    'icon' => '<i class="fa fa-refresh"></i> ',
                    'options' => ['id'=>'open-courses','class' => 'btn btn-success',
                                    'onclick'=>'return showElemModal($(this))'],
                    'conditions' => $isPermission && $model->status == McbsCourse::CLOSE_STATUS,
                ],
                [
                    'controllerId' => 'default',
                    'name' => Yii::t('app', 'Publish').Yii::t('app', 'Courses'),
                    'url' => ['publish', 'id' => $model->id],
                    'icon' => '<i class="fa fa-external-link"></i> ',
                    'options' => ['id'=>'publish-courses','class' => 'btn btn-info',
                                   'onclick'=>'return showElemModal($(this))'],
                    'conditions' => $isPermission ,
                ],
                [
                    'controllerId' => 'default',
                    'name' => Yii::t('app', 'Cancel').Yii::t('app', 'Attention'),
                    'url' => ['cancel-attention', 'id' => $model->id],
                    'icon' => '<i class="fa fa-heart"></i> ',
                    'options' => ['id' => 'cancel_attention', 'class' => 'btn btn-danger',
                                'style'=>'float: right;',
                                'onclick'=>'return showElemModal($(this))'],
                    'conditions' => !$attModel->isNewRecord,
                ],
                [
                    'controllerId' => 'default',
                    'name' => Yii::t('app', 'Attention').Yii::t('app', 'Courses'),
                    'url' => ['attention', 'id' => $model->id],
                    'icon' => '<i class="fa fa-heart-o"></i> ',
                    'options' => ['class' => 'btn btn-success', 'style'=>'float: right;',],
                    'conditions' => $attModel->isNewRecord,
                ],
                [
                    'controllerId' => 'activity-file',
                    'name' => Yii::t('app', 'File').Yii::t('app', 'List'),
                    'url' => ['activity-file/index', 'course_id' => $model->id],
                    'icon' => '<i class="fa fa-file"></i> ',
                    'options' => ['class' => 'btn btn-default'],
                    'conditions' => true,
                ],
                
            ];

            foreach ($menuItems AS $item){
                if($item['conditions']){
                    echo Html::a($item['icon'].$item['name'], $item['url'], $item['options']).' ';
                }
            }

        ?>        
    </p>
    
    <div class="col-md-6 col-xs-12 frame frame-left">
        <div class="col-xs-12 frame-title">
            <i class="icon fa fa-file-text-o"></i>
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
            <i class="icon fa fa-users"></i>
            <span><?= Yii::t('app', 'Help Man') ?></span>
            <div class="framebtn">
                <?php 
                   if($isPermission && $model->status == McbsCourse::NORMAL_STATUS)         
                        echo Html::a('<i class="fa fa-user-plus"></i> '.Yii::t('app', 'Add'),
                        ['course-make/create-helpman', 'course_id' => $model->id], 
                        ['id' => 'add-helpman','class' => 'btn btn-sm btn-success',
                        'onclick'=>'return showElemModal($(this));'])
                ?>
            </div>
        </div>
        <div id="help-man" class="col-xs-12 frame-table frame-right-table">
            <center>加载中...</center>
        </div>
    </div>
    
    <div class="col-xs-12 frame">
        <div class="col-xs-12 frame-title">
            <i class="icon fa fa-cubes"></i>
            <span><?= Yii::t('app', 'Course Frame') ?></span>
            <div class="framebtn">
                <?= Html::a('<i class="fa fa-sign-in"></i> '.Yii::t('app', '导入'),'javascript:;', [
                    'class' => 'btn btn-sm btn-info disabled'
                ]) ?>
                <?= Html::a('<i class="fa fa-sign-out"></i> '.Yii::t('app', '导出'),'javascript:;', [
                    'class' => 'btn btn-sm btn-info disabled'
                ]) ?>
            </div>
        </div>
        <div id="cou-frame" class="col-xs-12 frame-table">
            <div style="width: 100%;min-height: 340px;"><center>加载中...</center></div>
        </div>
    </div>
    
    <div class="col-xs-12 frame">
        <div class="col-xs-12 frame-title">
            <i class="icon fa fa-history"></i>
            <span><?= Yii::t('app', 'Action Log') ?></span>
        </div>
        <div id="action-log" class="col-xs-12 frame-table course-make-actlog">
            <div style="width: 100%;min-height: 340px;"><center>加载中...</center></div>
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
    
    /** 显示模态框 */
    window.showElemModal = function(elem){
        $(".myModal").html("");
        $('.myModal').modal("show").load(elem.attr("href"));
        return false;
    }    
    
    
            
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>
