<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\PhaseLink */

$this->title = Yii::t('rcoa/framework', 'Create Phase Link');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/framework', 'Phase Links'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phase-link-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
