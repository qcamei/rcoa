<?php

use common\models\worksystem\WorksystemAttributes;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model WorksystemAttributes */
/* @var $form ActiveForm */
?>

<div class="worksystem-attributes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(WorksystemAttributes::$typeName) ?>

    <?= $form->field($model, 'input_type')->dropDownList(WorksystemAttributes::$inputTypeName) ?>

    <?= $form->field($model, 'value_list')->textarea(['rows' => 6, 'placeholder' => '输入类型非【列表中选择】，可忽略不填']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
