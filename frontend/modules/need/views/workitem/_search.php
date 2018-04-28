<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\demand\searchs\DemandWorkitemSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="demand-workitem-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'demand_task_id') ?>

    <?= $form->field($model, 'workitem_type_id') ?>

    <?= $form->field($model, 'workitem_id') ?>

    <?= $form->field($model, 'is_new') ?>

    <?php // echo $form->field($model, 'value') ?>

    <?php // echo $form->field($model, 'cost') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('rcoa/demand', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('rcoa/demand', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
