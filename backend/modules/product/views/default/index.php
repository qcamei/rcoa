<?php

use common\models\product\Product;
use common\models\product\searchs\ProductSearch;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $searchModel ProductSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('rcoa/product', 'Products');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('rcoa', 'Create'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'type',
                'value' => function($model){
                    /* @var $model Product */
                    return $model->productType->name;
                }
            ],
            'name',
            [
                'attribute' => 'level',
                'value' => function($model){
                    /* @var $model Product */
                    return Product::$levelName[$model->level];
                }
            ],
            [
                'attribute' => 'unit_price',
                'value' => function($model){
                    /* @var $model Product */
                    return $model->level == Product::CLASSIFICATION ? null : $model->currency.number_format($model->unit_price, 2);
                }
            ],
            'image',
            [
                'attribute' => 'parent_id',
                'value' => function($model){
                    /* @var $model Product */
                    return !empty($model->parent_id) ? $model->parent->name : null;
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
