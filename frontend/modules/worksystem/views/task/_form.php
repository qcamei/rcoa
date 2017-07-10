<?php

use common\models\worksystem\WorksystemTask;
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
    
    <?= $form->field($model, 'item_type_id')->dropDownList($itemTypes, ['prompt' => '请选择...']) ?>

    <?= $form->field($model, 'item_id')->widget(Select2::classname(), [
        'data' => $items, 'options' => ['placeholder' => '请选择...',]
    ]) ?>
    
    <?= $form->field($model, 'item_child_id')->widget(Select2::classname(), [
        'data' => $itemChilds, 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'course_id')->widget(Select2::classname(), [
        'data' => $courses, 'options' => ['placeholder' => '请选择...']
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
    <?= Html::activeHiddenInput($model, 'task_type_id', ['id' => 'task_type_id-worksystemtask-task_type_id']) ?>

    <div id="add-attribute"></div>
    
    <?= $form->field($model, 'plan_end_time', [
        'template' => "{label}\n<div class=\"col-lg-4 col-md-4\">{input}</div>\n<div class=\"col-lg-4 col-md-4\">{error}</div>",
        'labelOptions' => [
                'class' => 'col-lg-1 col-md-1 control-label',
                'style'=>[
                    'color'=>'#999999',
                    'font-weight'=>'normal',
                    'padding-left' => '0',
                    'padding-right' => '0',
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

    <?php if(count($teams) == 1)
            echo Html::activeHiddenInput($model, 'external_team', ['value' => $teams[0]]);
        else
            echo $form->field($model, 'external_team')->dropDownList($teams);
    ?>
    
    <?php if(count($teams) == 1)
            echo Html::activeHiddenInput($model, 'create_team', ['value' => $teams[0]]);
        else
            echo $form->field($model, 'create_team')->dropDownList($teams);
    ?>
    
    <?= Html::activeHiddenInput($model, 'create_by', ['value' => Yii::$app->user->id]) ?>
    
    <h5><b>内容信息</b></h5>
    
    <div id="contentinfo"></div>
    
    <h5><b>其他信息</b></h5>

    <?= $form->field($model, 'des')->textarea(['rows' => 6, 'value' => $model->isNewRecord ? '无' : $model->des]) ?>

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
        $("#worksystemtask-course_id").html("");
        $('#select2-worksystemtask-course_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        wx(url, element, '请选择...');
    });
    /** 检查【课程】是否存在 */
    $('#worksystemtask-course_id').change(function(){
        var url = "/worksystem/task/check-exist?course_id="+$(this).val();
        $.post(url,function(data)
        {
            if(data['type'] == 1){
               $('.field-worksystemtask-course_id').addClass('has-error').removeClass("has-success");
               $(".field-worksystemtask-course_id .help-block").text(data['message']);
            }else{
                $('#worksystemtask-item_type_id').find('option[value='+data['data']['item_type_id']+']').attr('selected', true);
                if(data['data']['team_id'] != '')
                    $('#worksystemtask-create_team').find('option[value='+data['data']['team_id']+']').attr('selected', true);      
            }
        });
    });        
        
    $('#worksystemtask-plan_end_time-disp').attr('name', '');
JS;
    $this->registerJs($js,  View::POS_READY);
?>