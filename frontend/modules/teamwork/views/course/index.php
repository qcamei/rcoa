<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TwAsset;
use frontend\modules\teamwork\utils\TeamworkTool;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\LinkPager;

/* @var $this View */
/* @var $twTool TeamworkTool */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/teamwork', 'Course Manages');
$this->params['breadcrumbs'][] = $this->title;

$courseIds = ArrayHelper::getColumn($dataProvider->allModels, 'id');
$weekly = ArrayHelper::map($twTool->getWeeklyInfo($courseIds, $twTool->getWeek(date('Y-m-d', time()))), 'course_id', 'create_time');
foreach ($dataProvider->allModels as $model)
    $model->isExistWeekly = isset($weekly[$model->id]);
?>

<div class="container course-manage-index item-manage">
    
    <?= $this->render('_search_detai',[
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
            'layout' => "{items}\n{summary}\n{pager}",
            'summaryOptions' => [
                'class' => 'summary',
                //'style' => 'float: left'
            ],
            'pager' => [
                'options' => [
                    'class' => 'hidden',
                    //'style' => 'float: right; margin: 0px;'
                ]
            ],
            'tableOptions' => ['class' => 'table table-striped table-list'],
            'columns' => [
                [
                    'label' => '',
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return $model->mode == CourseManage::MODE_NEWBUILT ?
                                Html::img(['/filedata/teamwork/image/mode_newbuilt.png']) : 
                                Html::img(['/filedata/teamwork/image/mode_reform.png']);
                    },
                    'headerOptions' => [
                        'class'=>[
                            //'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '16px',
                            'padding-right' => '4px'
                        ],
                    ],
                    'contentOptions' =>[
                        //'class'=>'hidden-xs',
                        'style' => 'white-space: nowrap; padding-right:4px'
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/teamwork', 'Statistics-Team'),
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return '<span class="team-span">'.(!empty($model->team_id) ? $model->team->name : null).'</span>';
                    },
                    'headerOptions' => [
                        'class'=>[
                            //'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '45px',
                            'padding-left' => '4px;',
                            'padding-right' => '4px;',
                        ],
                    ],
                    'contentOptions' =>[
                        //'class'=>'hidden-xs',
                        'style' => 'white-space: nowrap;padding-left:4px;padding-right:4px;'
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/teamwork', 'Item Type'),
                    'value'=> function($model){
                        /* @var $model CourseManage */
                        return !empty($model->project->item_type_id) ? $model->project->itemType->name : null;
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
                        return !empty($model->project->item_id) ? $model->project->item->name : null;
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
                        return !empty($model->project->item_child_id) ? $model->project->itemChild->name : null;
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
                        return '<div class="course-name">'.(!empty($model->course_id) ? $model->course->name : null).'</div>';
                               /*Html::beginTag('div', [
                                        'class' => 'progress table-list-progress',
                                        'style' => 'height:12px;margin:2px 0;border-radius:0px;'
                                    ]).
                                    Html::beginTag('div', [
                                        'class' => 'progress-bar progress-bar',
                                        'style' => 'width:'.(int)($model->progress * 100).'%;line-height: 12px;font-size: 10px;',
                                    ]).
                                    (int)($model->progress * 100).'%'.
                                    Html::endTag('div').
                                Html::endTag('div');*/
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
                    'value' => function ($model){
                        return $model->isExistWeekly ? Html::img(['/filedata/teamwork/image/already_write_weekly.png']) : '';
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
        
        <?= LinkPager::widget([  
            'pagination' => new Pagination([
                //'pageSize' => 20,
                'totalCount' => $count,  
            ]),  
        ]) ?> 
    </div>
</div>

<?= $this->render('/default/_footer',[
    'twTool' => $twTool
]) ?>

<?php
    TwAsset::register($this);
?>