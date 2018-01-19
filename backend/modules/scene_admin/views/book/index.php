<?php

use common\models\scene\SceneBook;
use common\models\scene\searchs\SceneBookSearch;
use kartik\daterange\DateRangePicker;
use kartik\widgets\Select2;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
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
                'value' => function($model) {
                    /* @var $model SceneBook */
                    return !empty($model->site_id) ? $model->sceneSite->name : NULL;
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
                'filter' => true,
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'min-width' => '231px',
                    ],
                ],
                'filter' => DateRangePicker::widget([    // 日期组件
                    'model' => $searchModel,
                    'name' => 'date',
                    'value' => ArrayHelper::getValue($params, 'date'),
                    'hideInput' => true,
                    'convertFormat'=>true,
                    'pluginOptions' => [
                        'locale'=>['format' => 'Y-m-d'],
                        'allowClear' => true,
                    ],
                ]),
                'value' => function($model) {
                    /* @var $model SceneBook */
                    return date('Y/m/d ', strtotime($model->date)) . Yii::t('rcoa', 'Week ' . date('D', strtotime($model->date)));
                },
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'vertical-align' => 'middle',
                    ],
                ],
            ],
            [
                'attribute' => 'timeIndexName',
                'label' => Yii::t('app', 'Time Interval'),
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'min-width' => '80px',
                    ],
                ],
                'filter' => false,
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
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'course_id',
                    'data' => $courseName,
                    'hideSearch' => false,
                    'options' => ['placeholder' => Yii::t('app', 'All')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value' => function($model) {
                    /* @var $model SceneBook */
                    return !empty($model->course_id) ? $model->course->name : NULL;
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
                'value' => function($model) {
                    /* @var $model SceneBook */
                    return !empty($model->booker_id) ? $model->booker->nickname : NULL;
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
                'label' => Yii::t('app', '{Is}{Cancel}',[
                    'Is' => Yii::t('app', 'Is'),
                    'Cancel' => Yii::t('app', 'Cancel'),
                ]),
                'format' => 'raw',
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'min-width' => '100px',
                    ],
                ],
                'filter' => SceneBook::$statusMap,
                'value' => function($model) {
                    /* @var $model SceneBook */
                    return Html::a(Yii::t('app', '{Cancel}{Bespeak}',[
                            'Cancel' => Yii::t('app', 'Cancel'),
                            'Bespeak' => Yii::t('app', 'Bespeak'),
                        ]), ['cancel', 'id' => $model->id], [
                            'class' => ($model->getIsCancel()) ? 'btn btn-danger btn-sm' : 'btn btn-danger btn-sm disabled',
                            'data' => [
                                   'confirm' => Yii::t('app', 'Are you sure you want to cancel the reservation? After the cancellation will not be restored!'),
                                   'method' => 'post'
                                ],
                    ]);
                },
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
                'value' => function($model) {
                    /* @var $model SceneBook */
                    return Html::a(Yii::t('app', 'View'), ['view', 'id' => $model->id], [
                            'class' => 'btn btn-default btn-sm',
                            'style' => ['margin-right' => '5px']
                    ]) .
                        Html::a(Yii::t('app', 'Edit'), ['update', 'id' => $model->id], [
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
