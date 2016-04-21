<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\resource\Resource */

$this->title = Yii::t('rcoa/resource', 'Update {modelClass}: ', [
    'modelClass' => 'Resource',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/resource', 'Resources'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/resource', 'Update');
?>
<div class="resource-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
