<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemTaskType */

$this->title = Yii::t('rcoa/worksystem', 'Update Worksystem Task Type') .'：'. $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Task Types'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update').'：'. $model->name;
?>
<div class="worksystem-task-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
