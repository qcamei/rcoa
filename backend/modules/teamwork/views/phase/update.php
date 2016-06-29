<?php

use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model wskeee\framework\models\Phase */

$this->title = Yii::t('rcoa/teamwork', 'Update Phase') . ': ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Phases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa', 'Update');
?>
<div class="phase-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
