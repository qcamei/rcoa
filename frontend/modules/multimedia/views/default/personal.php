<?php

use common\models\multimedia\MultimediaTask;
use frontend\modules\multimedia\MultimediaAsset;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/multimedia', 'Multimedia Manages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container multimedia-task-personal multimedia-task">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-striped table-list'],
        'columns' => [
            [
                'label' => '',
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model MultimediaTask */
                    return $model->level == MultimediaTask::LEVEL_URGENT ? 
                            Html::img(['/filedata/multimedia/image/flag.png'], [
                                'width' => '16',
                                'height' => '16',
                            ]) : '';
                },
                'headerOptions' => [
                    'class'=>[
                        //'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '25px'  
                    ],
                ],
                'contentOptions' =>[
                    //'class'=>'hidden-xs',
                    'style' => 'padding: 8px 4px',
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Content Type'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model MultimediaTask */
                    return !empty($model->content_type) ? '<span class="content-type">'.$model->contentType->name.'</span>' : '';
                },
                'headerOptions' => [
                    'class'=>[
                        //'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '30px'  
                    ],
                ],
                'contentOptions' =>[
                    //'class'=>'hidden-xs',
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Item Type'),
                'value'=> function($model){
                    /* @var $model MultimediaTask */
                    return !empty($model->item_type_id) ? $model->itemType->name : 'null';
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs hidden-sm',
                    ],
                    'style' => [
                        'width' => '135px'  
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs course-name hidden-sm',
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Item'),
                'value'=> function($model){
                    /* @var $model MultimediaTask */
                    return !empty($model->item_id) ? $model->item->name : 'null';
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs hidden-sm',
                    ],
                    'style' => [
                        'width' => '135px'  
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs course-name hidden-sm',
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Item Child'),
                'value'=> function($model){
                    /* @var $model MultimediaTask */
                    return !empty($model->item_child_id) ? $model->itemChild->name : 'null';
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '145px'  
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs course-name',
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Course'),
                'value'=> function($model){
                    /* @var $model MultimediaTask */
                    return !empty($model->course_id) ? $model->course->name : 'null';
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '145px'  
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs course-name',
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Name'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model MultimediaTask */
                    return '<div class="course-name">'.($model->name).'</div>'.
                            Html::beginTag('div', [
                                'class' => 'progress table-list-progress',
                                'style' => 'height:12px;margin:2px 0;border-radius:0px;'
                            ]).Html::beginTag('div', [
                                    'class' => 'progress-bar', 
                                    'style' => 'width:'.($model->progress ).'%;line-height: 12px;font-size: 10px;',
                                ]).
                                ($model->progress).'%'.
                                Html::endTag('div').
                            Html::endTag('div');
                },
                'headerOptions' => [
                    'class'=>[
                        //'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'max-width' => '183px',
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'course-name',
                    'style' => [
                        'max-width' => '183px',
                        'padding' => '2px 8px',
                    ],
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Demand Time'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model MultimediaTask */
                    //return '<span class="complete-time">'.$model->carry_out_time.'</span>';
                    return $model->carry_out_time;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '83px'  
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs',
                    'style' => [
                        'font-size' => '10px;',
                        'padding' => '2px 8px'
                    ],
                ],
            ],
            [
                'label' => Yii::t('rcoa', 'Create By'),
                'value'=> function($model){
                    /* @var $model MultimediaTask */
                    return $model->createBy->nickname;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '83px'  
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs',
                    'style' => [
                        //'font-size' => '10px;',
                        //'padding' => '2px 8px'
                    ],
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Producer'),
                'value'=> function($model){
                    /* @var $model MultimediaTask */
                    $producer = [];
                    foreach ($model->producers as $value) 
                        $producer[] = $value->producer->u->nickname;
                    return !empty($producer) ? implode(',', $producer) : '';
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '95px'  
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs course-name',
                    'style' => [
                        //'font-size' => '10px;',
                        //'padding' => '2px 8px'
                    ],
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('rcoa', 'Operating'),
                'buttons' => [
                    'view' => function ($url, $model) {
                        /* @var $model MultimediaTask */
                        $options = [
                            'class' => $model->getIsStatusAssign() ? 'btn btn-primary btn-sm' : ($model->getIsStatusWaitCheck() ? 'btn btn-info btn-sm'
                                    : ($model->getIsStatusTostart() ? 'btn btn-success btn-sm'  
                                    : ($model->getIsStatusCompleted() ? 'btn btn-danger btn-sm' : 'btn btn-default btn-sm'))),
                        ];
                        return Html::a(MultimediaTask::$statusNmae[$model->status], 
                            ['view', 'id' => $model->id], $options);
                    },
                ],
                'headerOptions' => [
                    'style' => [
                        'width' => '75px'  
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'width' => '75px',
                        'padding' =>'4px',
                    ],
                ],
                'template' => '{view}',
            ],
        ],
    ]); ?>
</div>

<?= $this->render('_footer'); ?>

<?php
$js = 
<<<JS
    $('#submit').click(function()
    {
        $('#item-manage-form').submit();
    });
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<?php
    MultimediaAsset::register($this);
?>