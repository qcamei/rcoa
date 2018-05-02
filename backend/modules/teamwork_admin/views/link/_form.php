<?php

use common\models\teamwork\Link;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Link */
/* @var $form ActiveForm */
?>

<div class="link-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'phase_id')->textInput(['value' => $model->phase->name]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(Link::$types) ?>

    <?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>

    <?php /*$form->field($model, 'create_by')->textInput(['value' => $model->createBy->nickname, 'disabled' => 'disabled', 'maxlength' => true])*/ ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
