<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\multimedia\MultimediaProportion */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="multimedia-proportion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'proportion')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'des')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
