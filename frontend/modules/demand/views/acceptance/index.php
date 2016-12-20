<?php

use common\models\demand\DemandAcceptance;
use common\models\demand\searchs\DemandAcceptanceSearch;
use frontend\modules\demand\assets\DemandAssets;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel DemandAcceptanceSearch */
/* @var $dataProvider ActiveDataProvider */

?>
<div class="demand-acceptance-index">
    <h4>验收记录</h4>
    <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model,
        ]),
        'summary' => false,
        'tableOptions' => ['class' => 'table table-striped table-list'],
        'columns' => [
            [
                'label' => Yii::t('rcoa/multimedia', 'Title'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model DemandAcceptance */
                    return $model->title;
                },
                'headerOptions' => [
                    'style' => [
                        'width' => '95px',
                        'padding' => '8px;'
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'course-name',
                ],
            ],
            [
                'label' => Yii::t('rcoa', 'Remark'),
                'format' => 'raw',
                'value'=> function($model){
                    /* @var $model DemandAcceptance */
                    return $model->remark;
                },
                'headerOptions' => [
                    'style' => [
                        'min-width' => '134px',
                        'padding' => '8px;'
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'course-name',
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Created At'), 
                'value'=> function($model){
                    /* @var $model DemandAcceptance */
                    return date('Y-m-d H:i', $model->created_at);
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '117px',
                        'padding' => '8px;'
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'hidden-xs',
                    'style' => [
                        'font-size' => '10px;',
                        'color' => '#777',
                        'padding' => '4px 8px'
                    ],
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Updated At'),
                'value'=> function($model){
                    /* @var $model DemandAcceptance */
                    return date('Y-m-d H:i', $model->updated_at);
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '117px',
                        'padding' => '8px;'
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'hidden-xs',
                    'style' => [
                        'font-size' => '10px;',
                        'color' => '#777',
                        'padding' => '4px 8px'
                    ],
                ],
            ],
            [
                'label' => Yii::t('rcoa/multimedia', 'Complete Time'),
                'value'=> function($model){
                    /* @var $model DemandAcceptance */
                    return empty($model->complete_time) ? '' : $model->complete_time;
                },
                'headerOptions' => [
                    'class'=>[
                        'th'=>'hidden-xs',
                    ],
                    'style' => [
                        'width' => '117px',
                        'padding' => '8px;'
                    ],
                ],
                'contentOptions' =>[
                    'class' => 'hidden-xs',
                    'style' => [
                        'font-size' => '10px;',
                        'color' => '#777',
                        'padding' => '4px 8px'
                    ],
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => Yii::t('rcoa', 'Operating'),
                'buttons' => [
                    'view' => function ($url, $model) {
                        /* @var $model DemandAcceptance */
                        $options = [
                            'class' => 'btn btn-default view-acceptance',
                        ];
                        $icon = $model->status == DemandAcceptance::STATUS_COMPLETE ? 'icon task-complete' : 'icon working';
                        return Html::a('<i class="'.$icon.'"></i>'.Yii::t('rcoa', 'View'), 
                            ['acceptance/view', 'id' => $model->id], $options);
                    },
                ],
                'headerOptions' => [
                    'style' => [
                        'width' => '84px',
                        'padding' => '8px;'
                    ],
                ],
                'contentOptions' =>[
                    'style' => [
                        'width' => '84px',
                        'padding' =>'4px',
                    ],
                ],
                'template' => '{view}',
            ],
        ],
    ]); ?>
    
</div>

<?php
    DemandAssets::register($this);
?>
