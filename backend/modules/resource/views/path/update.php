<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\resource\ResourcePath */

$this->title = Yii::t('rcoa/resource', 'Update {modelClass}: ', [
    'modelClass' => 'Resource Path',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/resource', 'Resource Paths'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/resource', 'Update');
?>
<div class="resource-path-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
