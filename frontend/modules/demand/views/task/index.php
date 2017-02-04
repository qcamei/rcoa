<?php

use common\models\demand\DemandTask;
use common\models\demand\searchs\DemandTaskSearch;
use frontend\modules\demand\assets\DemandAssets;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\LinkPager;

/* @var $this View */
/* @var $searchModel DemandTaskSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/demand', 'Demand Tasks');
$this->params['breadcrumbs'][] = $this->title;

DemandTask::$operation = $operation;
DemandTask::$productTotal = $productTotal;
?>

<div class="container demand-task-index demand-task">
   
    <?=  $this->render('_search_detai',[
        'itemType' => $itemType,
        'items' => $items,
        'itemChild' => $itemChild,
        'course' => $course,
        'team' => $team,
        'createBy' => $createBys,
        'undertakePerson' => $undertakePersons,

        'item_type_id' => $itemTypeId,
        'item_id' => $itemId,
        'item_child_id' => $itemChildId,
        'course_id' => $courseId,
        'team_id' => $teamId,
        'create_by' => $createBy,
        'undertake_person' => $undertakePerson,
        'status' => $status,
        'time' => $time,
        'keyword' => $keyword,
        'mark' => $mark,
    ]); ?>

    
    <div id="demand-task-index">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
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
            'tableOptions' => ['class' => 'table table-striped table-list'],
            'columns' => [
                [
                    'label' => '',
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model DemandTask */
                        return $model->mode == DemandTask::MODE_NEWBUILT ?
                                Html::img(['/filedata/demand/image/mode_newbuilt.png']) : 
                                Html::img(['/filedata/demand/image/mode_reform.png']);
                    },
                    'headerOptions' => [
                        'class'=>[
                            //'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '16px',
                            'padding-left' => '4px',
                            'padding-right' => '4px'
                        ],
                    ],
                    'contentOptions' =>[
                        //'class'=>'hidden-xs',
                        'style' => 'white-space: nowrap; padding-left:4px;padding-right:4px'
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/demand', 'Item Type'),
                    'value'=> function($model){
                        /* @var $model DemandTask */
                        return !empty($model->item_type_id) ? $model->itemType->name : null;
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs hidden-sm hidden-md',
                        ],
                        'style' => [
                            'width' => '145px',
                            'padding' => '8px'
                        ],
                    ],
                    'contentOptions' =>[
                        'class'=>'hidden-xs course-name list-td hidden-sm hidden-md',
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/demand', 'Item'),
                    'value'=> function($model){
                        /* @var $model DemandTask */
                        return !empty($model->item_id) ? $model->item->name : null;
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
                    'label' => Yii::t('rcoa/demand', 'Item Child'),
                    'value'=> function($model){
                        /* @var $model DemandTask */
                        return !empty($model->item_child_id) ? $model->itemChild->name : null;
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs hidden-sm',
                        ],
                        'style' => [
                            'width' => '195px',
                            'padding' => '8px'
                        ],
                    ],
                    'contentOptions' =>[
                        'class'=>'hidden-xs course-name list-td hidden-sm',
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/demand', 'Courses'),
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model DemandTask */
                        return !empty($model->course_id) ? '<div class="course-name">'.($model->course->name).'</div>'.
                                Html::beginTag('div', [
                                    'class' => 'progress table-list-progress',
                                    'style' => 'height:12px;margin:2px 0;border-radius:0px;'
                                ]).Html::beginTag('div', [
                                        'class' => 'progress-bar', 
                                        'style' => 'width:'.$model->progress.'%;line-height: 12px;font-size: 10px;',
                                    ]).$model->progress.'%'.
                                    Html::endTag('div').
                                Html::endTag('div') : null;
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
                            'padding' => '2px 8px',
                        ],
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/demand', 'Check Harvest Time'),
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model DemandTask */
                        return $model->plan_check_harvest_time;
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
                    'label' => Yii::t('rcoa/demand', 'Create By'),
                    'value'=> function($model){
                        /* @var $model DemandTask */
                        return !empty($model->create_by) ? $model->createBy->nickname : null;
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '65px',
                            'padding' => '8px'
                        ],
                    ],
                    'contentOptions' =>[
                        'class'=>'list-td hidden-xs',
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/demand', 'Undertake Person'),
                    'value'=> function($model){
                        /* @var $model DemandTask */
                        return !empty($model->undertake_person) ? $model->undertakePerson->nickname : null;
                    },
                    'headerOptions' => [
                        'class'=>[
                            //'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '65px',
                            'padding' => '8px'
                        ],
                    ],
                    'contentOptions' =>[
                        'class'=>'list-td',
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/demand', 'Team'),
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model DemandTask */
                        return !empty($model->team_id) ? '<span class="team-span">'.$model->team->name.'</span>' : null;
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '68px',
                            'padding' => '8px'
                        ],
                    ],
                    'contentOptions' =>[
                        'class'=>'list-td hidden-xs',
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/demand', 'Product Total'),
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model DemandTask */
                        if(isset(DemandTask::$productTotal[$model->id])){
                            return '<span class="total-price">￥'.DemandTask::$productTotal[$model->id].'</span>';
                        }else{
                            return null;
                        }
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '78px',
                            'padding' => '8px'
                        ],
                    ],
                    'contentOptions' =>[
                        'class'=>'list-td hidden-xs',
                    ],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => Yii::t('rcoa', 'Operating'),
                    'buttons' => [
                        'view' => function ($url, $model) {
                            /* @var $model DemandTask */
                            $options = [
                                'class' => $model->getIsStatusCompleted() ? 
                                        'btn btn-success btn-sm' : ($model->getIsStatusDeveloping() ? 'btn btn-default btn-sm' : 
                                            (!empty(DemandTask::$operation[$model->id]) ? 
                                                'btn btn-primary btn-sm' : 'btn btn-default btn-sm')),
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
        
        <div class="summary">总共<b><?= $count ?></b>条数据</div>
        
        <?= LinkPager::widget([  
            'pagination' => new Pagination([
                'totalCount' => $count,  
            ]),  
        ]) ?> 
        
    </div>
</div>

<?php
$js = 
<<<JS
    /** 格式化所有价钱 */
    format(".total-price");
    /** 价格格式化 */
    function format(obj){
        $(obj).each(function(){
            var con = trim($(this).html()).split('￥');
            $(this).html('<span class="big" style="font-size: 14px;">' + $(this).html().split('.')[0] + '.</span><span class="small">' + $(this).html().split('.')[1] + '</span>');
        });
    }
    /** 正则匹配 */
    function trim(str){ 
　　     return str.replace(/(^\s*)|(\s*$)/g, "");
　　}
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    DemandAssets::register($this);
?>

