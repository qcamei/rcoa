<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\mconline\McbsCourse */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mcbs-course-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_type_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_child_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'course_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'is_publish')->textInput() ?>

    <?= $form->field($model, 'publish_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'close_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'des')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
