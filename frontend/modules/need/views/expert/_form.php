<?php

use frontend\modules\need\models\BasedataExpert;
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
            'template' => "{label}\n<div class=\"col-lg-10 col-md-10\">{input}</div>\n<div class=\"col-lg-9 col-md-9\">{error}</div>",  
            'labelOptions' => ['class' => 'col-lg-2 col-md-2 control-label','style'=>['color'=>'#999999','font-weight'=>'normal','padding-left'=>'9px','padding-right'=>'9px']],  
        ], 
    ]); ?>
    <div class="col-lg-6 col-md-6">
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
        <div class="col-lg-6 col-md-6" >
        <?=  $form->field($model, 'personal_image')->widget(FileInput::class, [
            'options' => [
                'accept' => 'image/*',
                'multiple' => false,
            ],
            'pluginOptions' => [
                'resizeImages' => true,
                'showCaption' => false,
                'showRemove' => false,
                'showUpload' => false,
                'browseClass' => 'btn btn-primary btn-block',
                'browseIcon' => '<i class="glyphicon glyphicon-camera"></i> ',
                'browseLabel' => '选择上传头像...',
                'initialPreview' => [
                            Html::img($model->personal_image, ['class' => 'file-preview-image', 'width' => '203', 'height' => '203']),
                ],
                'overwriteInitial' => true,
            ],
         ]); ?>
    </div>
    
    <div class="form-group">
        <div class="col-md-offset-1 col-lg-10">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
