<?php

use common\models\teamwork\CourseManage;
use common\widgets\uploadFile\UploadFileAsset;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;
use kartik\widgets\TouchSpin;
use wskeee\utils\DateUtil;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;



/* @var $this View */
/* @var $model CourseManage */
/* @var $form ActiveForm */

?>

<div class="course-manage-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'course-manage-form',
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
    
    <?= $form->field($model, 'project.item_type_id')->textInput([
        'value' => !empty($model->project->item_type_id) ? $model->project->itemType->name : '', 'disabled' => 'disabled'
    ]) ?>
    
    <?= $form->field($model, 'project.item_id')->textInput([
        'value' => !empty($model->project->item_id) ? $model->project->item->name : '', 'disabled' => 'disabled'
    ]) ?>
    
    <?= $form->field($model, 'project.item_child_id')->textInput([
        'value' => !empty($model->project->item_child_id) ? $model->project->itemChild->name : '', 'disabled' => 'disabled'
    ]) ?>
    
    <?= $form->field($model, 'course_id')->widget(Select2::classname(), [
        'data' => $courses, 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'teacher')->widget(Select2::classname(), [
        'data' => $teachers, 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'credit')->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '学分 ...',
            'min' => 0,
            'max' => 1000000000000000,
        ],
    ]) ?>
    
    <?= $form->field($model, 'lession_time')->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '学时 ...',
            'min' => 0,
            'max' => 1000000000000000,
            
        ],
    ]) ?>
    
    
    <?= $form->field($model, 'video_length')->textInput(['value'=>  DateUtil::intToTime($model->video_length)])->hint('aaaa')?>
    
    
    <?= $form->field($model, 'question_mete')->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '题量 ...',
            'min' => 0,
            'max' => 1000000000000000,
        ],
    ]) ?>
    
    <?= $form->field($model, 'case_number')->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '案例数...',
            'min' => 0,
            'max' => 1000000000000000,
        ],
    ]) ?>
    
    <?= $form->field($model, 'activity_number')->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '活动数 ...',
            'min' => 0,
            'max' => 1000000000000000,
        ],
    ]) ?>
    
    <h5><b>开发信息</b></h5>
    
    <?php
        if(is_array($team)){
            echo $form->field($model, 'team_id')->widget(Select2::classname(), [
                'id' => 'coursemanage-team_id', 'data' => $team, 'options' => ['placeholder' => '请选择...']
            ]);
        }
        else{
            echo Html::hiddenInput('CourseManage[team_id]', $team);
        }
    ?>  
    
    <?php
        echo Html::beginTag('div', ['class' => 'form-group field-courseproducer-producer has-success']);
             echo Html::beginTag('label', [
                 'class' => 'col-lg-1 col-md-1 control-label',
                 'style' => 'color: #999999; font-weight: normal; padding-right:0;padding-left:10px;',
                 'for' => 'courseproducer-producer'
                ]).Yii::t('rcoa/teamwork', 'Resource People').Html::endTag('label');
             echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']);
                echo Select2::widget([
                    'id' => 'courseproducer-producer',
                    //'maintainOrder' => true,
                    'name' => 'producer',
                    'value' => is_array($team) ? '' : array_keys($producer),
                    'data' => $producerList,
                    'options' => [
                        'placeholder' => '请选择...',
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        //'templateResult' => new JsExpression('format'),
                        //'templateSelection' => new JsExpression('format'),
                        'escapeMarkup' => new JsExpression("function(m) { return m; }"),
                        'allowClear' => true
                    ],
                    'pluginEvents' => [
                        'change' => "function() { log($(this)); }",
                    ],
                ]);
                
             echo Html::endTag('div');           
             echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']).Html::beginTag('div', ['class' => 'help-block']).
                    Html::endTag('div').Html::endTag('div');
        echo Html::endTag('div');
    ?>
    
    <?= $form->field($model, 'weekly_editors_people')->widget(Select2::classname(), [
        'data' => is_array($team) ? [] : $weeklyEditors, 'options' => ['placeholder' => '请选择...']
    ]) ?> 
    
    <?= $form->field($model, 'course_ops')->widget(Select2::classname(), [
        'data' => $producerList, 'options' => ['placeholder' => '请选择...']
    ]) ?>    
   
    <?php
        echo Html::beginTag('div', ['class' => 'form-group field-coursemanage-plan_start_time has-success']);
            echo Html::beginTag('label', [
                    'class' => 'col-lg-1 col-md-1 control-label', 
                    'style' => 'color: #999999; font-weight: normal; padding-right:0;padding-left:10px;',
                    'for' => 'coursemanage-plan_start_time'
                ]).Yii::t('rcoa/teamwork', 'Plan Start Time').Html::endTag('label');
            echo Html::beginTag('div', ['class' => 'col-sm-4']);
                echo DateControl::widget([
                    'name' => 'CourseManage[plan_start_time]',
                    'value' => $model->isNewRecord ? date('Y-m-d H:i', strtotime('+1 days')) : $model->plan_start_time, 
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
    
    <?php
        echo Html::beginTag('div', ['class' => 'form-group field-coursemanage-plan_end_time has-success']);
            echo Html::beginTag('label', [
                    'class' => 'col-lg-1 col-md-1 control-label', 
                    'style' => 'color: #999999; font-weight: normal; padding-right:0;padding-left:10px;',
                    'for' => 'coursemanage-plan_end_time'
                ]).Yii::t('rcoa/teamwork', 'Plan End Time').Html::endTag('label');
            echo Html::beginTag('div', ['class' => 'col-sm-4']);
                echo DateControl::widget([
                    'name' => 'CourseManage[plan_end_time]',
                    'value' => $model->isNewRecord ? date('Y-m-d H:i', strtotime('+3 days')) : $model->plan_end_time, 
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
    
    <?= $form->field($model, 'path')->textInput(['placeholder' => '课程存储服务器路径...']) ?>
    
    <h5><b>其他信息</b></h5>
    
    <?= $form->field($model, 'des')->textarea(['rows' => 4]) ?>
    
    <?php
        //附件上传按钮
        echo Html::beginTag('div', ['class' => 'form-group field-courseannex-annex', 'style' => 'margin-bottom:5px;']);
            echo Html::beginTag('label', [
                'class' => 'col-lg-1 col-md-1 control-label',
                'style' => 'color: #999999; font-weight: normal; padding-right: 0;',
                'for' => 'courseannex-annex',
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
            /*echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']).Html::beginTag('div', ['class' => 'help-block'])
                .Html::endTag('div').Html::endTag('div');*/
        echo Html::endTag('div');
        
        //附件上传输入框
        echo Html::beginTag('div', ['class' => 'form-group']);
            echo Html::beginTag('label', [
                'class' => 'col-lg-1 col-md-1 control-label',
                'style' => 'color: #999999; font-weight: normal; padding-right: 0;',
            ]).Html::endTag('label');
            echo Html::beginTag('div', ['id' => 'courseannex', 'class' => 'col-lg-10 col-md-10']);
                if(!$model->isNewRecord){
                    foreach ($annex as $value) {
                        echo  Html::textInput('CourseAnnex[name][]', $value->name, [
                            'type' => 'text',
                            'class' => 'form-control col-lg-12 col-md-11 col-sm-10 col-xs-9',
                        ]).Html::img(['/filedata/teamwork/image/delete.png'], [
                            'class' => 'form-img', 
                            'onclick' => 'deleteAnnex($(this))',
                        ]);
                        echo Html::hiddenInput('CourseAnnex[path][]', $value->path);
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
/*$url = Yii::$app->urlManager->baseUrl . '/images/flags/';
$format = 
<<< SCRIPT
    function format(state) {
        if (!state.id) return state.text; // optgroup
        src = '$url' +  state.id.toLowerCase() + '.png'
        return '<img class="flag" src="' + src + '"/>' + state.text;
    }
SCRIPT;
    //$this->registerJs($format, View::POS_HEAD);*/  
$sourceProducers = [];  
    foreach ($producerList as $teams){  
        foreach($teams as $id=>$name){  
            $sourceProducers[$id]=$name;  
        }  
    }  
$sourceProducers = json_encode($sourceProducers);
$js =   
<<<JS
    /*$('#coursemanage-team_id').change(function(){
        var li = '<li class="select2-search select2-search--inline">'
            +'<input class="select2-search__field" type="search" style="width: 943px;" placeholder="请选择..."></li>',
            option = '<option value="">请选择...</option>',
            span = '<span class="select2-selection__placeholder">请选择...</span>';
        $('.select2-selection--multiple ul').html(li);
        $('#select2-courseproducer-producer-results .select2-results__option').attr('aria-selected', 'false');
        $("#coursemanage-weekly_editors_people").html(option);
        $('#select2-coursemanage-weekly_editors_people-container').html(span);
    });*/ 
        
    var sourceProducers = $sourceProducers; 
    function log(value){   
        var hasSelected = $("#coursemanage-weekly_editors_people").val();    
        $("#coursemanage-weekly_editors_people").html("");    
        var producers = $(value).val();
        for(var i=0,len=producers.length;i<len;i++){    
            $('<option>').val(producers[i]).text(sourceProducers[producers[i]]).appendTo($("#coursemanage-weekly_editors_people"));     
        }    
        
        if(producers.indexOf(hasSelected)!=-1)    
            $("#coursemanage-weekly_editors_people").val(hasSelected);    
        else{  
            $("#coursemanage-weekly_editors_people").val("");  
            $("#select2-coursemanage-weekly_editors_people-container").html("请选择...");  
        }  
    }
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
        content: 'url:http://eefile.gzedu.com/upload/toUpload.do?formMap.filetype=ppt|doc|docx|xls|xlsx|pptx|txt|rar|zip|mp3|mp4|rmvb|wmv|flv|swf|3gp|jpg&formMap.filecwd=/files1/file&formMap.appId=APP015&formMap.filenum=1&formMap.origin=http://ccoa.gzedu.net/uploadIframe/uploadIframe.html',
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
                        var inputText = '<input type="text" name="CourseAnnex[name][]"class="form-control col-lg-12 col-md-11 col-sm-10 col-xs-9">'+
                            '<img class="form-img" src="/filedata/teamwork/image/delete.png" onclick="deleteAnnex($(this))">';
                        var inputHidden = '<input type="hidden" name="CourseAnnex[path][]">';
                        if(i == 0){
                            $(inputText).val(fileName.join('')).appendTo($("#courseannex"));
                            $("#courseannex").append($(inputHidden).val(filelist.join('')));
                        }
                        else{
                            $(inputText).val(fileName.join('')).after($("#courseannex-path"));
                            $(inputHidden).val(filelist.join('')).after($("#courseannex-name"));
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
function deleteAnnex(object){
    $(object).prev().remove();
    $(object).next().remove();
    $(object).remove();
}
</script>

<?php
    UploadFileAsset::register($this);
?>