<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\searchs\ItemManageSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-manage-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'item_type_id') ?>

    <?= $form->field($model, 'item_id') ?>

    <?= $form->field($model, 'item_child_id') ?>

    <?= $form->field($model, 'create_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'forecast_time') ?>

    <?php // echo $form->field($model, 'real_carry_out') ?>

    <?php // echo $form->field($model, 'progress') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'background') ?>

    <?php // echo $form->field($model, 'use') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('rcoa/framework', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('rcoa/framework', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
