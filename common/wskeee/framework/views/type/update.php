<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\ItemType */

$this->title = Yii::t('rcoa/framework', 'Update Item Type') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/framework', 'Item Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="item-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
