<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\shoot\searchs\ExpertSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="expert-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'u_id') ?>

    <?= $form->field($model, 'type') ?>

    <?= $form->field($model, 'birth') ?>

    <?= $form->field($model, 'job_title') ?>

    <?= $form->field($model, 'job_name') ?>

    <?php // echo $form->field($model, 'level') ?>

    <?php // echo $form->field($model, 'employer') ?>

    <?php // echo $form->field($model, 'attainment') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('rcoa', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('rcoa', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
