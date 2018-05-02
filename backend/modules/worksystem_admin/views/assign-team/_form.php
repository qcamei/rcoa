<?php

use common\models\worksystem\WorksystemAssignTeam;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model WorksystemAssignTeam */
/* @var $form ActiveForm */
?>

<div class="worksystem-assign-team-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'team_id')->widget(Select2::className(), [
        'data' => $teams, 'options' => ['placeholder' => '请选择...']
    ]) ?>

    <?= $form->field($model, 'user_id')->widget(Select2::className(),[
        'data' => $users, 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'des')->hiddenInput(['value' => '无']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
