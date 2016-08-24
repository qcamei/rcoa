<?php

use common\models\shoot\ShootBookdetail;
use kartik\widgets\Growl;
use kartik\widgets\Select2;
use kartik\widgets\TouchSpin;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
/* @var $this View */
/* @var $model frontend\modules\shoot\models\ShootBookdetail */
/* @var $form ActiveForm */
/* @var $model ShootBookdetail */
?>

<div class="shoot-bookdetail-form">

    <?php
    //在设置更新时不显示锁定时间
   if($model->status == $model::STATUS_BOOKING) {
         echo Growl::widget([
            'type' => Growl::TYPE_WARNING,
            'body' => '锁定时间 2 分钟',
            'showSeparator' => true,
            'delay' => 0,
            'pluginOptions' => [
                'offset'=> [
                        'x'=> 0,
                        'y'=> 0
                ],
                'delay' => 2*60*1000,
                'showProgressbar' => true,
                'placement' => [
                    'from' => 'top',
                    'align' => 'center',
                ]
            ]
        ]);
    }
      
    ?>
    <?php $form = ActiveForm::begin([
        'id'=>'bookdetail-create-form',
        'options'=>['class'=>'form-horizontal'],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-10 col-md-10\">{input}</div>\n<div class=\"col-lg-10 col-md-10\">{error}</div>",  
            'labelOptions' => ['class' => 'col-lg-1 col-md-1 control-label','style'=>['color'=>'#999999','font-weight'=>'normal']],  
        ], 
        ]); ?>
    
    <h5><b>课程信息：</b></h5>
    <?= $form->field($model, 'business_id')->dropDownList($business,['prompt'=>'请选择...',]) ?>
    
    <?= $form->field($model, 'fw_college')->widget(Select2::classname(), [
        'data' => $colleges, 'options' => ['placeholder' => '请选择...', 'onchange'=>'wx_one(this)']
    ]) ?>
    
    <?= $form->field($model, 'fw_project')->widget(Select2::classname(), [
        'data' => $projects, 'options' => ['placeholder' => '请选择...', 'onchange'=>'wx_two(this)']
    ]) ?>

    <?= $form->field($model, 'fw_course')->widget(Select2::classname(), [
        'data' => $courses, 'options' => ['placeholder' => '请选择...',]
    ])?>

    <?= $form->field($model, 'lession_time')->widget(TouchSpin::classname(),  [
            'readonly' => true,
            'pluginOptions' => [
                'placeholder' => '课时 ...',
                'min' => 1,
                'max' => 5,
            ],
        ])?>
   
    <?= $form->field($model, 'start_time')->textInput(['type'=>'time']) ?>

    <h5><b>老师信息：</b></h5>
    <?= $form->field($model, 'u_teacher')->widget(Select2::classname(), [
        'data' => $teachers, 'options' => ['placeholder' => '请选择...','onchange'=>'wx_three(this)']
    ])?>
    
    <?= Html::img($model->isNewRecord || !$model->getIsAssign() ? null : $model->teacher->personal_image, ['width' => '128',])?>
    
    <?= $form->field($model, 'teacher_phone')->textInput([
        'value' => $model->isNewRecord || !$model->getIsAssign() ? null : $model->teacher->user->phone, 
        'disabled' => 'disabled'
    ]) ?>
    
    <?= $form->field($model, 'teacher_email')->textInput([
        'value' => $model->isNewRecord || !$model->getIsAssign() ? null : $model->teacher->user->email , 
        'disabled' => 'disabled'
    ]) ?>
    
    <h5><b>其它信息：</b></h5>
    
    <?= $form->field($model, 'u_booker')->dropDownList($bookers, ['prompt'=>'请选择...']) ?>
    
    <?= $form->field($model, 'u_contacter')->widget(Select2::classname(), [
        'value' => !$model->getIsValid() ?  '' : $contactsKey,
        'data' => !$model->getIsValid() ? $contacts : ArrayHelper::merge($alreadyContacts, $contacts), //合并两个数组
        //'size' => 'lg',
        'maintainOrder' => true,    //无序排列
        'hideSearch' => true,
        'options' => [
            'placeholder' => '选择接洽人...',
            'multiple' => true,     //设置多选
        ],
        'toggleAllSettings' => [
            'selectLabel' => '<i class="glyphicon glyphicon-ok-circle"></i> 添加全部',
            'unselectLabel' => '<i class="glyphicon glyphicon-remove-circle"></i> 取消全部',
            'selectOptions' => ['class' => 'text-success'],
            'unselectOptions' => ['class' => 'text-danger'],
        ],
        'pluginOptions' => [
            'tags' => false,
            'maximumInputLength' => 10,
            'allowClear' => true,
        ],
        'pluginEvents' => [
            'change' => 'function(){ select2Log();}'
        ]
    ])?>
    
    <?= $form->field($model, 'remark')->textarea() ?>
    
    <?= $form->field($model, 'content_type')->radioList(ShootBookdetail::$contentTypeMap, [
        'separator'=>'',
         'itemOptions'=>[
            'labelOptions'=>[
                'style'=>[
                     'margin-right'=>'30px'
                ]
            ]
        ],
    ]) ?>

    <?= $form->field($model, 'photograph')->checkbox()->label('') ?>
    
    <!--隐藏的字段属性-->
    <?= Html::activeHiddenInput($model, 'ver') ?>
    <?= Html::activeHiddenInput($model, 'site_id') ?>
    <?= Html::activeHiddenInput($model, 'book_time') ?>
    <?= Html::activeHiddenInput($model, 'index') ?>
    <?= Html::activeHiddenInput($model, 'status') ?>
    <?= Html::hiddenInput('editreason') ?>
    <?= Html::hiddenInput('b_id',$model->id) ?>
   
    
    <?php ActiveForm::end(); ?>

