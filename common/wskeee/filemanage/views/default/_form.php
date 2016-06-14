<?php

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

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => '请输入目录名称或文档标题...']) ?>
    
    <?= $form->field($owner, 'owner')->widget(Select2::classname(), [
        'value' => $model->isNewRecord ?  '' : $ownerValue,
        'data' => $ownerName,
        'maintainOrder' => true,
        'options' => ['placeholder' => '请选择...', 'multiple' => true],
        'pluginOptions' => [
            'tags' => false,
            'maximumInputLength' => 10,
            'allowClear' => true,
        ],
    ]) ?>

    <?= $form->field($model, 'pid')->widget(Select2::classname(), [
        'data' => $fmList, 'hideSearch'=>false, 'options' => ['placeholder' => '为根目录时可不选...'],
    ]) ?>

    <?= $form->field($model, 'keyword')->textInput(['maxlength' => true, 'placeholder' => '请输入关键字...']) ?>
    
    <?= $form->field($model, 'image')->dropDownList([
        FileManage::IMAGE_FOLDER => '目录图标', FileManage::IMAGE_FILE =>'文档图标'
    ]) ?>
    
    <?= $form->field($model, 'icon')->dropDownList([
        FileManage::ICON_FOLDER => '目录图标', FileManage::ICON_FILE =>'文档图标'
    ]) ?>
    
    <?= $form->field($model, 'type')->radioList([FileManage::FM_LIST => '目录', FileManage::FM_FILE => '文档']) ?>
    
    <?= $form->field($detail, 'content')->textarea([
        'id' => 'container', 
        'type' => 'text/plain', 
        'style' => 'width:100%; height:300px;'
    ]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
 
 $js =   
<<<JS
    var isNewRecord = "$model->isNewRecord";
    var type = "$model->type";
    if(!isNewRecord && type == 2){
        $("input:radio").eq(1).attr("checked",true);
        $('.field-filemanagedetail-content').css('display','block');
    }else{
        $("input:radio").eq(0).attr("checked",true);
        $('.field-filemanagedetail-content').css('display','none');
    }
    $("input:radio").eq(0).click(function(){
       $('.field-filemanagedetail-content').css('display','none');
    });
    $("input:radio").eq(1).click(function(){
       $('.field-filemanagedetail-content').css('display','block');
    });
    $('#container').removeClass('form-control');
    var ue = UE.getEditor('container');
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 

<?php
    FileManageAsset::register($this);
?>
