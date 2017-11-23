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

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mcbs Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="mcbs-actlog-view mcbs mcbs-activity default-view">

    <div class="col-xs-12 frame">
        <div class="col-xs-12 frame-title">
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
                    'value' => !empty($model->type_id) ? "<div class=\"actitype active\"><div class=\"acticon\"></div><div class=\"actname\">{$model->type->name}</div></div>" : null,
                ],
                [
                    'attribute' => 'name',
                    'value' => !empty($model->section_id) ? "【{$model->section->name}】：{$model->name}" : null,
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
                                    'url' => ['webuploader/default', 'id'=>$data['id']],
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
            <span><?= Yii::t('app', 'Message') ?></span>
        </div>
        <div class="col-xs-12 frame-table message">
            <div class="meslist">
                <ul class="time-vertical">
                    <li><b></b><div class="img-circle"></div><a href="javascript:void(0)">keso</a></li>
                    <li><b></b><div class="img-circle"></div><a href="javascript:void(0)">FlyElephant</a></li>
                    <li><b></b><div class="img-circle"></div><a href="javascript:void(0)">博客园</a></li>
                    <li><b></b><div class="img-circle"></div><a href="javascript:void(0)">创业</a></li>
                </ul>
            </div>
            <div class="mesform">
                <div class="col-xs-11 mesinput">
                    <?php $form = ActiveForm::begin([
                        'options'=>[
                            'id' => 'form-message',
                            'class'=>'form-horizontal',
                        ],
                    ]); ?>
                    
                    <?= Html::textarea('content') ?>
                    
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-xs-1 mesbtn">
                    <?= Html::a(Yii::t('app', 'Message'), 'javascript:;', ['class'=>'btn btn-primary']) ?>
                </div>
            </div>
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
$actlog = Url::to(['course-make/log-index','course_id'=>$model->section->chapter->block->phase->course_id,
    'relative_id'=>$model->id]);
$js = 
<<<JS
        
    //加载操作记录列表
    $("#action-log").load("$actlog"); 
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>