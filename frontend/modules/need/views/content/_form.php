<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\need\NeedContent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="need-content-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'need_task_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'workitem_type_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'workitem_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_new')->textInput() ?>

    <?= $form->field($model, 'price')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plan_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reality_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort_order')->textInput() ?>

    <?= $form->field($model, 'is_del')->textInput() ?>

    <?= $form->field($model, 'created_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
