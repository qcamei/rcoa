<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemTaskProducer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="worksystem-task-producer-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'worksystem_task_id')->textInput() ?>

    <?= $form->field($model, 'team_member_id')->textInput() ?>

    <?= $form->field($model, 'index')->textInput() ?>

    <?= $form->field($model, 'is_delete')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa/worksystem', 'Create') : Yii::t('rcoa/worksystem', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
