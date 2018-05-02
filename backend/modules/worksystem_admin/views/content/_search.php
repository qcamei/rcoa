<?php

use common\models\worksystem\searchs\WorksystemContentSearch;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model WorksystemContentSearch */
/* @var $form ActiveForm */
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

    <?= $form->field($model, 'price_new') ?>

    <?php // echo $form->field($model, 'price_remould') ?>

    <?php // echo $form->field($model, 'unit') ?>

    <?php // echo $form->field($model, 'des') ?>

    <?php // echo $form->field($model, 'index') ?>

    <?php // echo $form->field($model, 'is_delete') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>