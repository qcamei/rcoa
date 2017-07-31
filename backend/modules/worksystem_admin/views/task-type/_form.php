<?php

use common\models\worksystem\WorksystemTaskType;
use kartik\widgets\FileInput;
use kartik\widgets\TouchSpin;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model WorksystemTaskType */
/* @var $form ActiveForm */
?>

<div class="worksystem-task-type-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'icon')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'multiple' => false,
           // 'value' => !$model->isNewRecord && $model->type == 'video' ? $model->poster : $model->path
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
                Html::img(WEB_ROOT . $model->icon, ['class' => 'file-preview-image','width' => '400']),
            ],
            'overwriteInitial' => true,
        ],
    ]);
    ?>

    <?= $form->field($model, 'des')->textarea(['rows' => 6, 'value' => !$model->isNewRecord ? $model->des : '无' ]) ?>

    <?= $form->field($model, 'index')->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '顺序 ...',
            'min' => -1,
            'max' => 999999999,
        ],
    ])?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
