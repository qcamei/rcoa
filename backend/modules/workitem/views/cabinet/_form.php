<?php

use common\models\workitem\WorkitemCabinet;
use kartik\widgets\FileInput;
use yii\helpers\BaseHtml;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model WorkitemCabinet */
/* @var $form ActiveForm */
?>

<div class="workitem-cabinet-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'enctype' => 'multipart/form-data',
        ],
    ]); ?>

    <?= BaseHtml::activeHiddenInput($model, 'workitem_id') ?>
    
    <?= $form->field($model, 'index')->textInput() ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->radioList(['image' => '图片','video' => '视频']) ?>
    
    <!-- 资源类型为图片时，保存为主资源路径，不是即为预览图 -->
    <?= $form->field($model, 'poster')->widget(FileInput::classname(), [
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
                Html::img(WEB_ROOT . ($model->type == 'image' ? $model->path : $model->poster), ['class' => 'file-preview-image','width' => '400']),
            ],
            'overwriteInitial' => true,
        ],
    ]);
    ?>
    <?= $form->field($model, 'path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'content')->textarea(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<?php
 $js =
<<<JS
    function changeInputMode(mode){
         console.log(mode);
         console.log($('workitemcabinet-path'));
        if(mode == 'image')
            $('.field-workitemcabinet-path').hide();
        else
            $('.field-workitemcabinet-path').show();
    }
    changeInputMode("$model->type");
    $('input:radio[name="WorkitemCabinet[type]"]').change(function(){
        changeInputMode($(this).val());
    });
JS;
    $this->registerJs($js,  View::POS_READY); 
?>