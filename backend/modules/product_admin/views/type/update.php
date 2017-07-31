<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\product\ProductType */

$this->title = Yii::t('rcoa/product', 'Update Product Type').':'.$model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/product', 'Product Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="product-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
