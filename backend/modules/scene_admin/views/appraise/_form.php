<?php

use common\models\scene\SceneAppraise;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model SceneAppraise */
/* @var $form ActiveForm */
?>

<div class="shoot-appraise-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'role')->dropDownList($roles, ['prompt'=>'请选择...']) ?>

    <?= $form->field($model, 'q_id')->dropDownList($questions, ['prompt'=>'请选择...']) ?>
    
    <?= $form->field($model, 'value')->textInput() ?>

    <?= $form->field($model, 'index')->textInput() ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
