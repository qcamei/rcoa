<?php

use common\models\product\Product;
use yii\data\ArrayDataProvider;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model Product */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/product', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'type',
                'value' => $model->productType->name,
            ],
            'name',
            [
                'attribute' => 'level',
                'value' => Product::$levelName[$model->level],
            ],
            [
                'attribute' => 'unit_price',
                'value' => $model->level == Product::CLASSIFICATION ? null : $model->currency.number_format($model->unit_price, 2),
            ],
            'image',
            [
                'attribute' => 'created_at',
                'value' => date('Y-m-d H:i', $model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('Y-m-d H:i', $model->updated_at),
            ],
            'des:ntext',
        ],
    ]) ?>
    
    <?php 
        if($model->level == Product::CLASSIFICATION){
            echo '<p>'.Html::a(Yii::t('rcoa', 'Create'), 
                ['create','parent_id'=>$model->id], 
                ['class' => 'btn btn-success'/*, 'data' => ['method' => 'post']*/]).'</p>';

            echo GridView::widget([
                'dataProvider' => new ArrayDataProvider([
                    'allModels' => $model->products,
                ]),
                'columns' => [
                    //['class' => 'yii\grid\SerialColumn'],

                   [
                        'label' => Yii::t('rcoa/product', 'Type'),
                        'value' => function($model){
                            /* @var $model Product */
                            return $model->productType->name;
                        }
                    ],
                   [
                        'label' => Yii::t('rcoa', 'Name'),
                        'value' => function($model){
                            /* @var $model Product */
                            return $model->name;
                        }
                    ],
                    [
                        'label' => Yii::t('rcoa', 'Level'),
                        'value' => function($model){
                            /* @var $model Product */
                            return Product::$levelName[$model->level];
                        }
                    ],
                    [
                        'label' => Yii::t('rcoa/product', 'Unit Price'),
                        'value' => function($model){
                            /* @var $model Product */
                            return $model->level == Product::CLASSIFICATION ? null : $model->currency.$model->unit_price;
                        }
                    ],
                    [
                        'label' => Yii::t('rcoa/product', 'Image'),
                        'value' => function($model){
                            /* @var $model Product */
                            return $model->image;
                        }
                    ],
                    [
                        'label' => Yii::t('rcoa', 'Parent ID'),
                        'value' => function($model){
                            /* @var $model Product */
                            return !empty($model->parent_id) ? $model->parent->name : null;
                        }
                    ],
                    [
                        'class' => ActionColumn::className(),
                        'template' => '{view}{update}{delete}',
                        'buttons' => [
                            'view' => function ($url, $model, $key) {
                                $options = [
                                    'title' => Yii::t('yii', 'View'),
                                    'aria-label' => Yii::t('yii', 'View'),
                                    'data-pjax' => '0',
                                ];
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['view', 'id' => $model->id], $options);
                            },
                            'update' => function ($url, $model, $key) {
                                $options = [
                                    'title' => Yii::t('yii', 'Update'),
                                    'aria-label' => Yii::t('yii', 'Update'),
                                    'data-pjax' => '0',
                                ];
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span>', ['update', 'id' => $model->id], $options);
                            },
                            'delete' => function ($url, $model, $key) {
                                $options = [
                                    'title' => Yii::t('yii', 'Delete'),
                                    'aria-label' => Yii::t('yii', 'Delete'),
                                    'data-pjax' => '0',
                                    'data-method' => 'post'
                                ];
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], $options);
                            }
                        ]
                    ],
                ],
            ]);
        } else {
            if(!empty($model->productDetail->product_id))
                echo $this->render('/details/view', ['model' => $model->productDetail]);
            else{
                echo '<p>'.Html::a(Yii::t('rcoa/product', 'Create Product Details'), 
                    ['details/create', 'product_id'=>$model->id], 
                    ['class' => 'btn btn-success'/*, 'data' => ['method' => 'post']*/]).'</p>';
                echo '没有找到数据。';
            }
        }
    ?>

</div>
