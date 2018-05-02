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
//DemandTask::$productTotal = $productTotal;
?>

<div class="container demand-task-index demand-task">
   
    <?=  $this->render('_search',[
        'params' => $param,
        //条件
        'itemTypes' => $itemType,
        'items' => $items,
        'itemChilds' => $itemChild,
        'courses' => $course,
        'developTeams' => $developTeams,
        'createBys' => $createBys,
        'undertakers' => $undertakers,
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
                    'value'=> function($data){
                        return $data['mode'] == DemandTask::MODE_NEWBUILT ?
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
                    'value'=> function($data){
                        return !empty($data['item_type_name']) ? $data['item_type_name'] : null;
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs hidden-sm hidden-md',
                        ],
                        'style' => [
                            'width' => '125px',
                            'padding' => '8px'
                        ],
                    ],
                    'contentOptions' =>[
                        'class'=>'hidden-xs course-name list-td hidden-sm hidden-md',
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/demand', 'Item'),
                    'value'=> function($data){
                        return !empty($data['item_name']) ? $data['item_name'] : null;
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs hidden-sm',
                        ],
                        'style' => [
                            'width' => '105px',
                            'padding' => '8px'
                        ],
                    ],
                    'contentOptions' =>[
                        'class'=>'hidden-xs course-name list-td hidden-sm',
                    ],
                ],
                [
                    'label' => Yii::t('rcoa/demand', 'Item Child'),
                    'value'=> function($data){
                        return !empty($data['item_child_name']) ? $data['item_child_name'] : null;
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs hidden-sm',
                        ],
                        'style' => [
                            'width' => '155px',
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
                    'value'=> function($data){
                        return !empty($data['item_course_name']) ? '<div class="course-name">'.($data['item_course_name']).'</div>'.
                                Html::beginTag('div', [
                                    'class' => 'progress table-list-progress',
                                    'style' => 'height:12px;margin:2px 0;border-radius:0px;'
                                ]).Html::beginTag('div', [
                                        'class' => 'progress-bar', 
                                        'style' => "width:{$data['progress']}%;line-height: 12px;font-size: 10px;",
                                    ])."{$data['progress']}%".
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
                    'value'=> function($data){
                        return $data['plan_check_harvest_time'];
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
                    'value'=> function($data){
                        return !empty($data['create_by']) ? $data['create_by'] : null;
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
                    'label' => Yii::t('rcoa/demand', 'Undertaker'),
                    'value'=> function($data){
                        return !empty($data['undertaker']) ? $data['undertaker'] : null;
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
                    'label' => Yii::t('rcoa/demand', 'Develop Team'),
                    'format' => 'raw',
                    'value'=> function($data){
                        return !empty($data['develop_team_name']) ? "<span class=\"team-span\">{$data['develop_team_name']}</span>" : null;
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
                    'label' => '预算成本',
                    'format' => 'raw',
                    'value'=> function($data){
                        if(!empty($data['budget_cost'])){
                            $budgetCost = $data['budget_cost'] + $data['budget_cost'] * $data['bonus_proportion'];
                            $totalBudgetCost = number_format(($budgetCost + $data['external_budget_cost']) / 10000, 2);
                            return "<span class=\"total-price\">￥{$totalBudgetCost}</span>万";
                        }else{
                            return null;
                        }
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '98px',
                            'padding' => '8px'
                        ],
                    ],
                    'contentOptions' =>[
                        'class'=>'list-td hidden-xs',
                    ],
                ],
                [
                    'label' => '实际成本',
                    'format' => 'raw',
                    'value'=> function($data){
                        if(!empty($data['cost'])){
                            $realityCost = $data['cost'] + $data['cost'] * $data['bonus_proportion'];
                            $totalRealityCost = number_format(($realityCost + $data['external_reality_cost']) / 10000, 2);
                            return "<span class=\"total-price\">￥{$totalRealityCost}</span>万";
                        }else{
                            return null;
                        }
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '98px',
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
                        'view' => function ($url, $data) use($operation) {
                            $options = [
                                'class' => isset($operation[Yii::$app->user->id][$data['id']]) ? 
                                    ($operation[Yii::$app->user->id][$data['id']] == DemandTask::STATUS_COMPLETED ?  'btn btn-success btn-sm' : 
                                    ($operation[Yii::$app->user->id][$data['id']] == DemandTask::STATUS_CANCEL ? 'btn btn-danger btn-sm' :
                                    ($operation[Yii::$app->user->id][$data['id']] == DemandTask::STATUS_DEVELOPING ? 'btn btn-default btn-sm' :
                                    ($operation[Yii::$app->user->id][$data['id']] ? 'btn btn-primary btn-sm' : 'btn btn-default btn-sm')))) : 
                                    ($data['status'] == DemandTask::STATUS_CANCEL ? 'btn btn-danger btn-sm' : 
                                    ($data['status'] == DemandTask::STATUS_COMPLETED ? 'btn btn-success btn-sm' : 'btn btn-default btn-sm')),
                                'style' => 'width: 55px;'
                            ];
                            return Html::a(DemandTask::$statusNmae[$data['status']], [
                                'view', 'id' => $data['id']], $options);
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
        
        <?php
            $page = !isset($param['page']) ? 1 :$param['page'];
            $pageCount = ceil($totalCount/20);
            if($pageCount > 0)
                echo "<div class=\"summary\">第<b>".(($page*20-20)+1)."</b>-<b>".($page!=$pageCount?$page*20:$totalCount)."</b>条，总共<b>{$totalCount}</b>条数据.</div>";
        ?>

        <?= LinkPager::widget([  
            'pagination' => new Pagination([
                'totalCount' => $totalCount,  
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
    //$this->registerJs($js,  View::POS_READY);
?>

<?php
    DemandAssets::register($this);
?>

