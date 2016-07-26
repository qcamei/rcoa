<?php

use common\widgets\ueditor\UeditorAsset;
use common\widgets\uploadFile\UploadFileAsset;
use common\wskeee\filemanage\FileManageAsset;
use kartik\widgets\Select2;
use wskeee\filemanage\models\FileManage;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model FileManage */
/* @var $form ActiveForm */
?>

<div class="file-manage-form">
    
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->dropDownList($model->typeName) ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => '请输入目录名称或文档标题...']) ?>
    
    <?php echo Html::beginTag('div', ['class' => 'form-group field-filemanage-name required']);
        echo Html::label(Yii::t('rcoa/fileManage', 'Owner'), 'filemanage-name', ['class' => 'control-label']);
        echo Select2::widget([
            'data' => $ownerName,
            'value' => $model->isNewRecord ?  '' : $ownerValue,
            'name' => 'FileManageOwner[owner][]',
            'options' => ['placeholder' => '请选择','multiple' => true],
            'pluginOptions' => [
                'tags' => true,
                'allowClear' => true
            ]
        ]);
        echo Html::beginTag('div', ['class' =>'help-block']).Html::endTag('div');
    echo Html::endTag('div');
    ?>
    
    <?= $form->field($model, 'pid')->widget(Select2::classname(), [
        'data' => $fmList, 'hideSearch'=>false, 'options' => ['placeholder' => '为根目录时可不选...'],
    ]) ?>

    <?= $form->field($model, 'keyword')->textInput(['maxlength' => true, 'placeholder' => '请输入关键字...']) ?>
    
    <?= $form->field($model, 'file_link', ['options'=> ['style' => !$model->getFmUpload()?'display: none;':'display:block;']])
                ->textInput(['id' => 'files']) ?>
    <?= Html::textInput('', '文件上传', [
        'id'=> 'upload',
        'class' => 'form-group field-filemanage-file_link',
        'type' => 'button',
        'style' => !$model->getFmUpload() ? 'display: none;' : 'display:block;',
        'onclick' => 'uploadFile()'
    ])?>
    
    <?= $form->field($detail, 'content', ['options'=> ['style' => !$model->getFmFile()?'display: none;':'display:block;']])
            ->textarea([
            'id' => 'container', 
            'type' => 'text/plain', 
            'style' => 'width:100%; height:300px;'
    ]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), 
        ['id' => 'submit','class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php

 $js =   
<<<JS
    $('#container').removeClass('form-control');
    var ue = UE.getEditor('container');
    var myselect = document.getElementById("filemanage-type");
    myselect.onchange = function(){
        switch (myselect.selectedIndex)
        {
            case 0:
                $('.field-filemanagedetail-content').fadeOut();
                $('.field-filemanage-file_link').fadeOut();
                ue.execCommand('cleardoc');
                $('#files').val('');
            break;
            case 1:
                $('.field-filemanagedetail-content').fadeIn();
                $('.field-filemanage-file_link').fadeOut();
                $('#files').val('');
            break;
            case 2:
                $('.field-filemanage-file_link').fadeIn();
                $('.field-filemanagedetail-content').fadeOut();
                ue.execCommand('cleardoc');
            break;
        }
    }
    
    $('#submit').click(function(){
        if(myselect.selectedIndex == 1 && !ue.hasContents()){
            alert('内容不能为空');
            return false;
        }
        if(myselect.selectedIndex == 2 && $('#files').val() == ''){
            alert('附件链接不能为空');
            return false;
        }
        $('#w0').submit();
    });
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 

<?= $this->render('@common/widgets/uploadFile/uploadFile'); ?>

<?php
    FileManageAsset::register($this);
    UeditorAsset::register($this);
    UploadFileAsset::register($this);
?>

