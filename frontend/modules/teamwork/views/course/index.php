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

<div class="container course-manage-index has-title item-manage">
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => false,
        'tableOptions' => ['class' => 'table table-striped table-list'],
        'columns' => [
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '',
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return '<span>'.$model->project->teamMember->team->name.'</span>';
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '60px' 
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-xs',
                ],
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '项目类型',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->project->itemType->name;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-sm hidden-xs',
                    ],
                    'style' => [
                        'width' => '80px' 
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-sm hidden-xs',
                ],
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '项目名称',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->project->item->name;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-sm hidden-xs',
                    ],
                    'style' => [
                        'width' => '135px' 
                    ],
                ],
                'contentOptions' =>[
                    'class'=>'hidden-sm hidden-xs',
                ],
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '子项目名称',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return $model->project->itemChild->name;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '350px' 
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'course-name hidden-xs',
                ],
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '课程名称',
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return Html::a($model->course->name, ['view','id' => $model->id], [
                        'style' => 'color:#000',
                    ]);
                },
                'headerOptions' => [
                    'style' => [
                        'max-width' => '271px',
                        'min-width' => '84px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'course-name',
                    'style' => [
                        'max-width' => '271px', 
                        'max-width' => '84px', 
                    ],
                ],
                
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemListTd',
                'label' => '进度',
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model CourseManage */
                    return Html::beginTag('div', ['class' => 'progress table-list-progress']).
                                Html::beginTag('div', [
                                    'class' => 'progress-bar progress-bar',
                                    'style' => 'width:'.(int)($model->progress * 100).'%',
                                ]).
                                (int)($model->progress * 100).'%'.
                                Html::endTag('div').
                            Html::endTag('div'); 
                },
                'headerOptions' => [
                    'style' => [
                        'width' => '74px',
                    ],
                ],
            ],
            [
                'class' => 'frontend\modules\teamwork\components\ItemActBtnCol',
                'label' => '操作',
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

<?= $this->render('/default/_footer') ?>

<?php
    TwAsset::register($this);
?>