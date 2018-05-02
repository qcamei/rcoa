<?php

use common\models\multimedia\MultimediaContentType;
use kartik\widgets\TouchSpin;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model MultimediaContentType */
/* @var $form ActiveForm */
?>

<div class="multimedia-content-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    
    <?php 
        if(!$model->isNewRecord){
            echo $form->field($model, 'index')->widget(TouchSpin::classname(),  [
                    'pluginOptions' => [
                        'placeholder' => '顺序 ...',
                        'min' => -1,
                        'max' => 999999999,
                    ],
                ]);
        }
    ?>

    <?= $form->field($model, 'des')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
