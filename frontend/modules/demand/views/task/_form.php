<?php

use common\models\demand\DemandTask;
use common\widgets\uploadFile\UploadFileAsset;
use kartik\datecontrol\DateControl;
use kartik\slider\Slider;
use kartik\widgets\Select2;
use kartik\widgets\TouchSpin;
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
        'data' => $courses, 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'teacher')->widget(Select2::classname(), [
        'data' => $teachers, 'options' => ['placeholder' => '请选择...']
    ]) ?>

    <?= $form->field($model, 'lesson_time', [
        'template' => "{label}\n<div class=\"col-sm-4\">{input}</div>\n<div class=\"col-sm-4\">{error}</div>"
    ])->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '学时 ...',
            'min' => 0,
            'max' => 999999,
        ],
    ]) ?>
    
    <?= $form->field($model, 'credit', [
        'template' => "{label}\n<div class=\"col-sm-4\">{input}</div>\n<div class=\"col-sm-4\">{error}</div>"
    ])->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '学分 ...',
            'min' => 0,
            'max' => 999999,
        ],
    ]) ?>
  
    <?= $form->field($model, 'course_description')->textarea(['value' => '无', 'rows' => 3]) ?>

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

    <?php
        if(is_array($team)){
            echo $form->field($model, 'create_team')->widget(Select2::classname(), [
                'id' => 'demandtask-create_team', 'data' => $team, 'options' => ['placeholder' => '请选择...']
            ]);
        }
        else{
            echo Html::activeHiddenInput($model, 'create_team', ['value' => $team]);
        }
    ?> 
    
    <?php
        echo Html::beginTag('div', ['class' => 'form-group field-demandtask-plan_check_harvest_time has-success']);
            echo Html::beginTag('label', [
                    'class' => 'col-lg-1 col-md-1 control-label', 
                    'style' => 'color: #999999; font-weight: normal; padding-right:0;padding-left:10px;',
                    'for' => 'demandtask-plan_check_harvest_time'
                ]).Yii::t('rcoa/demand', 'Check Harvest Time').Html::endTag('label');
            echo Html::beginTag('div', ['class' => 'col-sm-4']);
                echo DateControl::widget([
                    'name' => 'DemandTask[plan_check_harvest_time]',
                    'value' => $model->isNewRecord ? date('Y-m-d H:i', strtotime('+1 days')) : $model->plan_check_harvest_time, 
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
                ]);
                echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']).Html::beginTag('div', ['class' => 'help-block']).Html::endTag('div').Html::endTag('div');
            echo Html::endTag('div');
        echo Html::endTag('div');
    ?>
    
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
    
    <?php if(!$model->isNewRecord): ?>
    <h5><b>工作项信息</b></h5>
    
    <?= $this->render('/workitem/index', [
        'allModels' => $model->demandWorkitems
    ]) ?>
    
    <?php endif; ?>
    
    <h5><b>其他信息</b></h5>
    
    <?= $form->field($model, 'des')->textarea(['value' => !$model->isNewRecord ? $model->des : '无', 'rows' => 4]) ?>
    
    <?php
        //附件上传按钮
        echo Html::beginTag('div', ['class' => 'form-group field-demandtaskannex-annex', 'style' => 'margin-bottom:5px;']);
            echo Html::beginTag('label', [
                'class' => 'col-lg-1 col-md-1 control-label',
                'style' => 'color: #999999; font-weight: normal; padding-right: 0;',
                'for' => 'demandtaskannex-annex',
            ]).Yii::t('rcoa/teamwork', 'Annex').Html::endTag('label');
            echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']);
                echo Html::textInput('', '文件上传', [
                    'id'=> 'upload',
                    'class' => 'form-group',
                    'type' => 'button',
                    'style' => 'margin-left: 5px;margin-top: 3px;margin-bottom:5px;',
                    'onclick' => 'uploadFile()'
                ]);
            echo Html::endTag('div');
        echo Html::endTag('div');
        
        //附件上传输入框
        echo Html::beginTag('div', ['class' => 'form-group']);
            echo Html::beginTag('label', [
                'class' => 'col-lg-1 col-md-1 control-label',
                'style' => 'color: #999999; font-weight: normal; padding-right: 0;',
            ]).Html::endTag('label');
            echo Html::beginTag('div', ['id' => 'demandtaskannex', 'class' => 'col-lg-10 col-md-10']);
                if(!$model->isNewRecord){
                    foreach ($annex as $value) {
                        echo  Html::textInput('DemandTaskAnnex[name][]', $value->name, [
                            'type' => 'text',
                            'class' => 'form-control col-lg-12 col-md-11 col-sm-10 col-xs-9',
                        ]).Html::img(['/filedata/teamwork/image/delete.png'], [
                            'class' => 'form-img', 
                            'onclick' => 'deleteAnnex($(this))',
                        ]);
                        echo Html::hiddenInput('DemandTaskAnnex[path][]', $value->path);
                    }
                }
            echo Html::endTag('div');
            echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']).Html::beginTag('div', ['class' => 'help-block'])
                .Html::endTag('div').Html::endTag('div');
        echo Html::endTag('div');
    ?>
        
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
        $("#demandtask-course_id").html("");
        $('#select2-demandtask-course_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        wx(url, element, '请选择...');
    });
    /** 下拉选择【课程】 */
    $('#demandtask-item_child_id').change(function(){
        var url = "/demand/task/search-select?id="+$(this).val(),
            element = $('#demandtask-course_id');
        $("#demandtask-course_id").html("");
        $('#select2-demandtask-course_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        wx(url, element, '请选择...');
    });
    
   /** 滚动到添加课程产品处 */
    if($sign)
        $('html,body').animate({scrollTop:($('.demand-workitem-index').offset().top) - 140},1000);
    
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
                    for(var i = 0; i < fileList.length; i++){
                        var inputText = '<input type="text" name="DemandTaskAnnex[name][]"class="form-control col-lg-12 col-md-11 col-sm-10 col-xs-9">'+
                            '<img class="form-img" src="/filedata/teamwork/image/delete.png" onclick="deleteAnnex($(this))">';
                        var inputHidden = '<input type="hidden" name="DemandTaskAnnex[path][]">';
                        if(i == 0){
                            $(inputText).val(fileName.join('')).appendTo($("#demandtaskannex"));
                            $("#demandtaskannex").append($(inputHidden).val(filelist.join('')));
                        }
                        else{
                            $(inputText).val(fileName.join('')).after($("#demandtaskannex-path"));
                            $(inputHidden).val(filelist.join('')).after($("#demandtaskannex-name"));
                        }
                        //$('#md5').val(NameMD5List.join(''));
                    }
                    window['FILELIST'] = [];
                }
            },
            focus : true
        }]
    });
}
/* 移除附件 */
function deleteAnnex(object){
    $(object).prev().remove();
    $(object).next().remove();
    $(object).remove();
}
</script>

<?php
    UploadFileAsset::register($this);
?>