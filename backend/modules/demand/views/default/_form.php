<?php

use common\models\demand\DemandTaskAuditor;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DemandTaskAuditor */
/* @var $form ActiveForm */
?>

<div class="demand-task-auditor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'team_id')->widget(Select2::classname(), [
        'data' => $teams, 'hideSearch'=>false, 'options' => ['placeholder' => '请选择...'],
    ]) ?>
    
    <?= $form->field($model, 'u_id')->widget(Select2::classname(), [
        'data' => $users, 'hideSearch'=>false, 'options' => ['placeholder' => '请选择...'],
    ]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
