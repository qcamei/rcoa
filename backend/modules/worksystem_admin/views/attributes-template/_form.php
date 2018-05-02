<?php

use common\models\worksystem\WorksystemAttributesTemplate;
use kartik\widgets\Select2;
use kartik\widgets\TouchSpin;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model WorksystemAttributesTemplate */
/* @var $form ActiveForm */
?>

<div class="worksystem-attributes-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'worksystem_task_type_id')->widget(Select2::className(), [
        'data' => $taskTypes, 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'worksystem_attributes_id')->widget(Select2::className(), [
        'data' => $attributes, 'options' => ['placeholder' => '请选择...']
    ]) ?>

    <?= $form->field($model, 'index')->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '顺序 ...',
            'min' => -1,
            'max' => 999999999,
        ],
    ])?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
