<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\demand\DemandTaskProduct */

$this->title = Yii::t('rcoa/demand', 'Update {modelClass}: ', [
    'modelClass' => 'Demand Task Product',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Task Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/demand', 'Update');
?>
<div class="demand-task-product-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
