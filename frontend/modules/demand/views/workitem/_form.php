<?php

use common\models\demand\DemandWorkitem;
use kartik\widgets\TouchSpin;
use wskeee\utils\DateUtil;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DemandWorkitem */
/* @var $form ActiveForm */

$allModel = [];
foreach ($allModels as $model) {
    $allModel[$model->workitemType->name][$model->workitem->name][] = [
        'id' => $model->id,
        'is_new' => $model->is_new,
        'value_type' => $model->value_type,
        'cost' => $model->cost,
        'value' => $model->value,
        'unit' => $model->workitem->unit
    ];
}


?>

<div class="demand-workitem-form">
            
        <table class="table table-bordered demand-workitem-table">
           
            <thead>
                <tr>
                    <th></th>
                    <td class="text-center"><?= Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']); ?>新建</td>
                    <td class="text-center"><?= Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']); ?>改造</td>
                </tr>
            </thead>
            
            <tbody>
                <?php foreach ($allModel as $index => $models): ?>
                <tr class="tr">
                    <th class="text-center"><?= $index ?></th>
                    <td></td>
                    <td></td>
                </tr>
                    <?php foreach ($models as $keys => $elements): ?>
                        <tr>
                            <th class="text-right"><?= $keys ?></th>
                            <?php rsort($elements); foreach ($elements as $value): ?>
                            <td>
                                <div class="col-lg-4 col-md-7 col-sm-7 col-xs-12">
                                    <?= Html::input('number', 'value['.$value['id'].']', isset($value['value']) ? $value['value'] : 0, [
                                        'class' => 'form-control workitem-input', 'min' => 0, 
                                        'data-cost' => $value['cost'], 'onblur' => 'totalCost()'
                                    ]) ?>
                                </div>
                                <div class="unit"><?= $value['unit'] ?></div>
                                <div class="workitem-tooltip" data-toggle="tooltip" data-placement="top" title="￥<?= $value['cost'] ?> / <?= $value['unit'] ?>"></div>
                                <div class="cost-unit"><span>( ￥<?= $value['cost'] ?> / <?= $value['unit'] ?> )</span></div>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
                        
        </table> 
    
    <div class="total-cost">
        <div class="total">
            总费用：￥<span id="total-cost"><?= !empty($model->demandTask->cost) ? 
number_format($model->demandTask->cost + $model->demandTask->cost * $model->demandTask->bonus_proportion, 2, '.', ',') : '0' ?></span>
        <?= Html::hiddenInput('cost', $model->demandTask->cost, ['id' => 'total-cost-input']); ?>
        </div>
        <span class="pattern">(总费用 = 总成本 + 总成本 × 奖金比值)</span>
    </div>
    
</div>


<?php
$js =   
<<<JS
    /** 计算总成本 */
    window.totalCost = function(){    
        var totalCost = 0;
        var bonusProportion = $('#demandtask-bonus_proportion').val();
        $('.workitem-input').each(function(){
            totalCost += $(this).val() * $(this).attr('data-cost');
        });
        $('#total-cost').text(number_format(totalCost + totalCost * bonusProportion, 2, '.', ','));
        $('#total-cost-input').val(totalCost);
    } 
    
    /** 小屏幕显示 */
    var width = $(document).width();
    if(width <= 480){
        $('.col-xs-12').each(function(index, elem){
            $(elem).children('.workitem-input').focus(function(){
                $(elem).next('.workitem-tooltip').tooltip('show');
            });
            $(elem).children('.workitem-input').blur(function(){
                $(elem).next('.workitem-tooltip').tooltip('hide');
            });
            $(elem).children('.input-group').children('.workitem-input').focus(function(){
                $(elem).next('.workitem-tooltip').tooltip('show');
            });
            $(elem).children('.input-group').children('.workitem-input').blur(function(){
                $(elem).next('.workitem-tooltip').tooltip('hide');
            });
        });    
    }         

    /** 数字格式化 */
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
    $this->registerJs($js,  View::POS_READY);
?>