<?php

use common\models\need\searchs\NeedContentPsdSearch;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model NeedContentPsdSearch */
/* @var $form ActiveForm */
?>

<div class="need-content-psd-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'workitem_type_id') ?>

    <?= $form->field($model, 'workitem_id') ?>

    <?= $form->field($model, 'price_new') ?>

    <?= $form->field($model, 'price_remould') ?>

    <?php // echo $form->field($model, 'sort_order') ?>
    
    <?php // echo $form->field($model, 'is_del') ?> 

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
