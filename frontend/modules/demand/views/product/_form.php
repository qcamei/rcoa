<?php

use common\models\demand\DemandTaskProduct;
use kartik\widgets\TouchSpin;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DemandTaskProduct */
/* @var $form ActiveForm */
?>

<div class="demand-task-product-form">

    <?php $form = ActiveForm::begin([
        'id' => 'demand-task--product-form',
        'action' => '/demand/product/save?task_id='.$task_id.'&product_id='.$product_id,
        'fieldConfig' => [  
            'template' => "<div class=\"form-input\">{input}</div>",  
        ], 
    ]); ?>

    <?= $form->field($model, 'number')->widget(TouchSpin::classname(),  [
        'options' => ['class' => 'input-sm', 'style' => 'padding:5px;'],
        'pluginOptions' => [
            'placeholder' => '数量 ...',
            'min' => 1,
            'max' => 99999,
            'buttonup_class' => 'btn btn-default btn-sm', 
            'buttondown_class' => 'btn btn-default btn-sm', 
            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>', 
            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
        ],
    ]) ?>  

    <?php ActiveForm::end(); ?>

</div>

<?php
$num = !empty($model->number) ? $model->number : 0;
$target = $model->task->lesson_time;
$js = <<<JS
    $("#demandtaskproduct-number").blur(function(){
        var num = $('#demandtaskproduct-number').val();
        var lessons  = Number($lessons) - Number($num) + Number(num);
        $('#number').text(number_format((Number($totals) - Number($unit_price * Number($num))) + Number($unit_price) * Number(num), 2, '.', ','));
        $('#unit_price').text(number_format(Number($unit_price) * Number(num), 2, '.', ','));
        $('.lessons-small').text(lessons);
        if(lessons > $target)
            $('#submit').addClass('disabled');
        else
            $('#submit').removeClass('disabled');
    });
    $('.input-group-btn button').click(function(){
        var num = $('#demandtaskproduct-number').val();
        var lessons  = Number($lessons) - Number($num) + Number(num);
        $('#number').text(number_format((Number($totals) - Number($unit_price) * Number($num)) + Number($unit_price) * Number(num), 2, '.', ','));
        $('#unit_price').text(number_format(Number($unit_price) * Number(num), 2, '.', ','));
        $('.lessons-small').text(lessons);
        if(lessons > $target)
            $('#submit').addClass('disabled');
        else
            $('#submit').removeClass('disabled');
    });
    function number_format(number, decimals, dec_point, thousands_sep) {  
        var n = !isFinite(+number) ? 0 : +number,  
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),  
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,  
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,  
            s = '',  
            toFixedFix = function (n, prec) {  
                var k = Math.pow(10, prec);  
                return '' + Math.round(n * k) / k;        };  
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;  
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');  
        if (s[0].length > 3) {  
            s[0] = s[0].replace(/(\d)(?=(?:\d{3})+$)/g, '$1'+sep);  
        }  
        if ((s[1] || '').length < prec) {  
            s[1] = s[1] || '';  
            s[1] += new Array(prec - s[1].length + 1).join('0');  
        }      
        return s.join(dec);  
    } 
    
JS;
    $this->registerJs($js, View::POS_READY);
?>