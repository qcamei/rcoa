<?php

use common\components\GridViewChangeSelfColumn;
use common\models\scene\SceneSite;
use common\models\scene\searchs\SceneSiteSearch;
use kartik\widgets\Select2;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel SceneSiteSearch */
/* @var $dataProvider ActiveDataProvider */
/* @var $model SceneSite */

$this->title = Yii::t('app', '{Scene}{Administration}',[
    'Scene' => Yii::t('app', 'Scene'),
    'Administration' => Yii::t('app', 'Administration'),
]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="scene-site-index">

    <p>
        <?= Html::a(Yii::t('app', '{Create}{Scene}',[
            'Create' => Yii::t('app', 'Create'),
            'Scene' => Yii::t('app', 'Scene'),
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <h4>场地列表</h4>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            [
                'attribute' => 'id',
                'filter' => false,
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'width' => '60px',
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
                'attribute' => 'op_type',
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'width' => '100px',
                    ],
                ],
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'op_type',
                    'data' => SceneSite::$TYPES,
                    'hideSearch' => true,
                    'options' => ['placeholder' => Yii::t('app', 'All')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value' => function($data) {
                    return ($data['op_type'] == 1) ? '自营' : '合作';
                },
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'vertical-align' => 'middle',
                    ],
                ],
            ],
            [
                'attribute' => 'area',
                'label' => Yii::t('app', 'Area'),
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'width' => '100px',
                    ],
                ],
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'area',
                    'data' => $area,
                    'hideSearch' => false,
                    'options' => ['placeholder' => Yii::t('app', 'All')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'vertical-align' => 'middle',
                    ],
                ],
            ],
            [
                'attribute' => 'name',
                'label' => Yii::t('app', '{Scene}{Name}',[
                    'Scene' => Yii::t('app', 'Scene'),
                    'Name' => Yii::t('app', 'Name'),
                ]),
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'width' => '260px',
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
                'attribute' => 'content_type',
                'filter' => false,
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'width' => '200px',
                    ],
                ],
                'value' => function($data) {
                    return !empty($data['content_type']) ? $data['content_type'] : NULL;
                },
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'vertical-align' => 'middle',
                    ],
                ],
            ],
            [
                'attribute' => 'manager_id',
                'label' => Yii::t('app', 'Manager'),
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'manager_id',
                    'data' => $manager,
                    'hideSearch' => true,
                    'options' => ['placeholder' => Yii::t('app', 'All')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value' => function($data) {
                    return !empty($data['created_by']) ? $data['created_by'] : NULL;
                },
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'width' => '100px',
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
                'attribute' => 'is_publish',
                'format' => 'raw',
                'class' => GridViewChangeSelfColumn::className(),
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'is_publish',
                    'data' => ['否','是'],
                    'hideSearch' => true,
                    'options' => ['placeholder' => Yii::t('app', 'All')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'width' => '60px',
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
                'attribute' => 'sort_order',
                'label' => Yii::t('app', 'Sort'),
                'filter' => FALSE,
                'class' => GridViewChangeSelfColumn::className(),
                'plugOptions' => [
                    'type' => 'input',
                ],
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'width' => '60px',
                    ],
                ],
            ],
            [
                'header' => Yii::t('app', 'Operating'),
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'width' => '120px',
                    ],
                ],
                'format' => 'raw',
                'value' => function( $data) {
                    return Html::a(Yii::t('app', 'Edit'), ['update', 'id' => $data['id']], [
                            'class' => 'btn btn-primary btn-sm',
                            'style' => ['margin-right' => '5px']
                    ]) .
                        Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $data['id']], [
                            'class' => 'btn btn-danger btn-sm',
                            'data' => [
                               'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                               'method' => 'post'
                            ],
                    ]);
                },
                 'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'width' => '120px',
                    ],
                ],
            ],
        ],
    ]); ?>
</div>
