<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemContent */

$this->title = Yii::t('rcoa/worksystem', 'Update Worksystem Content') .'：'. $model->type_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Contents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update').'：'. $model->type_name;
?>
<div class="worksystem-content-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'taskTypes' => $taskTypes,
    ]) ?>

</div>
