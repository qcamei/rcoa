<?php

use common\models\workitem\Workitem;
use kartik\widgets\TouchSpin;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Workitem */
/* @var $form ActiveForm */
?>

<div class="workitem-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'index')->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '顺序 ...',
            'min' => -1,
            'max' => 999999999,
        ],
    ])?>

    <?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'des')->textarea(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
