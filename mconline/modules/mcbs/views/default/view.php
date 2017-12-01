<?php

use common\models\mconline\McbsCourse;
use mconline\modules\mcbs\assets\McbsAssets;
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
        <?= Html::a(Yii::t(null, '{edit}{courses}',[
                'edit' => Yii::t('app', 'Edit'),
                'courses' => Yii::t('app', 'Courses')
            ]), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) 
        ?>
        <?= Html::a(Yii::t(null, '{close}{courses}',[
                'close' => Yii::t('app', 'Close'),
                'courses' => Yii::t('app', 'Courses')
            ]), ['close', 'id' => $model->id], ['id'=>'close-courses','class' => 'btn btn-danger']) 
        ?>
        <?= Html::a(Yii::t(null, '{open}{courses}',[
                'open' => Yii::t('app', 'Open'),
                'courses' => Yii::t('app', 'Courses')
            ]), ['open', 'id' => $model->id], ['id'=>'open-courses','class' => 'btn btn-success']) 
        ?>
        <?= Html::a(Yii::t(null, '{publish}{courses}',[
                'publish' => Yii::t('app', 'Publish'),
                'courses' => Yii::t('app', 'Courses')
            ]), ['publish', 'id' => $model->id], ['id'=>'publish-courses','class' => 'btn btn-info']) 
        ?>
        <?= Html::a(Yii::t(null, '{attention}{courses}',[
                'attention' => Yii::t('app', 'Attention'),
                'courses' => Yii::t('app', 'Courses')
            ]), ['attention', 'id' => $model->id], ['id'=>'publish-courses','class' => 'btn btn-success']) 
        ?>
        <?= Html::a(Yii::t(null, '{file}{list}',[
                'file' => Yii::t('app', 'File'),
                'list' => Yii::t('app', 'List')
            ]), ['activity-file/index', 'course_id' => $model->id], ['class' => 'btn btn-default']) 
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
                <?= Html::a(Yii::t('app', 'Add'),
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
            
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>
