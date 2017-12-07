<?php

use common\models\mconline\McbsCourseActivity;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model McbsCourseActivity */

$this->title = "【{$model->section->name}】：{$model->name}" ;
$this->params['breadcrumbs'][] = ['label' => Yii::t(null, '{mcbs}{courses}',[
    'mcbs' => Yii::t('app', 'Mcbs'),
    'courses' => Yii::t('app', 'Courses'),
]), 'url' => ['default/index']];
$this->params['breadcrumbs'][] = [
    'label' => $model->section->chapter->block->phase->course->course->name,
    'url' => ['default/view', 'id' => $model->section->chapter->block->phase->course_id],
];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="mcbs-activity-view mcbs mcbs-activity default-view">
    
    <div class="col-xs-12 frame">
        <div class="col-xs-12 frame-title">
            <i class="icon fa fa-info-circle"></i>
            <span><?= Yii::t(null, "{activity}{info}",[
                'activity' => Yii::t('app', 'Activity'),
                'info' => Yii::t('app', 'Info'),
            ]) ?></span>
        </div>
        <?= DetailView::widget([
            'model' => $model,
            //'options' => ['class' => 'table table-bordered detail-view '],
            'template' => '<tr><th class="viewdetail-th">{label}</th><td class="viewdetail-td">{value}</td></tr>',
            'attributes' => [
                [
                    'label' => Yii::t('app', 'Type'),
                    'format' => 'raw',
                    'value' => !empty($model->type_id) ? "<div class=\"actitype active\">".Html::img([$model->type->icon_path],['class'=>'acticon'])."<div class=\"actname\">{$model->type->name}</div></div>" : null,
                ],
                [
                    'attribute' => 'name',
                    'value' => $model->name,
                ],
                [
                    'attribute' => 'des',
                    'value' => $model->des,
                ],
            ],
        ]) ?>
    </div>
    
    <div class="col-xs-12 frame">
        <div class="col-xs-12 frame-title">
            <i class="icon fa fa-paperclip"></i>
            <span><?= Yii::t(null, "{file}{list}",[
                'file' => Yii::t('app', 'File'),
                'list' => Yii::t('app', 'List'),
            ]) ?></span>
        </div>
        <div class="col-xs-12 frame-table">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                //'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-striped table-list'],
                'layout' => "{items}\n{summary}\n{pager}",
                'summaryOptions' => [
                    //'class' => 'summary',
                    'class' => 'hidden',
                    //'style' => 'float: left'
                ],
                'pager' => [
                    'options' => [
                        //'class' => 'pagination',
                        'class' => 'hidden',
                        //'style' => 'float: right; margin: 0px;'
                    ]
                ],
                'columns' => [
                    [
                        'label' => Yii::t(null, '{file}{name}',['file'=>Yii::t('app', 'File'),'name'=>Yii::t('app', 'Name')]),
                        'format' => 'raw',
                        'value'=> function ($data) {
                            return $data['is_del'] ? "<span style=\"color:#ccc\">{$data['name']}</span>" : $data['name'];
                        },
                        'headerOptions' => [
                            'style' => [
                                'min-width' => '100px',
                                'padding' => '8px',
                            ],
                        ],
                        'contentOptions' =>[
                            'style' => [
                                'padding' => '8px',
                            ],
                            'class'=>'course-name'
                        ],
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        //'header' => Yii::t('app', 'Operating'),
                        'buttons' => [
                            'view' => function ($url, $data, $key) {
                                $options = [
                                    'class' => 'btn btn-sm '.($data['is_del'] ? 'btn-danger disabled' : 'btn-success'),
                                    'title' => Yii::t('app', 'Download'),
                                    'aria-label' => Yii::t('app', 'Download'),
                                    'data-pjax' => '0',
                                ];
                                $buttonHtml = [
                                    'name' => !$data['is_del'] ? '<span class="fa fa-download"></span>'.Yii::t('app', 'Download') : '已删除',
                                    'url' => ['/webuploader/default/download', 'file_id'=>$data['id']],
                                    'options' => $options,
                                    'symbol' => '&nbsp;',
                                    'conditions' => true,
                                    'adminOptions' => true,
                                ];
                                return Html::a($buttonHtml['name'],$buttonHtml['url'],$buttonHtml['options']);
                                //return ResourceHelper::a($buttonHtml['name'], $buttonHtml['url'],$buttonHtml['options'],$buttonHtml['conditions']);
                            }
                        ],
                        'headerOptions' => [
                            'style' => [
                                'width' => '45px',
                                'padding' => '8px',
                            ],
                        ],
                        'contentOptions' =>[
                            'style' => [
                                'padding' => '4px 8px',
                            ],
                        ],
                        'template' => '{view}',
                    ],
                ],
            ]); ?>
        </div>
    </div>
    
    <div class="col-xs-12 frame">
        <div class="col-xs-12 frame-title">
            <i class="icon fa fa-commenting"></i>
            <span><?= Yii::t('app', 'Message')."（{$number}）" ?></span>
        </div>
        <div class="col-xs-12 frame-table message">
            <div id="mes-list" class="meslist">
   
            </div>
            <div class="mesform">
                <div class="col-xs-11 mesinput">
                    
                    <?php $form = ActiveForm::begin([
                        'options'=>[
                            'id' => 'form-message',
                            'class'=>'form-horizontal',
                        ],
                        'action'=>['create-message', 'activity_id'=>$model->id]
                    ]); ?>
                    
                    <?= Html::textarea('content',null,['placeholder'=>'请输入你想说的话...']);  ?>
                    
                    <?php ActiveForm::end(); ?>
                    
                </div>
                <div class="col-xs-1 mesbtn">
                    <?= Html::a(Yii::t('app', 'Message'), 'javascript:;', ['id'=>'submitsave', 'class'=>'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xs-12 frame">
        <div class="col-xs-12 frame-title">
            <i class="icon fa fa-history"></i>
            <span><?= Yii::t('app', 'Action Log') ?></span>
        </div>
        <div id="action-log" class="col-xs-12 frame-table course-make-actlog">
           
        </div>
    </div>
    
</div>

<?= $this->render('/layouts/model') ?>

<?php 
$meslist = Url::to(['course-make/mes-index','course_id'=>$model->section->chapter->block->phase->course_id,
    'activity_id'=>$model->id]);
$actlog = Url::to(['course-make/log-index','course_id'=>$model->section->chapter->block->phase->course_id,
    'relative_id'=>$model->id]);
$createmse = Url::to(['course-make/create-message', 'activity_id'=>$model->id]);
$js = 
<<<JS
        
    //加载留言列表
    $("#mes-list").load("$meslist"); 
    //加载操作记录列表
    $("#action-log").load("$actlog"); 
    //提交表单
    $("#submitsave").click(function(){
        $.post("$createmse",$('#form-message').serialize(),function(data){
            if(data['code'] == '200'){
                $("#mes-list").load("$meslist"); 
                $("#form-message textarea").val("");
            }
        });
    });
        
    //编辑活动弹出框
    $("#update-couactivity").click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
    //删除活动弹出框
    $("#delete-couactivity").click(function(){
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