<?php

use common\models\User;
use kartik\widgets\FileInput;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model User */
/* @var $form ActiveForm */

?>

<div class="user-form">
    <?php $form = ActiveForm::begin([
        'options' => [
            'class'=>'form-horizontal',
            'enctype' => 'multipart/form-data',
        ],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-9 col-md-9\">{input}</div>\n<div class=\"col-lg-7 col-md-7\">{error}</div>",  
            'labelOptions' => ['class' => 'col-lg-2 col-md-2 control-label','style'=>['color'=>'#999999','font-weight'=>'normal','padding-left' => '0']],  
        ], 
    ]); ?>
    <div class="col-lg-7 col-md-7">
        <?= $form->field($model, 'username')->textInput(['readonly'=>'']); ?>

        <?= $form->field($model, 'nickname')->textInput(['readonly'=>'']); ?>

        <?= $form->field($model, 'password')->passwordInput(['minlength'=>6, 'maxlength'=>20, 'value'=>'']); ?>

        <?= $form->field($model, 'password2')->passwordInput(['minlength'=>6, 'maxlength'=>20, 'value'=>'']); ?>

        <?= $form->field($model, 'ee')->textInput(['minlength'=>6,'maxlength'=>20, 'value'=>'']); ?>

        <?= $form->field($model, 'phone')->textInput(['minlength'=>11,'maxlength'=>11, 'value'=>'']); ?>

        <?= $form->field($model, 'email')->textInput(['maxlength' => 200, 'value'=>'']) ?>
    </div>
    
    <div class="col-lg-5 col-md-5" >
    <?=  $form->field($model, 'avatar')->widget(FileInput::classname(), [
        'options' => [
            'accept' => 'image/*',
            'multiple'=>true,
        ],
        'pluginOptions' => [
            'resizeImages' => true,
            'initialPreview'=>[
                    Html::img($model->avatar, ['class'=>'file-preview-image','width'=>'213']),
                ],
            //'initialCaption'=>"The Moon and the Earth",
            'overwriteInitial'=>true
        ]
     ]); ?>
    </div>
    
    <div class="col-lg-10 col-md-10 form-group">
        <?= Html::submitButton( '保存', ['class'=> 'btn btn-primary',]) ?> 
    </div>
    
    <?php $form->end(); ?>
    
</div>
<script type="text/javascript">
   /* //图片上传预览插件
    function setImagePreviews(avalue) {
        var fielObj = document.getElementById("avatar");     //获取input:fiel id
        var pic_path = document.getElementById("pic_path");      //获取div id 
        pic_path.innerHTML = "";
        var fileList = fielObj.files;
        for (var i = 0; i < fileList.length; i++) {            
            pic_path.innerHTML += "<div> <img id='img" + i + "'/> </div>";
            var imgObjPreview = document.getElementById("img"+i); 
            if (fielObj.files && fielObj.files[i]) {
                //火狐下，直接设img属性
                imgObjPreview.style.display = 'block';
                imgObjPreview.style.width = '242px';
                imgObjPreview.style.height = '242px';
                //imgObjPreview.src = docObj.files[0].getAsDataURL();
                //火狐7以上版本不能用上面的getAsDataURL()方式获取，需要一下方式
                imgObjPreview.src = window.URL.createObjectURL(fielObj.files[i]);
            }
            else {
                //IE下，使用滤镜
                fielObj.select();
                var imgSrc = document.selection.createRange().text;
                alert(imgSrc)
                var localImagId = document.getElementById("img" + i);
                //必须设置初始大小
                localImagId.style.width = "242px";
                localImagId.style.height = "242px";
                //图片异常的捕捉，防止用户修改后缀来伪造图片
                try {
                    localImagId.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)";
                    localImagId.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = imgSrc;
                }
                catch (e) {
                    alert("您上传的图片格式不正确，请重新选择!");
                    return false;
                }
                imgObjPreview.style.display = 'none';
                document.selection.empty();
            }
        }  
        return true;
    }*/


</script>
