<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\expert\ExpertProject */

$this->title = Yii::t('rcoa', 'Update {modelClass}: ', [
    'modelClass' => 'Expert Project',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa', 'Expert Projects'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="expert-project-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'projects' => $projects,
    ]) ?>

</div>
