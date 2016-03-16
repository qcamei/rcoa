<?php

use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model System */
/* @var $form ActiveForm */
?>

<div class="system-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'path')->textInput(['maxlength' => true,'placeholder'=> '/图片路径/图片名称']) ?>
    
    <?= $form->field($model, 'link')->textInput(['maxlength' => true,'placeholder'=> '非跳转页面：/url，跳转页面：http://域名/url']) ?>

    <?= $form->field($model, 'des')->textInput(['maxlength' => true]) ?>
    
    <?= $form->field($model, 'isdisplay')->checkbox() ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '创建' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
