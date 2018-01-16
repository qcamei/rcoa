<?php

use common\models\scene\searchs\SceneBookSearch;
use kartik\widgets\Select2;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel SceneBookSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', '{Bespeak}{List}',[
    'Bespeak' => Yii::t('app', 'Bespeak'),
    'List' => Yii::t('app', 'List'),
]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scene-book-index">

    <h1><?php //Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'site_id',
                'label' => Yii::t('app', 'Scene'),
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'min-width' => '220px',
                    ],
                ],
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'site_id',
                    'data' => $siteName,
                    'hideSearch' => false,
                    'options' => ['placeholder' => Yii::t('app', 'All')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value' => function($data) {
                    return !empty($data['name']) ? $data['name'] : NULL;
                },
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'vertical-align' => 'middle',
                    ],
                ],
            ],
            [
                'attribute' => 'date',
                'label' => Yii::t('app', 'Holiday Date'),
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'min-width' => '120px',
                    ],
                ],
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'vertical-align' => 'middle',
                    ],
                ],
            ],
            [
                'attribute' => 'time_index',
                'label' => Yii::t('app', 'Time Interval'),
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'min-width' => '80px',
                    ],
                ],
                'filter' => false,
                'value' => function($data) {
                    return (($data['time_index'] == 0) ? '上午' : ($data['time_index'] == 1) ? '下午' : '晚上');
                },
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'vertical-align' => 'middle',
                    ],
                ],
            ],
            [
                'attribute' => 'course_id',
                'label' => Yii::t('app', 'Course ID'),
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'min-width' => '180px',
                    ],
                ],
                'value' => function($data) {
                    return !empty($data['course_name']) ? $data['course_name'] : NULL;
                },
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'vertical-align' => 'middle',
                    ],
                ],
            ],
            [
                'attribute' => 'booker_id',
                'label' => Yii::t('app', 'Booker'),
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'min-width' => '100px',
                    ],
                ],
                'filter' => false,
                'value' => function($data) {
                    return !empty($data['booker']) ? $data['booker'] : NULL;
                },
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'vertical-align' => 'middle',
                    ],
                ],
            ],
            [
                'attribute' => 'status',
                'label' => Yii::t('app', 'Status'),
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'min-width' => '100px',
                    ],
                ],
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'vertical-align' => 'middle',
                    ],
                ],
            ],
            [
                'header' => Yii::t('app', 'Operating'),
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'min-width' => '120px',
                    ],
                ],
                'format' => 'raw',
                'value' => function( $data) {
                    return Html::a(Yii::t('app', 'View'), ['view', 'id' => $data['id']], [
                            'class' => 'btn btn-default btn-sm',
                            'style' => ['margin-right' => '5px']
                    ]) .
                        Html::a(Yii::t('app', 'Edit'), ['update', 'id' => $data['id']], [
                            'class' => 'btn btn-primary btn-sm',
                    ]);
                },
                 'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'min-width' => '120px',
                    ],
                ],
            ],
        ],
    ]); ?>
</div>
