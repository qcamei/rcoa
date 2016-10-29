<?php

use common\models\multimedia\MultimediaTask;
use frontend\modules\multimedia\MultimediaAsset;
use frontend\modules\multimedia\utils\MultimediaTool;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model MultimediaTask */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/multimedia', 'Multimedia Manages');
$this->params['breadcrumbs'][] = $this->title;

$taskIds = ArrayHelper::getColumn($dataProvider->allModels, 'id');
$taskStatus = ArrayHelper::map($dataProvider->allModels, 'id', 'status');
MultimediaTask::$operation = $multimedia->getIsBelongToOwnOperate($taskIds, $taskStatus);
?>
<div class="container multimedia-task-list multimedia-task">
    
    <?= $this->render('_search_detai',[
        'team' => $team,
        'contentType' => $contentType,
        'itemType' => $itemType,
        'items' => $items,
        'itemChild' => $itemChild,
        'course' => $course,
        'createBy' => $createBy,
        'producers' => $producers,
        'create_team' => $create_team,
        'make_team' => $make_team,
        'content_type' => $content_type,
        'item_type_id' => $item_type_id,
        'item_id' => $item_id,
        'item_child_id' => $item_child_id,
        'course_id' => $course_id,
        'create_by' => $create_by,
        'producer' => $producer,
        'status' => $status,
        'time' => $time,
        'keyword' => $keyword,
        'mark' => $mark,
    ]); ?>

    <div id="multimedia-task-list">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{summary}\n{pager}",
        'summaryOptions' => [
            'class' => 'summary',
            //'style' => 'float: left'
        ],
        'pager' => [
            'options' => [
                'class' => 'pagination',
                //'style' => 'float: right; margin: 0px;'
            ]
        ],
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
                    'style' => [
                        'width' => '20px',
                        'padding' => '8px 2px',
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'padding' => '8px 2px',
                    ]
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Create Brace'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model MultimediaTask */
                    return !empty($model->make_team) ? 
                           '<span class="team-span" style="float: left;">'.$model->createTeam->name.'</span>'.
                           Html::img(['/filedata/multimedia/image/brace.png'], [
                               'width' => '15', 
                               'height' => '15', 
                               'style' => 'float: left; margin: 3px 3px;'
                           ]).'<span class="team-span" style="float: left;">'.$model->makeTeam->name.'</span>'
                           : (!empty($model->create_team) ? '<span class="team-span">'.$model->createTeam->name.'</span>' : '');
                },
                'headerOptions' => [
                    'style' => [
                        'width' => '105px',
                        'padding' => '8px 2px',
                        'text-align' => 'center;'
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'padding' => '8px 2px'
                    ],
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
                    'style' => [
                        'width' => '34px',
                        'padding' => '8px 2px;'  
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'padding' => '8px 2px;'  
                    ],
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Item Type'),
                'value'=> function($model){
                    /* @var $model MultimediaTask */
                    return !empty($model->item_type_id) ? $model->itemType->name : '';
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs hidden-sm hidden-md',
                    ],
                    'style' => [
                        'width' => '110px',
                        'padding' => '8px'
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs course-name list-td hidden-sm hidden-md',
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Item'),
                'value'=> function($model){
                    /* @var $model MultimediaTask */
                    return !empty($model->item_id) ? $model->item->name : '';
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs hidden-sm hidden-md',
                    ],
                    'style' => [
                        'width' => '100px',
                        'padding' => '8px'
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs course-name list-td hidden-sm hidden-md',
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Item Child'),
                'value'=> function($model){
                    /* @var $model MultimediaTask */
                    return !empty($model->item_child_id) ? $model->itemChild->name : '';
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs hidden-sm',
                    ],
                    'style' => [
                        'width' => '135px',
                        'padding' => '8px'
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs course-name list-td hidden-sm',
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Course'),
                'value'=> function($model){
                    /* @var $model MultimediaTask */
                    return !empty($model->course_id) ? $model->course->name : '';
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs hidden-sm',
                    ],
                    'style' => [
                        'width' => '150px',
                        'padding' => '8px'
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs course-name list-td hidden-sm',
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
                    'style' => [
                        'min-width' => '84px',
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'list-td',
                    'style' => [
                        'padding' => '2px 4px',
                    ],
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Demand Time'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model MultimediaTask */
                    return $model->plan_end_time;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '85px',
                        'padding' => '8px'
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
                    return !empty($model->create_by) ? $model->createBy->nickname : '';
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '58px',
                        'padding' => '8px'
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'list-td hidden-xs',
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Producer'),
                'value'=> function($model){
                    $producer = ArrayHelper::map($model->teamMember, 'u_id', 'user.nickname');
                    return !empty($producer) ? implode(',', $producer) : '';
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '75px',
                        'padding' => '8px'
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'list-td hidden-xs course-name',
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('rcoa', 'Operating'),
                'buttons' => [
                    'view' => function ($url, $model) {
                        /* @var $model MultimediaTask */
                        $options = [
                            'class' => $model->getIsStatusCompleted()? 
                                    'btn btn-success btn-sm' : (MultimediaTask::$operation[$model->id] ? 
                                    'btn btn-primary btn-sm' : 'btn btn-default btn-sm'),
                            'style' => 'width: 55px;'
                        ];
                        return Html::a($model->getStatusName(), [
                            'view', 'id' => $model->id], $options);
                    },
                ],
                'headerOptions' => [
                    'style' => [
                        'width' => '60px',
                        'padding' => '8px 2px;',
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'width' => '60px',
                        'padding' => '4px 2px;',
                    ],
                ],
                'template' => '{view}',
            ],
        ],
    ]); ?>
    </div>
</div>

<?= $this->render('_footer', [
    'multimedia' => $multimedia,
]); ?>

<?php
    MultimediaAsset::register($this);
?>