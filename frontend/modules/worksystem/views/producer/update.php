<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemTaskProducer */

$this->title = Yii::t('rcoa/worksystem', 'Update {modelClass}: ', [
    'modelClass' => 'Worksystem Task Producer',
]) . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Task Producers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/worksystem', 'Update');
?>
<div class="worksystem-task-producer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
