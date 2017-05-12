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

?>

<div class="demand-workitem-form">
        
        <?= Html::a('样例展示', ['workitem/list'], ['style' => 'float: right; font-size:16px;', 'target' => '_blank']) ?>
        
        <table class="table table-bordered demand-workitem-table">
           
            <thead>
                <tr>
                    <th></th>
                    <td class="text-center"><?= Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']); ?>新建</td>
                    <td class="text-center"><?= Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']); ?>改造</td>
                </tr>
            </thead>
            
            <tbody>
                <?php foreach ($workitmType as $type): ?>
                <tr class="tr">
                    <th class="text-center"><?= $type['name'] ?></th>
                    <td></td>
                    <td></td>
                </tr>
                    <?php foreach ($workitem as $work): ?>
                        <?php if($work['workitem_type'] == $type['id']): ?>
                        <tr>
                            <th class="text-right"><?= $work['name'] ?></th>
                            <?php foreach ($work['childs'] as $child): ?>     
                            <td>
                                <div class="col-lg-4 col-md-7 col-sm-7 col-xs-8" style="padding:0px 5px;">
                                    <?= Html::input('number', 'value['.$child['id'].']', isset($child['value']) ? $child['value'] : 0, [
                                        'class' => 'form-control workitem-input', 'min' => 0, 
                                        'data-cost' => $child['cost'], 'onblur' => 'totalCost($(this));'
                                    ]) ?>
                                </div>
                                <div class="workitem-tooltip" data-toggle="tooltip" data-placement="top" title="￥<?= $child['cost'] ?> / <?= $child['unit'] ?>"></div>
                                <div class="unit"><?= $child['unit'] ?></div>
                                <div class="cost-unit"><span>( ￥<?= $child['cost'] ?> / <?= $child['unit'] ?> )</span></div>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
                        
        </table> 
    
    <div class="total-cost">
        <div class="total">
            <?= Yii::t('rcoa/demand', 'Budget Cost') ?>：￥<span id="total-cost"><?= !empty($model->budget_cost) ? 
number_format($model->budget_cost + $model->budget_cost * $model->bonus_proportion, 2, '.', ',') : '0.00' ?></span>
        <?= Html::hiddenInput('budget_cost', $model->budget_cost, ['id' => 'total-cost-input']); ?>
        </div>
        <span class="pattern">(预算成本 = 预算开发成本 + 预算开发成本 × 绩效分值)</span>
    </div>
    
</div>

<?php
$js =   
<<<JS
    /** 计算总成本 */
    window.totalCost = function(elem){
        var totalCost = 0;
        var r = /^[0-9]*[1-9][0-9]*$/;
        var value = $(elem).val();
        var bonusProportion = $('#demandtask-bonus_proportion').val();
        if(!r.test(value))
            $(elem).val(Math.floor(value));
        $('.workitem-input').each(function(){
            totalCost += $(this).val() * $(this).attr('data-cost');
        });
        $('#total-cost').text(number_format(totalCost + totalCost * bonusProportion, 2, '.', ','));
        $('#total-cost-input').val(totalCost);
    } 
    
    /** 小屏幕显示 */
    var width = $(document).width();
    if(width <= 480){
        $('.workitem-input').each(function(){
            $(this).focus(function(){
                $(this).parent().next('.workitem-tooltip').tooltip('show');
            });
            $(this).blur(function(){
                $(this).parent().next('.workitem-tooltip').tooltip('hide');
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