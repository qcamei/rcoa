<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\mconline\searchs\McbsCourseSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mcbs-course-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'item_type_id') ?>

    <?= $form->field($model, 'item_id') ?>

    <?= $form->field($model, 'item_child_id') ?>

    <?= $form->field($model, 'course_id') ?>

    <?php // echo $form->field($model, 'create_by') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'is_publish') ?>

    <?php // echo $form->field($model, 'publish_time') ?>

    <?php // echo $form->field($model, 'close_time') ?>

    <?php // echo $form->field($model, 'des') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
