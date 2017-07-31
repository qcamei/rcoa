<?php

use common\models\product\ProductDetails;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model ProductDetails */

$this->title = $model->product->name;

?>
<div class="product-details-view">

    <center>
        <h1 style="font-family:微软雅黑;color:#467d19;"><?= Html::encode($model->product->name) ?></h1>
        <span style="color: #ccc">时间：<?= Html::encode(date('Y-m-d H:i', $model->created_at)) ?></span>
    </center>
    <hr>
    <?= Html::img(WEB_ROOT.$model->details)?>
    
    <p>
        <?= Html::a(Yii::t('rcoa', 'Update'), ['details/update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('rcoa', 'Delete'), ['details/delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('rcoa', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
</div>

