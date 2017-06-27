<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\worksystem\searchs\WorksystemContentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="worksystem-content-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'worksystem_task_type_id') ?>

    <?= $form->field($model, 'type_name') ?>

    <?= $form->field($model, 'icon') ?>

    <?= $form->field($model, 'is_new') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'unit') ?>

    <?php // echo $form->field($model, 'des') ?>

    <?php // echo $form->field($model, 'index') ?>

    <?php // echo $form->field($model, 'is_delete') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('rcoa/worksystem', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('rcoa/worksystem', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
