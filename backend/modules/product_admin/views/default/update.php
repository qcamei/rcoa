<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\product\Product */

$this->title = Yii::t('rcoa', 'Update').':'.$model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/product', 'Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'productType' => $productType,
        'classification' => $classification,
    ]) ?>

</div>
