<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\worksystem\searchs\WorksystemTaskSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="worksystem-task-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'item_type_id') ?>

    <?= $form->field($model, 'item_id') ?>

    <?= $form->field($model, 'item_child_id') ?>

    <?= $form->field($model, 'course_id') ?>

    <?php // echo $form->field($model, 'task_type_id') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'level') ?>

    <?php // echo $form->field($model, 'is_epiboly') ?>

    <?php // echo $form->field($model, 'budget_cost') ?>

    <?php // echo $form->field($model, 'reality_cost') ?>

    <?php // echo $form->field($model, 'budget_bonus') ?>

    <?php // echo $form->field($model, 'reality_bonus') ?>

    <?php // echo $form->field($model, 'plan_end_time') ?>

    <?php // echo $form->field($model, 'external_team') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'progress') ?>

    <?php // echo $form->field($model, 'create_team') ?>

    <?php // echo $form->field($model, 'create_by') ?>

    <?php // echo $form->field($model, 'index') ?>

    <?php // echo $form->field($model, 'is_delete') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'finished_at') ?>

    <?php // echo $form->field($model, 'des') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('rcoa/worksystem', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('rcoa/worksystem', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
