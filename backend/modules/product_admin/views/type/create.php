<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\product\ProductType */

$this->title = Yii::t('rcoa/product', 'Create Product Type');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/product', 'Product Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
