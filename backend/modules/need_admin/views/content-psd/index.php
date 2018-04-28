<?php

use common\components\GridViewChangeSelfColumn;
use common\models\need\NeedContentPsd;
use kartik\widgets\Select2;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel common\models\need\NeedContentPsdSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', '{Content}{Template}',[
    'Content' => Yii::t('app', 'Content'),
    'Template' => Yii::t('app', 'Template'),
]);
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="need-content-psd-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{items}\n{summary}\n{pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'workitem_type_id',
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'workitem_type_id',
                    'data' => $contentType,
                    'hideSearch' => false,
                    'options' => ['placeholder' => Yii::t('app', 'All')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value' => function ($data) {
                    return $data['type_name'];
                },
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
            ],
            [
                'attribute' => 'workitem_id',
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'workitem_id',
                    'data' => $workitem,
                    'hideSearch' => false,
                    'options' => ['placeholder' => Yii::t('app', 'All')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'value' => function ($data) {
                    return $data['workitem_name'];
                },
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
            ],
            [
                'attribute' => 'price_new',
                'filter' => false,
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
                'value' => function ($data) {
                    return '￥' . $data['price_new'];
                },
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
            ],
            [
                'attribute' => 'price_remould',
                'filter' => false,
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
                'value' => function ($data) {
                    return '￥' . $data['price_remould'];
                },
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
            ],
            [
                'label' => Yii::t('app', 'Employer'),
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
                'value' => function ($data) {
                    return $data['unit'];
                },
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
            ],
            [
                'attribute' => 'is_del',
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'width' => '80px'
                    ],
                ],
                'format' => 'raw',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'is_del',
                    'data' => NeedContentPsd::$SHOW_TYPES,
                    'hideSearch' => true,
                    'options' => ['placeholder' => Yii::t('app', 'All')],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
                'class' => GridViewChangeSelfColumn::class,
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
            ],
            [
                'attribute' => 'sort_order',
                'headerOptions' => [
                    'style' => [
                        'text-align' => 'center',
                        'width' => '65px'
                    ],
                ],
                'filter' => false,
                'class' => GridViewChangeSelfColumn::class,
                'plugOptions' => [
                    'type' => 'input',
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'contentOptions' => [
                    'style' => [
                        'text-align' => 'center',
                    ],
                ],
            ],
        ],
    ]); ?>
</div>
