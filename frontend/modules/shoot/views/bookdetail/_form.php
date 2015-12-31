<?php

use common\models\shoot\ShootBookdetail;
use kartik\widgets\Growl;
use kartik\widgets\TouchSpin;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use common\models\shoot\ShootHistory;
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
    <?= $form->field($model, 'fw_college')->dropDownList($colleges,['prompt'=>'请选择...','onchange'=>'wx_one(this)',]) ?>
    
    <?= $form->field($model, 'fw_project')->dropDownList($projects,['prompt'=>'请选择...','onchange'=>'wx_two(this)',]) ?>

    <?= $form->field($model, 'fw_course')->dropDownList($courses,['prompt'=>'请选择...']) ?>

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
    <?= $form->field($model, 'teacher_name')->dropDownList($teacherName, ['prompt'=>'请选择...']) ?>
    
    <?= $form->field($model, 'teacher_phone')->dropDownList(['prompt'=>'请选择...']) ?>
    
    <?= $form->field($model, 'teacher_email')->dropDownList(['prompt'=>'请选择...']) ?>

    <h5><b>其它信息：</b></h5>
    <?= $form->field($model, 'u_contacter')->dropDownList($users,['prompt'=>'请选择...']) ?>

    <?= $form->field($model, 'u_booker')->dropDownList($users,['prompt'=>'请选择...']) ?>
    
    <?= $form->field($model, 'remark')->textarea() ?>
    
    <?= $form->field($model, 'shoot_mode')->radioList(ShootBookdetail::$shootModeMap,[
        'separator'=>'',
         'itemOptions'=>[
            'labelOptions'=>[
                'style'=>[
                     'margin-right'=>'50px'
                ]
            ]
        ],
    ]) ?>

    <?= $form->field($model, 'photograph')->checkbox()->label('') ?>
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
   $("input:radio").eq(1).attr("checked",true);
JS;
    $this->registerJs($js,  View::POS_READY); 
?> 
<script type="text/javascript">
    function wx_one(e){
        console.log($(e).val());
	$("#shootbookdetail-fw_course").html("");
	$("#shootbookdetail-fw_project").html("");
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
        $.post("/framework/api/search?id="+$(e).val(),function(data)
        {
            $('<option/>').appendTo($("#shootbookdetail-fw_course"));
            $.each(data['data'],function()
            {
                $('<option>').val(this['id']).text(this['name']).appendTo($("#shootbookdetail-fw_course"));
            });
        });
    }
</script>