<?php

use common\models\mconline\McbsCourse;
use kartik\widgets\Select2;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model McbsCourse */
/* @var $form ActiveForm */
?>

<div class="mcbs-course-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'mcbs-form',
            'class'=>'form-horizontal',
        ],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-10 col-md-10\">{input}</div>\n<div class=\"col-lg-10 col-md-10\">{error}</div>",  
            'labelOptions' => [
                'class' => 'col-lg-1 col-md-1 control-label form-label',
            ],  
        ], 
    ]); ?>

    <h5><b></b></h5>
    
    <?= $form->field($model, 'item_type_id')->widget(Select2::classname(), [
        'data' => $itemTypes, 'options' => ['placeholder' => '请选择...',]
    ]) ?>
    
    <?= $form->field($model, 'item_id')->widget(Select2::classname(), [
        'data' => $items, 'options' => ['placeholder' => '请选择...',]
    ]) ?>
    
    <?= $form->field($model, 'item_child_id')->widget(Select2::classname(), [
        'data' => $itemChilds, 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'course_id')->widget(Select2::classname(), [
        'data' => $courses, 'options' => ['data-add' => 'true', 'placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'des')->textarea(['rows' => 6, 'value' => $model->isNewRecord ? '无' : $model->des]) ?>

    <?= Html::activeHiddenInput($model, 'id') ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = 
<<<JS
        
     /** 下拉选择【专业/工种】 */
    $('#mcbscourse-item_id').change(function(){
        $("#mcbscourse-item_child_id").html("");
        $('#select2-mcbscourse-item_child_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        $('.field-mcbscourse-course_id').removeClass('has-error');
        $(".field-mcbscourse-course_id .help-block").text('');
        $("#mcbscourse-course_id").html('<option value="">全部</option>');
        $('#select2-mcbscourse-course_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        $.post("/framework/api/search?id="+$(this).val(),function(data)
        {
            $('<option/>').val('').text(this['name']).appendTo($('#mcbscourse-item_child_id'));
            $.each(data['data'],function()
            {
                $('<option>').val(this['id']).text(this['name']).appendTo($('#mcbscourse-item_child_id'));
            });
        });
    });
    /** 下拉选择【课程】 */
    $('#mcbscourse-item_child_id').change(function(){
        $('.field-mcbscourse-course_id').removeClass('has-error');
        $(".field-mcbscourse-course_id .help-block").text('');
        $("#mcbscourse-course_id").attr("data-add", "true");
        $("#mcbscourse-course_id").html("");
        $('#select2-mcbscourse-course_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        $.post("/framework/api/search?id="+$(this).val(),function(data)
        {
            $('<option/>').val('').text(this['name']).appendTo($('#mcbscourse-course_id'));
            $.each(data['data'],function()
            {
                $('<option>').val(this['id']).text(this['name']).appendTo($('#mcbscourse-course_id'));
            });
        });
    });   
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>