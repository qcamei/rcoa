<?php

use common\models\need\NeedContentPsd;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model NeedContentPsd */
/* @var $form ActiveForm */
?>

<div class="need-content-psd-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'workitem_type_id')->widget(Select2::class, [
        'data' => $workitemType,
        'options' => ['placeholder' => '请选择...'],
    ]) ?>

    <?= $form->field($model, 'workitem_id')->widget(Select2::class, [
        'data' => $workitem,
        'options' => ['placeholder' => '请选择...'],
    ]) ?>

    <?= $form->field($model, 'price_new')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'price_remould')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_del')->widget(SwitchInput::class, [
        'pluginOptions' => [
            'onText' => Yii::t('app', 'Y'),
            'offText' => Yii::t('app', 'N'),
        ]
    ]) ?> 
    
    <?= $form->field($model, 'sort_order')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
