<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\demand\searchs\DemandTaskSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="demand-task-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'item_type_id') ?>

    <?= $form->field($model, 'item_id') ?>

    <?= $form->field($model, 'item_child_id') ?>

    <?= $form->field($model, 'course_id') ?>

    <?php // echo $form->field($model, 'teacher') ?>

    <?php // echo $form->field($model, 'lesson_time') ?>

    <?php // echo $form->field($model, 'credit') ?>

    <?php // echo $form->field($model, 'course_description') ?>

    <?php // echo $form->field($model, 'mode') ?>

    <?php // echo $form->field($model, 'team_id') ?>

    <?php // echo $form->field($model, 'undertake_person') ?>

    <?php // echo $form->field($model, 'plan_check_harvest_time') ?>

    <?php // echo $form->field($model, 'reality_check_harvest_time') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'create_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'des') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('rcoa/demand', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('rcoa/demand', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
