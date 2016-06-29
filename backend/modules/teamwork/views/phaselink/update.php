<?php

use common\models\teamwork\PhaseLink;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model PhaseLink */

$this->title = Yii::t('rcoa/teamwork', 'Update {modelClass}: ', [
    'modelClass' => 'Phase Link',
]) . ' ' . $model->phases_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Phase Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->phases_id, 'url' => ['view', 'phases_id' => $model->phases_id, 'link_id' => $model->link_id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="phase-link-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
