<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\mconline\McbsActivityFile */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Mcbs Activity File',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mcbs Activity Files'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="mcbs-activity-file-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
