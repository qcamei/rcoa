<?php

use common\models\teamwork\TemplateType;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model TemplateType */
/* @var $form ActiveForm */
?>

<div class="template-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?php /*$form->field($model, 'create_by')->textInput(['maxlength' => true])

    $form->field($model, 'created_at')->textInput() 

     $form->field($model, 'updated_at')->textInput()*/ ?>
    
    <?= $form->field($model, 'des')->textarea() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
