<?php

use common\models\Banner;
use kartik\widgets\TouchSpin;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Banner */
/* @var $form ActiveForm */
?>

<div class="banner-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'path')->textInput(['maxlength' => true, 'placeholder'=> '/文件路径/文件名称']) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => true, 'placeholder'=> '/url']) ?>

    <?= $form->field($model, 'des')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'index')->widget(TouchSpin::classname(),  [
            'readonly' => true,
            'pluginOptions' => [
                'placeholder' => '序号 ...',
                'min' => 1,
                'max' => 5,
            ],
        ])?>

    <?= $form->field($model, 'isdisplay')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
