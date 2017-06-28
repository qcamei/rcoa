<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\worksystem\searchs\WorksystemContentinfoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="worksystem-contentinfo-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'worksystem_task_id') ?>

    <?= $form->field($model, 'worksystem_content_id') ?>

    <?= $form->field($model, 'price') ?>

    <?= $form->field($model, 'budget_number') ?>

    <?php // echo $form->field($model, 'budget_cost') ?>

    <?php // echo $form->field($model, 'reality_number') ?>

    <?php // echo $form->field($model, 'reality_cost') ?>

    <?php // echo $form->field($model, 'index') ?>

    <?php // echo $form->field($model, 'is_delete') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('rcoa/worksystem', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('rcoa/worksystem', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
