<?php

use common\models\demand\DemandWeightTemplate;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DemandWeightTemplate */
/* @var $form ActiveForm */
?>

<div class="demand-weight-template-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'workitem_type_id')->widget(Select2::classname(), [
        'data' => $workitemType, 'hideSearch'=>false, 'options' => ['placeholder' => '请选择...'],
    ]) ?>

    <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sl_weight')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'zl_weight')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
