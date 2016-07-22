<?php

use common\widgets\ueditor\UeditorAsset;
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

<script type="text/javascript">
window['process'] = function(result){
        window['FILELIST'] = JSON.parse(result['data']);
}

function uploadFile(){
    //var testPath = 'http://eechat.tt.gzedu.com/';
    //var formalPath = 'http://eechat.gzedu.com/'; 
    var api = $.dialog({
        id: 'LHG76D',
        //content: 'url:http://127.0.0.1:8080/ee_fis/upload/toUpload.do?formMap.filetype=ppt|doc|docx|xls|xlsx|pptx|txt|rar|zip|mp3|mp4|rmvb|wmv|flv|swf|3gp|jpg&formMap.filecwd=/files1/file&formMap.appId=APP005&formMap.filenum=2&formMap.origin=http://127.0.0.1:8080/ee_fis/uploadIframe.html&formMap.convert=Y&formMap.appType=oos&formMap.fileName=mp4/object_name&formMap.bucket=ougz-video',
        content: 'url:http://eefile.gzedu.com/upload/toUpload.do?formMap.filetype=ppt|doc|docx|xls|xlsx|pptx|txt|rar|zip|mp3|mp4|rmvb|wmv|flv|swf|3gp|jpg&formMap.filecwd=/files1/file&formMap.appId=APP015&formMap.filenum=1&formMap.origin=http://ccoaadmin.gzedu.net/uploadIframe/uploadIframe.html',
        //content: 'url:http://eefile.gzedu.com/upload/toUpload.do?formMap.filetype=ppt|doc|docx|xls|xlsx|pptx|txt|rar|zip|mp3|mp4|rmvb|wmv|flv|swf|3gp|jpg&formMap.filecwd=/files1/file&formMap.appId=APP005&formMap.filenum=2&formMap.origin=http://127.0.0.1:8080/ee_chat/uploadIframe.html&formMap.convert=Y&formMap.appType=oos&formMap.fileName=mp4/object_name&formMap.bucket=ougz-video',		
        title: '文件上传',
        width: 460,
        height: 360,
        button:[{
            name : '取消上传',
            callback : function(win){}
        },{
            name: '完成上传',
            callback: function (win) {
                var fileList = win['FILELIST'], 
                        filelist = [],
                        NameMD5List = [];

                if(fileList && fileList.length > 0){
                    for(var i = 0; i < fileList.length; i++){
                        filelist.push(fileList[i].FileURL);
                        NameMD5List.push(fileList[i].FileMD5);
                    }
                    $('#files').val(filelist.join(''));
                    //$('#md5').val(NameMD5List.join(''));
                    window['FILELIST'] = [];
                }
            },
            focus : true
        }]
    });
}
</script>

<?php
    FileManageAsset::register($this);
    UeditorAsset::register($this);
?>

