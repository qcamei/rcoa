<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\searchs\PhaseLinkSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="phase-link-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'phases_id') ?>

    <?= $form->field($model, 'link_id') ?>

    <?= $form->field($model, 'total') ?>

    <?= $form->field($model, 'completed') ?>

    <?= $form->field($model, 'progress') ?>

    <?php // echo $form->field($model, 'create_by') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('rcoa/framework', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('rcoa/framework', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
