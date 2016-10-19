<?php

use common\models\multimedia\MultimediaTypeProportion;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model MultimediaTypeProportion */
/* @var $form ActiveForm */
?>

<div class="multimedia-type-proportion-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
        echo Html::beginTag('div', ['class' => 'form-group field-multimediatypeproportion-content_type has-success']);
            echo Html::beginTag('label', [
                    'class' => 'control-label', 
                    'for' => 'multimediatypeproportion-content_type'
                ]).Yii::t('rcoa/multimedia', 'Name Type').Html::endTag('label');
            echo Select2::widget([
                'name' => 'MultimediaTypeProportion[content_type]',
                'value' => $model->isNewRecord ? $contentType : $model->content_type, 
                'data' => $contentTypes,
                'hideSearch' => true,
                'options' => ['placeholder' => '请选择...']

            ]);
            echo Html::beginTag('div', ['class' => 'help-block']).Html::endTag('div').Html::endTag('div');
        echo Html::endTag('div');
    ?>
    
    <?= $form->field($model, 'proportion')->textInput(['maxlength' => true, 'value' => ($model->proportion / 10) * 10]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
