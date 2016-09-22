<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\multimedia\searchs\MultimediaTaskSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="multimedia-manage-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'item_type_id') ?>

    <?= $form->field($model, 'item_id') ?>

    <?= $form->field($model, 'item_child_id') ?>

    <?= $form->field($model, 'course_id') ?>

    <?php // echo $form->field($model, 'name') ?>

    <?php // echo $form->field($model, 'video_length') ?>

    <?php // echo $form->field($model, 'workload') ?>

    <?php // echo $form->field($model, 'proportion') ?>

    <?php // echo $form->field($model, 'content_type') ?>

    <?php // echo $form->field($model, 'carry_out_time') ?>

    <?php // echo $form->field($model, 'level') ?>

    <?php // echo $form->field($model, 'make_team') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'path') ?>

    <?php // echo $form->field($model, 'create_team') ?>

    <?php // echo $form->field($model, 'create_by') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'des') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('rcoa/multimedia', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('rcoa/multimedia', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
