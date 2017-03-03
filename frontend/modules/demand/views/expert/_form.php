<?php

use frontend\modules\demand\models\BasedataExpert;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model BasedataExpert */
/* @var $form ActiveForm */
?>

<div class="expert-form">

    <?php $form = ActiveForm::begin([
        'options'=>['class'=>'form-horizontal','enctype' => 'multipart/form-data',],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-9 col-md-9\">{input}</div>\n<div class=\"col-lg-9 col-md-9\">{error}</div>",  
            'labelOptions' => ['class' => 'col-lg-2 col-md-2 control-label','style'=>['color'=>'#999999','font-weight'=>'normal']],  
        ], 
    ]); ?>
    <div class="col-lg-7 col-md-7">
        <?= $form->field($model, 'username')->textInput(['maxlength' => true,'readonly'=>($model->getIsNewRecord() ? false : true)]) ?>

        <?= $form->field($model, 'nickname')->textInput() ?>

        <?= $form->field($model, 'sex')->radioList(BasedataExpert::$sexToValue) ?>

        <?= $form->field($model, 'phone')->textInput() ?>

        <?= $form->field($model, 'email')->textInput() ?>

        <?= $form->field($model, 'birth')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'job_title')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'job_name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'employer')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'attainment')->textarea(['rows' => 6]) ?>
    </div>
    <div class="col-lg-5 col-md-5" >
        <?=  $form->field($model, 'personal_image')->widget(FileInput::classname(), [
            'options' => [
                'accept' => 'image/*',
                'multiple'=>true,
            ],
            'pluginOptions' => [
                'resizeImages' => true,
                'initialPreview'=>[
                        Html::img($model->personal_image, ['class'=>'file-preview-image','width'=>'213']),
                    ],
                //'initialCaption'=>"The Moon and the Earth",
                'overwriteInitial'=>true
            ]
         ]); ?>
    </div>
    
    <div class="form-group">
        <div class="col-md-offset-1 col-lg-10">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa/basedata', 'Create') : Yii::t('rcoa/basedata', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
