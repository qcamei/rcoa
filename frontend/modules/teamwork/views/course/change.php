<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TwAsset;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
    
/* @var $model CourseManage */

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">移交其它部门</h4>
</div>
<div class="modal-body">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'form-change', 
            'class'=>'form-horizontal',
        ],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-10 col-md-10\">{input}</div>\n<div class=\"col-lg-10 col-md-10\">{error}</div>",  
            'labelOptions' => [
                'class' => 'col-lg-2 col-md-2 control-label',
                'style'=>[
                    'color'=>'#999999',
                    'font-weight'=>'normal',
                    'padding-right' => '0'
                ]
            ],  
        ], 
    ]); ?>

    <?= $form->field($model, 'team_id')->widget(Select2::classname(), [
        'data' => $team, 'hideSearch'=>false, 'options' => ['placeholder' => '请选择...'],
    ])?>

    <?= $form->field($model, 'course_principal')->widget(Select2::classname(), [
        'data' => $coursePrincipal, 'hideSearch'=>false, 'options' => ['placeholder' => '请选择...'],
    ])?>

    <?php ActiveForm::end(); ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" id="change-save">确认</button>
</div>

<script type="text/javascript">
    /** 下拉选择【课程负责人】 */
    $('#coursemanage-team_id').change(function(){
        var url = "/teamwork/course/search-select?team_id="+$(this).val(),
            element = $('#coursemanage-course_principal');
        $("#coursemanage-course_principal").html("");
        $('#select2-coursemanage-course_principal-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        wx(url, element, '请选择...');
    });
    /** 移交操作 提交表单 */
    $("#change-save").click(function()
    {
        $('#form-change').submit();       
    });
</script>

<?php
    TwAsset::register($this);
?>