</div>
<?php
  
$js =   
<<<JS
    $(document).ready(function(){ 
         var htmlImg = '<div class="form-group"><label class="col-lg-1 col-md-1 control-label" style="color: #999999; font-weight: normal;">形象</label><div class="col-lg-10 col-md-10"><div id="img" style="border:2px solid #999999; width:140px; padding:4px;"></div></div></div>';
         $('#bookdetail-create-form img').before(htmlImg);
         $('#bookdetail-create-form img').appendTo('#img');
    }); 
    var isNewRecord = "$model->isNewRecord";
    if(isNewRecord)   
        $("input:radio").eq(1).attr("checked",true);
         
    $(".select2-selection__rendered li").prev(".select2-selection__choice").eq(0).css({border:"1px solid blue"});
    
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 
<script type="text/javascript">
    function wx_one(e){
        //console.log($(e).val());
	$("#shootbookdetail-fw_course").html("");
	$("#shootbookdetail-fw_project").html("");
        $("#select2-shootbookdetail-fw_project-container").html("");
	$("#select2-shootbookdetail-fw_course-container").html("");
	$.post("/framework/api/search?id="+$(e).val(),function(data)
        {
            $('<option/>').appendTo($("#shootbookdetail-fw_project"));
            $.each(data['data'],function()
            {
                $('<option>').val(this['id']).text(this['name']).appendTo($("#shootbookdetail-fw_project"));
            });
	});
    }
    function wx_two(e){
        $("#shootbookdetail-fw_course").html("");
        $("#select2-shootbookdetail-fw_course-container").html("");
        $.post("/framework/api/search?id="+$(e).val(),function(data)
        {
            $('<option/>').appendTo($("#shootbookdetail-fw_course"));
            $.each(data['data'],function()
            {
                $('<option>').val(this['id']).text(this['name']).appendTo($("#shootbookdetail-fw_course"));
            });
        });
    }
    function wx_three(e){
        //console.log($(e).val());
	$.post("/expert/default/search?id="+$(e).val(),function(data)
        {
            $('#shootbookdetail-teacher_phone').val(data.data.phone);
            $('#shootbookdetail-teacher_email').val(data.data.email);
            $('#bookdetail-create-form img').attr('src',data.data.img);
	});
    }
    function select2Log(){
        $(".select2-selection__rendered li").prev(".select2-selection__choice").eq(0).css({border:"1px solid blue"});
    } 
    
</script>