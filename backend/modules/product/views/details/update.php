<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\product\ProductDetails */

$this->title = Yii::t('rcoa/product', 'Update Product Details').':'.$model->product->name;
$this->params['breadcrumbs'][] = ['label' => $model->product->name, 'url' => ['default/view', 'id' => $model->product_id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="product-details-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'productId' => $model->product_id,
    ]) ?>

</div>
