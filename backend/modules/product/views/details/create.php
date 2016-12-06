<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\product\ProductDetails */

$this->title = Yii::t('rcoa/product', 'Create Product Details').':'.$model->product->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/product', 'Product Details'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-details-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'productId' => $productId,
    ]) ?>

</div>
