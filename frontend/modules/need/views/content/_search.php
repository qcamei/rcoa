<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\need\searchs\NeedContentSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="need-content-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'need_task_id') ?>

    <?= $form->field($model, 'workitem_type_id') ?>

    <?= $form->field($model, 'workitem_id') ?>

    <?= $form->field($model, 'is_new') ?>

    <?php // echo $form->field($model, 'price') ?>

    <?php // echo $form->field($model, 'plan_num') ?>

    <?php // echo $form->field($model, 'reality_num') ?>

    <?php // echo $form->field($model, 'sort_order') ?>

    <?php // echo $form->field($model, 'is_del') ?>

    <?php // echo $form->field($model, 'created_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
