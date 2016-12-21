<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\demand\DemandTaskProduct */

$this->title = Yii::t('rcoa/demand', 'Create Demand Task Product');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Task Products'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demand-task-product-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
