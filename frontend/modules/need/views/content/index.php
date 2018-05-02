<?php

use common\models\need\NeedContent;
use common\models\need\searchs\NeedContentSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel NeedContentSearch */
/* @var $dataProvider ActiveDataProvider */

//$this->title = Yii::t('app', 'Need Contents');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="need-content-index">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "{items}\n{summary}\n{pager}",
        'summaryOptions' => ['class' => 'hidden'],
        'pager' => [
            'options' => ['class' => 'hidden']
        ],
        'tableOptions' => ['class' => 'table table-striped table-list table-content'],
        'columns' => [
            [
                //'attribute' => 'workitem_id',
                'label' => Yii::t('app', '{Content}{Name}', [
                    'Content' => Yii::t('app', 'Content'), 'Name' => Yii::t('app', 'Name')
                ]),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model NeedContent */
                    return $model->workitem->name;
                },
                'headerOptions' => [
                    'class'=>[
                        //'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '210px',
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => [
                        //'td' => 'hidden-xs'
                    ],
                    'style' => [
                        'padding' => '8px 4px',
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'is_new',
                'label' => Yii::t('app', 'Is New'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model NeedContent */
                    return !$model->is_new ? '新建' : '改造';
                },
                'headerOptions' => [
                    'class'=>[
                        //'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '150px',
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => [
                        //'td' => 'hidden-xs'
                    ],
                    'style' => [
                        'padding' => '8px 4px',
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'price',
                'label' => Yii::t('app', 'Price'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model NeedContent */
                    return '￥<span>' . $model->price . '</span>';
                },
                'headerOptions' => [
                    'class'=>[
                        //'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '150px',
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => [
                        //'td' => 'hidden-xs'
                    ],
                    'style' => [
                        'padding' => '8px 4px',
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'plan_num',
                'label' => Yii::t('app', 'Plan Num'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model NeedContent */
                    return Html::activeInput('number', $model, 'plan_num', [
                        'min' => 0, 'data-id' => $model->id, 'onblur' => 'updataContent($(this))'
                    ]) . '<span class="stamp">（' . $model->workitem->unit . '）</span>';
                },
                'headerOptions' => [
                    'class'=>[
                        //'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '210px',
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => [
                        //'td' => 'hidden-xs'
                    ],
                    'style' => [
                        'padding' => '8px 4px',
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                //'attribute' => 'cost',
                'format' => 'raw',
                'encodeLabel' => false,
                'label' => Yii::t('app', 'Cost') . '<span class="stamp">（单价 × 数量）</span>',
                'value'=> function($model){
                    /* @var $model NeedContent */
                    return '￥<span class="cost">' . number_format($model->price * $model->plan_num, 2, '.', '') . '</span>';
                },
                'headerOptions' => [
                    'class'=>[
                        //'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '180px',
                        'padding' => '8px 4px',
                    ],
                ],
                'contentOptions' =>[
                    'class' => [
                        //'td' => 'hidden-xs'
                    ],
                    'style' => [
                        'padding' => '8px 4px',
                        'white-space' => 'nowrap',
                    ],
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Html::a(Yii::t('app', 'Add'), ['content/create', 'need_task_id' => $need_task_id], [
                    'class' => 'btn btn-success btn-sm',
                    'onclick' => 'showModal($(this)); return false;'
                ]),
                'buttons' => [
                    'delete' => function ($url, $model){
                        return Html::a('<i class="glyphicon glyphicon-trash"></i>', ['content/delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger btn-sm',
                            'onclick' => 'deleteContent($(this)); return false;'
                        ]);
                    },
                ],
                'headerOptions' => [
                    'style' => [
                        'width' => '45px',
                        'padding' => '4px 4px;',
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'width' => '45px',
                        'padding' => '4px 4px;',
                        'text-align' => 'center',
                    ],
                ],
                'template' => '{delete}',
            ],
        ],
    ]); ?>
</div>

<div id="total-cost" class="stamp total-cost">
    总成本：￥<span><?=  number_format($totalCost, 2, '.', '') ?></span>
    <?= Html::hiddenInput('NeedTask[plan_content_cost]', $totalCost ) ?>
</div>