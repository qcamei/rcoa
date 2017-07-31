<?php

use common\models\product\ProductDetails;
use common\models\product\searchs\ProductSearch;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ProductSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/product', 'Product Details');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Create'), ['details/create', 'product_id' => $product_id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model,
        ]),
        'columns' => [
            [
                'attribute' => 'product_id',
                'value' => function($model){
                    /* @var $model ProductDetails */
                    return !empty($model->product_id) ? $model->product->name : null;
                }
            ],
            [
                'attribute' => 'created_at',
                'value' => function($model){
                    /* @var $model ProductDetails */
                    return date('Y-m-d H:i', $model->created_at);
                }
            ],
            [
                'attribute' => 'updated_at',
                'value' => function($model){
                    /* @var $model ProductDetails */
                    return date('Y-m-d H:i', $model->updated_at);
                }
            ],
            [
                'attribute' => 'details',
                'value' => function($model){
                    /* @var $model ProductDetails */
                    return $model->details;
                }
            ],
            [
                'attribute' => 'index',
                'value' => function($model){
                    /* @var $model ProductDetails */
                    return $model->index;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 
                            ['/product/details/view', 'id' => $model->id], $options);
                    },
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                        ];
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', 
                            ['/product/details/update', 'id' => $model->id], $options);
                    },
                    'delete' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'View'),
                            'aria-label' => Yii::t('yii', 'View'),
                            'data-pjax' => '0',
                            'data' => [
                                        'method' => 'post'
                                        ]
                        ];
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', 
                            ['/product/details/delete', 'id' => $model->id], $options);
                    },       
                ],
                'template' => '{view}{update}{delete}',
            ],
        ],
    ]); ?>
</div>
