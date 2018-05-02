<?php

use common\models\workitem\WorkitemCost;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model WorkitemCost */
/* @var $form ActiveForm */
?>

<div class="workitem-cost-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'workitem_id')->widget(Select2::classname(), [
        'data' => $workitems, 'hideSearch'=>false, 'options' => ['placeholder' => '请选择...'],
    ]) ?>

    <?= $form->field($model, 'cost_new')->textInput() ?>

    <?= $form->field($model, 'cost_remould')->textInput() ?>

    <?= $form->field($model, 'target_month')->textInput([
        'value' => date('Y-m', time()),
        'disabled' => 'disabled',
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
