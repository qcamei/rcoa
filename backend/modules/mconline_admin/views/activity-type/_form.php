<?php

use common\models\mconline\McbsActivityType;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model McbsActivityType */
/* @var $form ActiveForm */
?>

<div class="mcbs-activity-type-form">

    <?php $form = ActiveForm::begin([
                'options' => [
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data',
                ],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-9 col-md-9\">{input}</div>\n<div class=\"col-lg-7 col-md-7\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-2 col-md-2 control-label', 'style' => ['color' => '#999999', 'font-weight' => 'normal', 'padding-left' => '0']],
                ],
    ]);?>
    <div class="col-lg-7 col-md-7">
        
        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'des')->textInput(['maxlength' => true]) ?>
        
        <?= $form->field($model, 'des')->textarea(['rows'=>6,'value'=>$model->isNewRecord?'无':$model->des]) ?>

    </div>

    <div class="col-lg-5 col-md-5">
        <?= $form->field($model, 'icon_path')->widget(FileInput::classname(), [
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
                'browseLabel' => '选择上传图像...',
                'initialPreview' => [
                    $model->isNewRecord ?
                            Html::img(Yii::getAlias('@mconline') . '/web/upload/activity_type_icons/', ['class' => 'file-preview-image', 'width' => '60']) :
                            Html::img(MCONLINE_WEB_ROOT . $model->icon_path, ['class' => 'file-preview-image', 'width' => '60']),
                ],
                'overwriteInitial' => true,
            ],
        ]);?>
    </div>

    <div class="col-lg-10 col-md-10 form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
