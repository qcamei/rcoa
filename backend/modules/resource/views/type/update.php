<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\resource\ResourceType */

$this->title = Yii::t('rcoa/resource', 'Update {modelClass}: ', [
    'modelClass' => 'Resource Type',
]) . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/resource', 'Resource Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/resource', 'Update');
?>
<div class="resource-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
