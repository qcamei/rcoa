<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\College */

$this->title = Yii::t('demand', 'Update {modelClass}: ', [
    'modelClass' => 'College',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('demand', 'Colleges'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('demand', 'Update');
?>
<div class="college-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
