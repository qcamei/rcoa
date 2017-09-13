<?php

use common\models\demand\DemandTask;
use common\widgets\uploadFile\UploadFileAsset;
use kartik\datecontrol\DateControl;
use kartik\slider\Slider;
use kartik\widgets\Select2;
use kartik\widgets\TouchSpin;
use wskeee\rbac\RbacName;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DemandTask */
/* @var $form ActiveForm */

?>

<div class="demand-task-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'demand-task-form',
            'class'=>'form-horizontal',
        ],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-10 col-md-10\">{input}</div>\n<div class=\"col-lg-10 col-md-10\">{error}</div>",  
            'labelOptions' => [
                'class' => 'col-lg-1 col-md-1 control-label',
                'style'=>[
                    'color'=>'#999999',
                    'font-weight'=>'normal',
                    'padding-right' => '0'
                ]
            ],  
        ], 
    ]); ?>

    <h5><b>基础信息</b></h5>
    
    <?= $form->field($model, 'item_type_id')->widget(Select2::classname(), [
        'data' => $itemTypes, 'options' => ['placeholder' => '请选择...']
    ]) ?>

    <?= $form->field($model, 'item_id')->widget(Select2::classname(), [
        'data' => $items, 'options' => ['placeholder' => '请选择...',]
    ]) ?>
    
    <?= $form->field($model, 'item_child_id')->widget(Select2::classname(), [
        'data' => $itemChilds, 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'course_id')->widget(Select2::classname(), [
        'data' => $courses, 'options' => ['data-add' => "true", 'placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'teacher')->widget(Select2::classname(), [
        'data' => $teachers, 'options' => ['placeholder' => '请选择...']
    ]) ?>

    <?= $form->field($model, 'lesson_time', [
        'template' => "{label}\n<div class=\"col-sm-4\">{input}</div>\n<div class=\"col-sm-4\">{error}</div>"
    ])->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '学时 ...',
            'step' => 0.1,
            'decimals' => 2,
            'min' => 0.0,
            'max' => 999999.99,
        ],
    ]) ?>
    
    <?= $form->field($model, 'credit', [
        'template' => "{label}\n<div class=\"col-sm-4\">{input}</div>\n<div class=\"col-sm-4\">{error}</div>"
    ])->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '学分 ...',
            'step' => 0.1,
            'decimals' => 2,
            'min' => 0.0,
            'max' => 999999.99,
        ],
    ]) ?>
  
    <?= $form->field($model, 'course_description')->textarea(['value' => !$model->isNewRecord ? $model->course_description : '无', 'rows' => 3]) ?>

    <h5><b>开发信息</b></h5>
        
    <?= $form->field($model, 'mode')->radioList(DemandTask::$modeName, [
        'itemOptions'=>[
            'labelOptions'=>[
                'style'=>[
                    'margin-right'=>'30px',
                    'margin-top' => '5px'
                ]
            ]
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
    
    <?= $form->field($model, 'plan_check_harvest_time', [
        'template' => "{label}\n<div class=\"col-lg-4 col-md-4\">{input}</div>\n<div class=\"col-lg-4 col-md-4\">{error}</div>",
        'labelOptions' => [
                'class' => 'col-lg-1 col-md-1 control-label form-label',
                'style'=>[
                    'padding-left' => '0',
                ]
            ],
    ])->widget(DateControl::classname(),[
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
    
    <?= $form->field($model, 'bonus_proportion', [
        'template' => "{label}\n<div class=\"col-sm-2\">{input}</div>\n<div class=\"col-sm-2\">{error}</div>"
    ])->widget(Slider::classname(), [
        'value' => $model->bonus_proportion,
        'sliderColor'=>Slider::TYPE_INFO,
        'handleColor'=>Slider::TYPE_PRIMARY,
        'pluginOptions'=>[
            'min' => 0.05,
            'max'=> 0.1,
            'precision'=>2,
            'handle'=>'square',
            'step'=>0.01,
            'tooltip'=>'always',
            'formatter'=>new JsExpression("function(val) { 
                return Math.round(val * 100) + '%';
            }")
        ],
    ]); ?>
        
    <h5><b>工作项信息</b></h5>
        
    <?= $this->render('/workitem/index', [
        'dt_model' => $model,
        'workitmType' => $workitmType,
        'workitem' => $workitem,
    ]) ?>
        
    <?= $form->field($model, 'external_budget_cost', [
        'labelOptions' => ['style' => 'padding-left:0px; padding-right:0px;color: #999999;font-weight: normal;'],
        'template' => "{label}\n<div class=\"col-sm-4\">{input}</div>\n<div class=\"col-sm-4\">{error}</div>"
    ])->textInput(['type' => 'number', 'min' => 0]) ?>
    
    <h5><b>其他信息</b></h5>
    
    <?= $form->field($model, 'des')->textarea(['value' => !$model->isNewRecord ? $model->des : '无', 'rows' => 4]) ?>
    
    <div class="form-group field-demandtaskannex-annex">
        <label class="col-lg-1 col-md-1 control-label form-label" for="demandtaskannex-annex"><?= Yii::t('rcoa/teamwork', 'Annex') ?></label>
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
        <div id="demandtaskannex" class="col-lg-10 col-md-10">
            
            <?php if(!$model->isNewRecord): ?>
            
                <?php foreach($annexs as $item): ?>
                
                <div class="col-lg-12 col-md-12" style="margin-bottom:10px; padding:0px;">
                    <div class="col-lg-12 col-md-12" style="padding:0px">
                        <?= Html::textInput('DemandTaskAnnex[name][]', $item['name'], ['type' => 'text', 'class' => 'form-control']) ?>
                        <?= Html::textInput('DemandTaskAnnex[path][]', $item['name'], ['type' => 'hidden']) ?>
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
    $('#demandtask-item_id').change(function(){
        var url = "/framework/api/search?id="+$(this).val(),
            element = $('#demandtask-item_child_id');
        $("#demandtask-item_child_id").html("");
        $('#select2-demandtask-item_child_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        $('.field-demandtask-course_id').removeClass('has-error');
        $(".field-demandtask-course_id .help-block").text('');
        $("#demandtask-course_id").attr("data-add", "true");
        $("#demandtask-course_id").html("");
        $('#select2-demandtask-course_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        wx(url, element, '请选择...');
    });
    /** 下拉选择【课程】 */
    $('#demandtask-item_child_id').change(function(){
        var url = "/demand/task/search-select?id="+$(this).val(),
            element = $('#demandtask-course_id');
        $('.field-demandtask-course_id').removeClass('has-error');
        $(".field-demandtask-course_id .help-block").text('');
        $("#demandtask-course_id").attr("data-add", "true");
        $("#demandtask-course_id").html("");
        $('#select2-demandtask-course_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        wx(url, element, '请选择...');
    });
    /** 检查【课程】是否唯一 */
    $('#demandtask-course_id').change(function(){
        $("#demandtask-course_id").attr("data-add", "true");
        $('.field-demandtask-course_id').removeClass("has-error").addClass('has-success');
        $(".field-demandtask-course_id .help-block").text("");
        $.post("/demand/task/check-unique?id="+$(this).val(),function(data){
            if(data['type'] == 1){
                $("#demandtask-course_id").attr("data-add", "false");
                $('.field-demandtask-course_id').removeClass("has-success").addClass('has-error');
                $(".field-demandtask-course_id .help-block").text(data['message']);
            }
        });
    });
    $("#demandtask-plan_check_harvest_time-disp").attr('name', '');
        
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
                    $("#demandtaskannex .form-control").each(function(index, elem){
                        if(fileName == $(this).val()){
                            $('#annex-prompt').html('<span class="error-warn">请不要重复上传相同附件！</span>');
                            is_return = false;
                        }
                    });
                    if(is_return == false)
                        return;
                    
                    $('#annex-prompt').html('');
                    var Html = '<div class="col-lg-12 col-md-12" style="margin-bottom:10px; padding:0px;"><div class="col-lg-12 col-md-12" style="padding:0px"><input type="text" class="form-control" name="DemandTaskAnnex[name][]" value="'+fileName.join('')+'"><input type="hidden" name="DemandTaskAnnex[path][]" value="'+filelist.join('')+'"></div><img class="form-img" src="/filedata/teamwork/image/delete.png" onclick="removeAnnex($(this))"></div>';
                    $(Html).appendTo($("#demandtaskannex"));
                    
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