<?php

use common\models\multimedia\MultimediaAssignTeam;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model MultimediaAssignTeam */
/* @var $form ActiveForm */
?>

<div class="multimedia-assign-team-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'team_id')->widget(Select2::className(), [
        'data' => $team, 'options' => ['placeholder' => '请选择...']
    ]) ?>

    <?= $form->field($model, 'u_id')->widget(Select2::className(),[
        'data' => $user, 'options' => ['placeholder' => '请选择...']
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
