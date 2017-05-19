<?php

use common\models\demand\DemandDelivery;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DemandDelivery */
/* @var $form ActiveForm */

$is_show = reset($workitemType);   //获取数组的第一个值

?>

<div class="demand-delivery-form">
    
    <?php $form = ActiveForm::begin(['id' => 'demand-delivery-form']); ?>
        
    <table class="table table-bordered demand-workitem-table">
        
        <thead>
            <tr>
                <th></th>
                <td class="text-center" style="width: 450px">需求</td>
                <td class="text-center">交付</td>
            </tr>
        </thead>
        
        <tbody>
            <tr>
                <th class="text-center">时间</th>
                <td class="text-center"><?= $model->demandTask->plan_check_harvest_time ?></td>
                <td class="text-center"><?= date('Y-m-d H:i', time()) ?></td>
            </tr>
            <?php  foreach ($workitemType as $type): ?>
            <tr class="tr">
                <th class="text-center"><?= $type['name'] ?></th>
                <td></td>
                <td class="text-center">
                    <?php if($is_show['id'] == $type['id'] ): ?>
                    <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                        <?= Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']) ?>新建
                    </div>
                    <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                        <?= Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']) ?>改造
                    </div>
                    <?php endif; ?>
                </td>
            </tr>
                <?php foreach ($workitem as $work): ?>
                    <?php if($work['workitem_type'] == $type['id']): ?>
                    <tr>
                        <th class="text-right"><?= $work['name'] ?></th>
                        <td class="text-center">
                        <?php rsort($work['childs']); foreach ($work['childs'] as $child): ?>                         
                            <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                            <?= $child['is_new'] == true ? 
                                   Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']).$child['value'].$child['unit'] :
                                   Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']).$child['value'].$child['unit'];
                            ?>
                            </div>
                        <?php endforeach; ?>    
                        </td>
                        <td class="text-center">
                        <?php rsort($work['childs']); foreach ($work['childs'] as $child): ?>                         
                            <div class="col-xs-6">
                                <div class="col-xs-9">
                                <?= $child['is_new'] == true ? 
                                    Html::input('number', 'value['.$child['id'].']', 0, [
                                        'class' => 'form-control  col-xs-9 workitem-input', 'min' => 0,
                                        'data-cost' => $child['cost'], 'onblur' => 'totalCost($(this));'
                                    ]) :
                                    Html::input('number', 'value['.$child['id'].']', 0, [
                                        'class' => 'form-control  col-xs-9 workitem-input', 'min' => 0,
                                        'data-cost' => $child['cost'], 'onblur' => 'totalCost($(this));'
                                    ]);
                                ?>
                                </div>
                                <div class="unit"><?= $child['unit'] ?></div>
                            </div>
                        <?php endforeach; ?>
                        </td>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <tr class="tr">
                <th class="text-center">外部成本</th>
                <td><?= !empty($model->demandTask->external_budget_cost) ? '￥'.number_format($model->demandTask->external_budget_cost, 2) : '无' ?></td>
                <td><?=  Html::textInput('external_reality_cost', '0.00', ['id' => 'external-reality-cost', 'class' => 'form-control', 'type' => 'number', 'onblur' => 'totalCost();']); ?></td>
            </tr>
            <tr class="tr">
                <th class="text-center">备注</th>
                <td><?= !empty($model->demandTask->des) ? $model->demandTask->des : '无' ?></td>
                <td><?=  Html::textarea('des', '无', ['class' => 'form-control', 'rows' => 4]); ?></td>
            </tr>
        </tbody>
        
    </table>
    
    <div class="total-cost">
        <div class="total">
            <!--<?= Yii::t('rcoa/demand', 'Total Cost') ?>：￥<span id="total-cost">0.00</span>-->
            <?= Html::hiddenInput('cost', '0.00', ['id' => 'total-cost-input']); ?>
        </div>
        <!--<span class="pattern">（人工实际成本 = 人工实际成本 + 奖金）</span>-->
    </div>
        
    <?php ActiveForm::end(); ?>
    
</div>

<?php
$bonusProportion = $model->demandTask->bonus_proportion;
$js =   
<<<JS
    /** 计算总成本 */
    window.totalCost = function(elem){
        if(elem != null){
            var r = /^[0-9]*[1-9][0-9]*$/;
            var value = $(elem).val();
            if(!r.test(value))
                $(elem).val(Math.floor(value));
        }
        var totalCost = 0;
        //var bonusProportion = $bonusProportion;
        //var er_cost = $('#external-reality-cost').val();
        
        $('.workitem-input').each(function(){
            totalCost += $(this).val() * $(this).attr('data-cost');
        });
        
        //var totalRealityCost = (totalCost + totalCost * bonusProportion); //+ Number(er_cost);
        //$('#total-cost').text(number_format(totalRealityCost, 2, '.', ','));
        $('#total-cost-input').val(totalCost);
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
