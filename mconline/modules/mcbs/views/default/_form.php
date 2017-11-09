<?php

use common\models\mconline\McbsCourse;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model McbsCourse */
/* @var $form ActiveForm */
?>

<div class="mcbs-course-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_type_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'item_child_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'course_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'create_by')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'is_publish')->textInput() ?>

    <?= $form->field($model, 'publish_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'close_time')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'des')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_at')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'updated_at')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = 
<<<JS
        
     /** 下拉选择【专业/工种】 */
    $('#worksystemtask-item_id').change(function(){
        $("#worksystemtask-item_child_id").html("");
        $('#select2-worksystemtask-item_child_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        $('.field-worksystemtask-course_id').removeClass('has-error');
        $(".field-worksystemtask-course_id .help-block").text('');
        $("#worksystemtask-course_id").attr("data-add", "true");
        $("#worksystemtask-course_id").html('<option value="">全部</option>');
        $('#select2-worksystemtask-course_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        $.post("/framework/api/search?id="+$(this).val(),function(data)
        {
            $('<option/>').val('').text(text).appendTo(element);
            $.each(data['data'],function()
            {
                $('<option>').val(this['id']).text(this['name']).appendTo($('#worksystemtask-item_child_id'));
            });
        });
    });
    /** 下拉选择【课程】 */
    $('#worksystemtask-item_child_id').change(function(){
        $('.field-worksystemtask-course_id').removeClass('has-error');
        $(".field-worksystemtask-course_id .help-block").text('');
        $("#worksystemtask-course_id").attr("data-add", "true");
        $("#worksystemtask-course_id").html("");
        $('#select2-worksystemtask-course_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        $.post("/framework/api/search?id="+$(this).val(),function(data)
        {
            $('<option/>').val('').text(text).appendTo(element);
            $.each(data['data'],function()
            {
                $('<option>').val(this['id']).text(this['name']).appendTo($('#worksystemtask-course_id'));
            });
        });
    });   
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>