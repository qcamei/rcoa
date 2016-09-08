<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TeamworkTool;
use frontend\modules\teamwork\TwAsset;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/teamwork', 'Course Manages');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container course-manage-index item-manage">
    
    <?= $this->render('search',[
        'itemType' => $itemType,
        'items' => $items,
        'itemChild' => $itemChild,
        'course' => $course,
        'team' => $team,
        'itemTypeId' => $itemTypeId,
        'itemId' => $itemId,
        'itemChildId' => $itemChildId,
        'courseId' => $courseId,
        'keyword' => $keyword,
        'time' => $time,
        'status' => $status,
        'team_id' => $team_id,
        'mark' => $mark,
    ]); ?>
    
    <div id="course-manage-index">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'summary' => false,
            'tableOptions' => ['class' => 'table table-striped table-list'],
            'columns' => [
                [
                    'label' => '',
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return '<span class="team-span">'.$model->team->name.'</span>';
                    },
                    'headerOptions' => [
                        'class'=>[
                            //'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '45px' 
                        ],
                    ],
                    'contentOptions' =>[
                        //'class'=>'hidden-xs',
                        'style' => 'white-space: nowrap;'
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/teamwork', 'Item Type'),
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return !empty($model->project->item_type_id) ? $model->project->itemType->name : 'null';
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-sm hidden-xs',
                        ],
                        'style' => [
                            'width' => '90px' 
                        ],
                    ],
                    'contentOptions' =>[
                        'class'=>'hidden-sm hidden-xs course-name',
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/teamwork', 'Item'),
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return !empty($model->project->item_id) ? $model->project->item->name : 'null';
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-sm hidden-xs',
                        ],
                        'style' => [
                            'width' => '100px' 
                        ],
                    ],
                    'contentOptions' =>[
                        'class'=>'hidden-sm hidden-xs course-name',
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/teamwork', 'Item Child'),
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return !empty($model->project->item_child_id) ? $model->project->itemChild->name : 'null';
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '250px' 
                        ],
                    ],
                    'contentOptions' =>[
                        'class' => 'course-name hidden-xs course-name',
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/teamwork', 'Course ID'),
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return '<div class="course-name">'.(!empty($model->course_id) ? $model->course->name : 'null').'</div>'.
                               Html::beginTag('div', [
                                        'class' => 'progress table-list-progress',
                                        'style' => 'height:12px;margin:2px 0;border-radius:0px;'
                                    ]).
                                    Html::beginTag('div', [
                                        'class' => 'progress-bar progress-bar',
                                        'style' => 'width:'.(int)($model->progress * 100).'%;line-height: 12px;font-size: 10px;',
                                    ]).
                                    (int)($model->progress * 100).'%'.
                                    Html::endTag('div').
                                Html::endTag('div');
                    },
                    'headerOptions' => [
                        'style' => [
                            'max-width' => '300px',
                            'min-width' => '70px',
                        ],
                    ],
                    'contentOptions' =>[
                        'style' => [
                            'max-width' => '300px', 
                            'max-width' => '70px',
                            'padding' => '2px 4px'
                        ],
                    ],

                ],
                [
                    'label' => Yii::t('rcoa/teamwork', 'Weekly'),
                    'format' => 'raw',
                    'value' => function($model){
                        /* @var $model CourseManage */
                        /* @var $twTool TeamworkTool */
                       $twTool = Yii::$app->get('twTool');
                       $week = $twTool->getWeek(date('Y-m-d', time()));
                       $result = $twTool->getWeeklyInfo($model->id, $week['start'], $week['end']);
                       return empty($result) ? '' : 
                              Html::img(['/filedata/teamwork/image/already_write_weekly.png']);
                    },
                    'headerOptions'=>[
                        'class'=>[
                            'th'=>'hidden-xs',
                        ],
                        'style'=> [
                            'width' => '15px',
                        ]
                    ],
                    'contentOptions' =>[
                        'class' => 'hidden-xs',
                    ],
                ],
                [
                    'class' => 'frontend\modules\teamwork\components\ItemActBtnCol',
                    'label' => Yii::t('rcoa', 'Operating'),
                    'contentOptions' =>[
                        'style'=> [
                            'width' => '90px',
                            'padding' =>'4px',
                        ],
                     ],
                     'headerOptions'=>[
                        'style'=> [
                            'width' => '125px',
                        ]
                    ],
                ],
            ],
        ]); ?>
    </div>
</div>

<?= $this->render('/default/_footer') ?>

<?php
    TwAsset::register($this);
?>