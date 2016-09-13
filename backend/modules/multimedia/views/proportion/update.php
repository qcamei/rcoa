<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\multimedia\MultimediaProportion */

$this->title = Yii::t('rcoa/multimedia', 'Update Multimedia Proportion').': '.$model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/multimedia', 'Multimedia Proportions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="multimedia-proportion-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
