<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\shoot\searchs\ExpertProjectSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="expert-project-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'expert_id') ?>

    <?= $form->field($model, 'project_id') ?>

    <?= $form->field($model, 'start_time') ?>

    <?= $form->field($model, 'end_time') ?>

    <?php // echo $form->field($model, 'cost') ?>

    <?php // echo $form->field($model, 'compatibility') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('rcoa', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('rcoa', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
