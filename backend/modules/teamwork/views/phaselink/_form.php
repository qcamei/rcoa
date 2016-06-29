<?php

use common\models\teamwork\PhaseLink;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model PhaseLink */
/* @var $form ActiveForm */
?>

<div class="phase-link-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'phases_id')->textInput() ?>

    <?= $form->field($model, 'link_id')->textInput() ?>

    <?= $form->field($model, 'total')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'completed')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'progress')->textInput() ?>

    <?= $form->field($model, 'create_by')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa/teamwork', 'Create') : Yii::t('rcoa/teamwork', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
