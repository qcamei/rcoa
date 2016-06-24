<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\Phase */

$this->title = Yii::t('rcoa/framework', 'Update {modelClass}: ', [
    'modelClass' => 'Phase',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/framework', 'Phases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/framework', 'Update');
?>
<div class="phase-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
