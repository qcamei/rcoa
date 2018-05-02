<?php

use kartik\widgets\Select2;
use wskeee\framework\models\Course;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model Course */
/* @var $form ActiveForm */
?>

<div class="course-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'parent[parent_id]')->widget(Select2::classname(), [
        'data' => $colleges, 
        'options' => ['placeholder' => Yii::t('rcoa/basedata', 'Placeholder'), 'onchange'=>'wx_one(this)']
    ])->label(Yii::t('app', 'Item ID'))?>
    
    <?= $form->field($model, 'parent_id')->widget(Select2::classname(), [
        'data' => $projects, 
        'options' => ['placeholder' => Yii::t('rcoa/basedata', 'Placeholder')]
    ])->label(Yii::t('app', 'Item Child ID'))?>
    
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'des')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<script type="text/javascript">
    function wx_one(e){
        //console.log($(e).val());
	$("#course-parent_id").html("");
        $("#select2-course-parent_id-container").html("<?= Yii::t('rcoa/basedata', 'Placeholder') ?>");
	$.post("/need/course/search?parent_id="+$(e).val(),function(result)
        {
            if(result['code']==0)
            {
                $('<option/>').appendTo($("#course-parent_id"));
                $.each(result['data'],function(index,element)
                {
                    $('<option>').val(index).text(element).appendTo($("#course-parent_id"));
                });
            }else{
                alert('获取 <?= Yii::t('rcoa/basedata', 'Project') ?> 失败！\nmsg：'+result['msg']);
            }
	});
    }
    
</script>