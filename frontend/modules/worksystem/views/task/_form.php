<?php

use common\models\worksystem\WorksystemTask;
use common\widgets\uploadFile\UploadFileAsset;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model WorksystemTask */
/* @var $form ActiveForm */
?>

<div class="worksystem-task-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'worksystem-task-form',
            'class'=>'form-horizontal',
        ],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-10 col-md-10\">{input}</div>\n<div class=\"col-lg-10 col-md-10\">{error}</div>",  
            'labelOptions' => [
                'class' => 'col-lg-1 col-md-1 control-label form-label',
            ],  
        ], 
    ]); ?>

    <h5><b>基础信息</b></h5>
    
    <?= $form->field($model, 'item_type_id')->dropDownList($itemTypes, ['prompt' => '请选择...']) ?>

    <?= $form->field($model, 'item_id')->widget(Select2::classname(), [
        'data' => $items, 'options' => ['placeholder' => '请选择...',]
    ]) ?>
    
    <?= $form->field($model, 'item_child_id')->widget(Select2::classname(), [
        'data' => $itemChilds, 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'course_id')->widget(Select2::classname(), [
        'data' => $courses, 'options' => ['data-add' => 'true', 'placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => '请输入任务名称...']) ?>

    <h5><b>开发信息</b></h5>
    
    <?= $form->field($model, 'level')->radioList(WorksystemTask::$levelName, [
        'separator'=>'',
        'itemOptions'=>[
            'labelOptions'=>[
                'style'=>[
                    'margin-right'=>'30px',
                    'margin-top' => '5px'
                ]
            ]
        ],     
    ]) ?>

    <?= $form->field($model, 'task_type_id')->dropDownList($taskTypes, ['prompt' => '请选择...', 'disabled' => 'disabled']) ?>

    <div id="add-attribute"></div>
    
    <?= $form->field($model, 'plan_end_time', [
        'template' => "{label}\n<div class=\"col-lg-4 col-md-4\">{input}</div>\n<div class=\"col-lg-4 col-md-4\">{error}</div>",
        'labelOptions' => [
                'class' => 'col-lg-1 col-md-1 control-label form-label',
                'style'=>[
                    'padding-left' => '0',
                ]
            ],
    ])->widget(DateControl::classname(),[
        'value' => $model->isNewRecord ? date('Y-m-d H:i', strtotime('+1 days')) : $model->plan_end_time, 
        'type'=> DateControl::FORMAT_DATETIME,
        'displayFormat' => 'yyyy-MM-dd H:i',
        'saveFormat' => 'yyyy-MM-dd H:i',
        'ajaxConversion'=> true,
        'autoWidget' => true,
        'readonly' => true,
        'options' => [
            'pluginOptions' => [
                'autoclose' => true,
            ],
        ],
    ]) ?>
    
    <?= $form->field($model, 'create_team', [
        'labelOptions' => [
            'style' => [
                'padding-left' => '0',
                'display' =>  count($teams) > 1 ? 'block' : 'none'
             ]
        ]
    ])->dropDownList($teams, ['style ' => count($teams) > 1 ? 'display:block' : 'display:none']) ?>
    
    <?= Html::activeHiddenInput($model, 'create_by', ['value' => Yii::$app->user->id]) ?>
    
    <h5><b>内容信息</b></h5>
    
    <div id="contentinfo"></div>
    
    <h5><b>其他信息</b></h5>

    <?= $form->field($model, 'des')->textarea(['rows' => 6, 'value' => $model->isNewRecord ? '无' : $model->des]) ?>
    
    <div class="form-group field-worksystemannex-annex">
        <label class="col-lg-1 col-md-1 control-label form-label" for="worksystemannex-annex"><?= Yii::t('rcoa/teamwork', 'Annex') ?></label>
        <div class="'col-lg-10 col-md-10">
            <?= Html::textInput('', '文件上传', [
                'id'=> 'upload',
                'class' => 'form-group',
                'type' => 'button',
                'style' => 'margin-left: 5px;margin-top: 3px;margin-bottom:5px;',
                'onclick' => 'uploadFile()'
            ]); ?>
        </div>
    </div>
    
    <div class="form-group">
        <label class="col-lg-1 col-md-1 control-label form-label"></label>
        <div id="worksystemannex" class="col-lg-10 col-md-10">
            
            <?php if(!$model->isNewRecord): ?>
            
                <?php foreach($annexs as $item): ?>
                
                <div class="col-lg-12 col-md-12" style="margin-bottom:10px; padding:0px;">
                    <div class="col-lg-12 col-md-12" style="padding:0px">
                        <?= Html::textInput('WorksystemAnnex[name][]', $item['name'], ['type' => 'text', 'class' => 'form-control']) ?>
                        <?= Html::textInput('WorksystemAnnex[path][]', $item['name'], ['type' => 'hidden']) ?>
                    </div>
                    <?= Html::img(['/filedata/teamwork/image/delete.png'], ['class' => 'form-img', 'onclick' => 'removeAnnex($(this))']) ?>
                </div>
            
                <?php endforeach; ?>
            
            <?php endif;?>
        </div>
        <div id="annex-prompt" class="col-xs-10"></div>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>

<?php
$js =   
<<<JS
    /** 下拉选择【专业/工种】 */
    $('#worksystemtask-item_id').change(function(){
        var url = "/framework/api/search?id="+$(this).val(),
            element = $('#worksystemtask-item_child_id');
        $("#worksystemtask-item_child_id").html("");
        $('#select2-worksystemtask-item_child_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        $('.field-worksystemtask-course_id').removeClass('has-error');
        $(".field-worksystemtask-course_id .help-block").text('');
        $("#worksystemtask-course_id").attr("data-add", "true");
        $("#worksystemtask-course_id").html('<option value="">全部</option>');
        $('#select2-worksystemtask-course_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        wx(url, element, '请选择...');
    });
    /** 下拉选择【课程】 */
    $('#worksystemtask-item_child_id').change(function(){
        var url = "/framework/api/search?id="+$(this).val(),
            element = $('#worksystemtask-course_id');
        $('.field-worksystemtask-course_id').removeClass('has-error');
        $(".field-worksystemtask-course_id .help-block").text('');
        $("#worksystemtask-course_id").attr("data-add", "true");
        $("#worksystemtask-course_id").html("");
        $('#select2-worksystemtask-course_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        wx(url, element, '请选择...');
    });
    /** 检查【课程】是否存在 */
    $('#worksystemtask-course_id').change(function(){
        $(this).attr("data-add", "true");
        var url = "/worksystem/task/check-exist?course_id="+$(this).val();
        setTimeout(function(){
            $.post(url,function(data)
            {
                if(data['type']){
                    $('#worksystemtask-item_type_id').find('option[value='+data['data']['item_type_id']+']').attr('selected', true);
                    $('#worksystemtask-item_type_id').parent().parent().removeClass("has-error").addClass("has-success");
                    $('#worksystemtask-item_type_id').parent().next().children().html("");
                    $('#worksystemtask-create_team').find('option[value='+data['data']['team_id']+']').attr('selected', true);
                }else{
                    if(data['isAdd'] == 0){
                        $("#worksystemtask-course_id").attr("data-add", "false");
                    }
                    $('.field-worksystemtask-course_id').removeClass("has-success").addClass('has-error');
                    $(".field-worksystemtask-course_id .help-block").text(data['message']);
                }
            });
        },100);
    });        
        
    $('#worksystemtask-plan_end_time-disp').attr('name', '');
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
        content: 'url:http://eefile.gzedu.com/upload/toUpload.do?formMap.filetype=ppt|doc|docx|xls|xlsx|pptx|txt|rar|zip|mp3|mp4|rmvb|wmv|flv|swf|3gp|jpg&formMap.filecwd=/files1/file&formMap.appId=APP015&formMap.filenum=1&formMap.origin=<?php echo WEB_ROOT?>/uploadIframe/uploadIframe.html',
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
                        fileName = [],
                        NameMD5List = [];

                if(fileList && fileList.length > 0){
                    for(var i = 0; i < fileList.length; i++){
                        filelist.push(fileList[i].FileURL);
                        fileName.push(fileList[i].CFileName);
                        NameMD5List.push(fileList[i].FileMD5);
                    }
                    var is_return = true;
                    $("#worksystemannex .form-control").each(function(index, elem){
                        if(fileName == $(this).val()){
                            $('#annex-prompt').html('<span class="error-warn">请不要重复上传相同附件！</span>');
                            is_return = false;
                        }
                    });
                    if(is_return == false)
                        return;
                    
                    $('#annex-prompt').html('');
                    var Html = '<div class="col-lg-12 col-md-12" style="margin-bottom:10px; padding:0px;"><div class="col-lg-12 col-md-12" style="padding:0px"><input type="text" class="form-control" name="WorksystemAnnex[name][]" value="'+fileName.join('')+'"><input type="hidden" name="WorksystemAnnex[path][]" value="'+filelist.join('')+'"></div><img class="form-img" src="/filedata/teamwork/image/delete.png" onclick="removeAnnex($(this))"></div>';
                    $(Html).appendTo($("#worksystemannex"));
                    
                    //$('#md5').val(NameMD5List.join(''));
                    window['FILELIST'] = [];
                }
            },
            focus : true
        }]
    });
}

/* 移除附件 */
function removeAnnex(object)
{
    $(object).parent().remove();
}
</script>

<?php
    UploadFileAsset::register($this);
?>