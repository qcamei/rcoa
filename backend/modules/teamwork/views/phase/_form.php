<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model wskeee\framework\models\Phase */
/* @var $form ActiveForm */
?>

<div class="phase-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'template_type_id')->dropDownList($templateType) ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'weights')->textInput(['maxlength' => true]) ?>

    <?php /*$form->field($model, 'create_by')->textInput(['value' => $model->createBy->nickname, 'disabled' => 'disabled', 'maxlength' => true])*/ ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
