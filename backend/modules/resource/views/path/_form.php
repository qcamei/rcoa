<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\resource\ResourcePath */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="resource-path-form">
    
    <?php $form = ActiveForm::begin(); ?>
    
    <?= Html::activeHiddenInput($model, 'r_id') ?>
    
    <?= $form->field($model, 'path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->radioList(['图片','视频']) ?>

    <?= $form->field($model, 'des')->textarea(['rows'=>'10']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
