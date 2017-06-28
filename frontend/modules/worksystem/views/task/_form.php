<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\worksystem\WorksystemTask */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="worksystem-task-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'item_type_id')->textInput() ?>

    <?= $form->field($model, 'item_id')->textInput() ?>

    <?= $form->field($model, 'item_child_id')->textInput() ?>

    <?= $form->field($model, 'course_id')->textInput() ?>

    <?= $form->field($model, 'task_type_id')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'level')->textInput() ?>

    <?= $form->field($model, 'is_epiboly')->textInput() ?>

    <?= $form->field($model, 'budget_cost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reality_cost')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'budget_bonus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'reality_bonus')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'plan_end_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'external_team')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'progress')->textInput() ?>

    <?= $form->field($model, 'create_team')->textInput() ?>

    <?= $form->field($model, 'create_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'index')->textInput() ?>

    <?= $form->field($model, 'is_delete')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <?= $form->field($model, 'finished_at')->textInput() ?>

    <?= $form->field($model, 'des')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa/worksystem', 'Create') : Yii::t('rcoa/worksystem', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
