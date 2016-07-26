<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\teamwork\CourseAnnex */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="course-annex-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'course_id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'path')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa/teamwork', 'Create') : Yii::t('rcoa/teamwork', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
