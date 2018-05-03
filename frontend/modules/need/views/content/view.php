<?php

use common\models\need\NeedContent;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model NeedContent */

//$this->title = '';
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Need Contents'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;

?>
<div class="need-content-view">
    <div class="col-xs-12 frame">
    
        <div class="col-xs-12 title">
            <i class="glyphicon glyphicon-tasks"></i>
            <span><?= Yii::t('app', '开发内容') ?></span>
        </div>
    
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'layout' => "{items}\n{summary}\n{pager}",
            'summaryOptions' => ['class' => 'hidden'],
            'pager' => [
                'options' => ['class' => 'hidden']
            ],
            'tableOptions' => ['class' => 'table table-striped table-list table-bordered table-frame table-view'],
            'columns' => [
                [
                    //'attribute' => 'workitem_id',
                    'label' => '',
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
                            'width' => '65px',
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
                            'width' => '50px',
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
                            'width' => '70px',
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
                        return $model->plan_num . ' ' . $model->workitem->unit;
                    },
                    'headerOptions' => [
                        'class'=>[
                            //'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '90px',
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
                    //'attribute' => 'reality_num',
                    'label' => Yii::t('app', 'Reality Num'),
                    'format' => 'raw',
                    'value'=> function($model){
                        /* @var $model NeedContent */
                        return $model->reality_num . ' ' . $model->workitem->unit;
                    },
                    'headerOptions' => [
                        'class'=>[
                            //'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '90px',
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
                    //'attribute' => 'plan_cost',
                    'format' => 'raw',
                    'encodeLabel' => false,
                    'label' => Yii::t('app', 'Plan Cost'),
                    'value'=> function($model){
                        /* @var $model NeedContent */
                        return '￥' . number_format($model->price * $model->plan_num, 2, '.', '');
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '115px',
                            'padding' => '8px 4px',
                        ],
                    ],
                    'contentOptions' =>[
                        'class' => [
                            'td' => 'hidden-xs'
                        ],
                        'style' => [
                            'padding' => '8px 4px',
                            'white-space' => 'nowrap',
                        ],
                    ],
                ],
                [
                    //'attribute' => 'reality_cost',
                    'format' => 'raw',
                    'encodeLabel' => false,
                    'label' => Yii::t('app', 'Reality Cost'),
                    'value'=> function($model){
                        /* @var $model NeedContent */
                        $isAsc = $model->price * $model->reality_num > $model->price * $model->plan_num;
                        $isDesc = $model->price * $model->reality_num < $model->price * $model->plan_num;
                        return $isAsc ?  '<span class="danger">￥' . number_format($model->price * $model->reality_num, 2, '.', '') . ' ↑</span>' :
                            ($isDesc ? '<span class="primary">￥' . number_format($model->price * $model->reality_num, 2, '.', '') . ' ↓</span>' : 
                                '￥' . number_format($model->price * $model->reality_num, 2, '.', ''));
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '115px',
                            'padding' => '8px 4px',
                        ],
                    ],
                    'contentOptions' =>[
                        'class' => [
                            'td' => 'hidden-xs'
                        ],
                        'style' => [
                            'padding' => '8px 4px',
                            'white-space' => 'nowrap',
                        ],
                    ],
                ],
                [
                    //'attribute' => 'D_value',
                    'format' => 'raw',
                    'encodeLabel' => false,
                    'label' => Yii::t('app', 'D Value'),
                    'value'=> function($model){
                        /* @var $model NeedContent */
                        $dValue = $model->price * $model->reality_num - $model->price * $model->plan_num;
                        return $dValue > 0 ?  '<span class="danger"> +' . $dValue . '</span>' :
                            ($dValue < 0 ? '<span class="primary">' . $dValue . '</span>' : $dValue);
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '115px',
                            'padding' => '8px 4px',
                        ],
                    ],
                    'contentOptions' =>[
                        'class' => [
                            'td' => 'hidden-xs'
                        ],
                        'style' => [
                            'padding' => '8px 4px',
                            'white-space' => 'nowrap',
                        ],
                    ],
                ],
                [
                    //'attribute' => 'contrast',
                    'format' => 'raw',
                    'encodeLabel' => false,
                    'label' => Yii::t('app', 'Contrast'),
                    'value'=> function($model){
                        /* @var $model NeedContent */
                        return $model->plan_num > 0 && ($model->reality_num > $model->plan_num || $model->reality_num < $model->plan_num) ?  
                              '<i class="fa fa-info-circle warning"></i>' :  ($model->plan_num == 0 && $model->reality_num > $model->plan_num ? 
                                '<i class="fa fa-plus-circle primary"></i>' : '<i class="fa fa-check-circle success"></i>');
                    },
                    'headerOptions' => [
                        'class'=>[
                            'th'=>'hidden-xs',
                        ],
                        'style' => [
                            'width' => '115px',
                            'padding' => '8px 4px',
                        ],
                    ],
                    'contentOptions' =>[
                        'class' => [
                            'td' => 'hidden-xs'
                        ],
                        'style' => [
                            'padding' => '8px 4px',
                            'white-space' => 'nowrap',
                        ],
                    ],
                ],
            ],
        ]); ?>
        
        <div class="tip hidden-xs">
            注意： 
            <span><i class="fa fa-check-circle success"></i>与预计一致</span> 
            <span><i class="fa fa-info-circle warning"></i>与预计不一致</span>
            <span><i class="fa fa-plus-circle primary"></i>新增</span>
            <span><i class="danger">↑</i>成本增加</span>
            <span><i class="primary">↓</i>成本下降</span>
        </div>
        
    </div>    
</div>
