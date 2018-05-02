<?php

use kartik\widgets\Select2;
use wskeee\framework\models\Project;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Project */
/* @var $form ActiveForm */
?>

<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>
    
    <?= $form->field($model, 'parent_id')->widget(Select2::classname(), [
        'data' => $colleges, 'options' => ['placeholder' => Yii::t('rcoa/basedata', 'Placeholder')]
    ])->label(Yii::t('rcoa/basedata', 'College'))?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'des')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa/basedata', 'Create') : Yii::t('rcoa/basedata', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
