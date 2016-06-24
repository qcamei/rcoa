<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\searchs\PhaseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="phase-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'weights') ?>

    <?= $form->field($model, 'progress') ?>

    <?= $form->field($model, 'create_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('rcoa/framework', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('rcoa/framework', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
