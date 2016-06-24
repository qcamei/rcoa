<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\PhaseLink */

$this->title = Yii::t('rcoa/framework', 'Update {modelClass}: ', [
    'modelClass' => 'Phase Link',
]) . ' ' . $model->phases_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/framework', 'Phase Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->phases_id, 'url' => ['view', 'phases_id' => $model->phases_id, 'link_id' => $model->link_id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/framework', 'Update');
?>
<div class="phase-link-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
