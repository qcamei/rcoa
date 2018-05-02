<?php

use common\models\demand\DemandWorkitemTemplate;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use kartik\widgets\TouchSpin;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DemandWorkitemTemplate */
/* @var $form ActiveForm */
?>

<div class="demand-workitem-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'demand_workitem_template_type_id')->widget(Select2::classname(), [
        'data' => $templateTypes, 'hideSearch'=>false, 'options' => ['placeholder' => '请选择...'],
    ]) ?>
    
    <?= $form->field($model, 'workitem_type_id')->widget(Select2::classname(), [
        'data' => $workitemTypes, 'hideSearch'=>false, 'options' => ['placeholder' => '请选择...'],
    ]) ?>
    
    <?= $form->field($model, 'workitem_id')->widget(Select2::classname(), [
        'data' => $workitems, 'hideSearch'=>false, 'options' => ['placeholder' => '请选择...'],
    ]) ?>

    <?= $form->field($model, 'index')->widget(TouchSpin::classname(), [
        'pluginOptions' => [
            'placeholder' => '顺序 ...',
            'min' => -1,
            'max' => 999999999,
        ],
    ])?>
    
    <?= $form->field($model, 'is_new')->widget(SwitchInput::classname(), [
        'pluginOptions' => [
            'onText' => '是',
            'offText' => '否',
    ]]) ?>
    
    <?= $form->field($model, 'value_type')->widget(SwitchInput::classname(), [
        'pluginOptions' => [
            'onText' => '时间',
            'offText' => '数字',
    ]]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
