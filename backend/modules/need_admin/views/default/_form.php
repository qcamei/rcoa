<?php

use common\models\workitem\Workitem;
use common\widgets\ueditor\UeditorAsset;
use kartik\widgets\FileInput;
use kartik\widgets\TouchSpin;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Workitem */
/* @var $form ActiveForm */
?>

<div class="workitem-form">

    <?php $form = ActiveForm::begin([
        'options' => [
            'class'=>'form-horizontal',
            'enctype' => 'multipart/form-data',
        ],
        
    ]); ?>
    <div class="col-lg-12 col-md-12">
        <div class="col-lg-7 col-md-7">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?=
            $form->field($model, 'index')->widget(TouchSpin::classname(), [
                'pluginOptions' => [
                    'placeholder' => '顺序 ...',
                    'min' => -1,
                    'max' => 999999999,
                ],
            ])
            ?>

            <?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'des')->textarea(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-1 col-md-1"></div>
        <div class="col-lg-4 col-md-4">
            <?php
            echo $form->field($model, 'cover')->widget(FileInput::classname(), [
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
                        Html::img(WEB_ROOT . $model->cover, ['class' => 'file-preview-image', 'width' => '213']),
                    ],
                    'overwriteInitial' => true,
                ],
            ]);
            ?>
        </div>
    </div>
    
    <div style="margin-left: 25px;margin-right: 25px">
        <?= $form->field($model, 'content')->textarea([
            'id' => 'container',
            'type' => 'text/plain',
            'style' => 'width:100%; height:800px',
            'placeholder' => '详情...'
        ]) ?>
    </div>
    
    <div class="form-group" style="margin-left: 15px;">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

 $js =
<<<JS
    $('#container').removeClass('form-control');
    var ue = UE.getEditor('container', {toolbars:[
        [
            'fullscreen', 'source', '|', 'undo', 'redo', '|',  
            'bold', 'italic', 'underline','fontborder', 'strikethrough', 'removeformat', 'formatmatch', '|', 
            'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
            'paragraph', 'fontfamily', 'fontsize', '|',
            'justifyleft', 'justifyright' , 'justifycenter', 'justifyjustify', '|',
            'simpleupload', 'horizontal'
        ]
    ]});
    ue.ready(function(){
        ue.setContent('$model->content');
    });
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 

<?php
    UeditorAsset::register($this);
?>