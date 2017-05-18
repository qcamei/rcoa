<?php

use common\models\demand\DemandAcceptance;
use common\models\demand\DemandDelivery;
use frontend\modules\demand\assets\ChartAsset;
use kartik\slider\Slider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DemandAcceptance */
/* @var $form ActiveForm */
/* @var $delivery DemandDelivery */

$is_show = reset($workitemType);   //获取数组的第一个值
$is_rowspan = [];  //是否合并单元格
$worktime = ArrayHelper::getColumn($workitem, 'demand_time');
$workdes = ArrayHelper::getColumn($workitem, 'des');
$d_realityCost = ArrayHelper::getColumn($delivery, 'reality_cost');
$d_externalRealityCost = ArrayHelper::getColumn($delivery, 'external_reality_cost');
$deliverytime = ArrayHelper::getColumn($delivery, 'delivery_time');
$deliverydes = ArrayHelper::getColumn($delivery, 'des');

$number = [];   //合并单元格数
foreach ($workitem as $work){
    if(!isset($number[$work['workitem_type']]))
        $number[$work['workitem_type']] = 0;
    $number[$work['workitem_type']] ++;
}

?>

<div class="demand-acceptance-form">
    
    <?= Html::a('样例展示', ['workitem/list'], ['style' => 'float: right; font-size:16px;', 'target' => '_blank']) ?>
    
    <?php $form = ActiveForm::begin(['id' => 'demand-acceptance-form']); ?>
    
    <table class="table table-bordered demand-workitem-table">
        
        <thead>
            <tr>
                <th style="width: 10%"></th>
                <td class="text-center" style="width: 25%">需求</td>
                <td class="text-center" colspan="2" style="width: 40%">交付</td>
                <td class="text-center" style="width: 25%">验收</td>
            </tr>
        </thead>
        
        <tbody>
            <tr>
                <th class="text-center">时间</th>
                <td class="text-center"><?= reset($worktime) ?></td>
                <td class="text-center" colspan="2"><?= reset($deliverytime) ?></td>
                <td class="text-center"><?= date('Y-m-d H:i', time()) ?></td>
            </tr>
            <?php  foreach ($workitemType as $type): 
                if($percentage[$type['id']] == NUll) $percentage[$type['id']] = 100; else $percentage[$type['id']];
                if($percentage[$type['id']] < 70) $color = '#ff0000'; else if($percentage[$type['id']] < 100) $color = '#428BCA'; else $color = '#43c584';
            ?>
            <tr class="tr">
                <th class="text-center"><?= $type['name'] ?></th>
                <td></td>
                <td colspan="2"></td>
                <td></td>
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
                        <?php rsort($delivery[$work['id']]['childs']); foreach ($delivery[$work['id']]['childs'] as $child): ?>                         
                            <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                            <?= $child['is_new'] == true ? 
                                   Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']).$child['value'].$child['unit'] :
                                   Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']).$child['value'].$child['unit'];
                            ?>
                            </div>
                        <?php endforeach; ?>
                        </td>
                        <?php if(!isset($is_rowspan[$type['id']])): $is_rowspan[$type['id']] = true;?>
                        <td class="text-center" rowspan="<?= $number[$type['id']] ?>" style="width:100px">
                            <?php  if(isset($percentage[$type['id']])): ?>
                            <span class="chart" data-percent="<?= $percentage[$type['id']]; ?>" data-bar-color="<?= $color; ?>">
                                <span class="percent" style="color: <?= $color; ?>"></span>
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center" rowspan="<?= $number[$type['id']] ?>">
                            <div class="col-lg-4 col-md-7 col-sm-7 col-xs-12"><span>评分：</span></div>
                            <div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
                                <?= Slider::widget([
                                    'class' => 'acceptance-value',
                                    'name'=> 'value['.$type['id'].']',
                                    'value'=> $percentage[$type['id']] >= 100 ?  10 : 0,
                                    'sliderColor'=>Slider::TYPE_INFO,
                                    'handleColor'=>Slider::TYPE_INFO,   
                                    'options' => [
                                       'style' => [
                                           'width' => '100%',
                                       ],
                                    ],
                                    'pluginOptions'=>[
                                        'min' => 0,
                                        'max' => 10,
                                        'tooltip'=>'always',
                                        /*'formatter'=>new JsExpression("function(val) { 
                                            if (val < 7) {
                                                return '不达标';
                                            }
                                            else if (val < 10) {
                                                return '达标';
                                            }
                                            else {
                                                return '非常好';
                                            }
                                            
                                        }")*/
                                    ],
                                    
                                ]); ?>       
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endforeach; ?>
            <tr class="tr">
                <th class="text-center">人工成本</th>
                <td>￥<?= number_format($model->demandTask->budget_cost, 2) ?></td>
                <td colspan="2">
                    <?php $surplus = reset($d_realityCost) - $model->demandTask->budget_cost; 
                        if(reset($d_realityCost) > $model->demandTask->budget_cost): ?>
                    <span style="color:red">￥<?= number_format(reset($d_realityCost), 2) ?></span>
                    <span class="pattern" style="color: #000">（超出预算￥<?= number_format($surplus, 2) ?>）</span>
                    <?php  else: ?>
                    <span>￥<?= number_format(reset($d_realityCost), 2) ?></span>
                    <?php endif; ?>
                </td>
                <td rowspan="3">
                    <input type="hidden" name="DemandAcceptance[pass]" value="">
                    <div id="demandacceptance-pass">
                        <label><input type="radio" name="DemandAcceptance[pass]" value="1">&nbsp;<a class="btn btn-success">验收通过</a></label>
                        <label><input type="radio" name="DemandAcceptance[pass]" value="0"  checked="checked" >&nbsp;<a class="btn btn-danger">验收不通过</a></label>
                    </div>
                </td>
            </tr>
            <tr class="tr">
                <th class="text-center">外部成本</th>
                <td>￥<?= number_format($model->demandTask->external_budget_cost, 2) ?></td>
                <?php $surplus = reset($d_externalRealityCost) - $model->demandTask->external_budget_cost; 
                        if(reset($d_externalRealityCost) > $model->demandTask->external_budget_cost): ?>
                <td colspan="2" style="color:red">
                    ￥<?= number_format(reset($d_externalRealityCost), 2) ?>
                    <span class="pattern" style="color: #000">（超出预算￥<?= number_format($surplus, 2) ?>）</span>
                </td>
                <?php else: ?>
                <td colspan="2">￥<?= number_format(reset($d_externalRealityCost), 2) ?></td>
                <?php endif; ?>
            </tr>
            <tr class="tr">
                <th class="text-center">总成本</th>
                <?php $budgetCost = $model->demandTask->budget_cost + $model->demandTask->budget_cost * $model->demandTask->bonus_proportion;
                       $totalBudgetCost = $budgetCost + $model->demandTask->external_budget_cost;
                       $realityCost = reset($d_realityCost) + reset($d_realityCost) * $model->demandTask->bonus_proportion;
                       $totalRealityCost = $realityCost + reset($d_externalRealityCost);?>
                <td>
                    ￥<?= number_format($totalBudgetCost, 2) ?>
                    <p class="pattern">（总成本 = 人工成本 + 奖金 + 外部成本）</p>
                </td>
                <?php $surplus = $totalRealityCost - $totalBudgetCost;
                    if($totalRealityCost > $totalBudgetCost): ?>
                <td colspan="2" style="color:red">
                    ￥<?= number_format($totalRealityCost, 2) ?>
                    <span class="pattern" style="color: #000">（超出预算￥<?= number_format($surplus, 2) ?>）</span>
                    <p class="pattern">（总成本 = 人工成本 + 奖金 + 外部成本）</p>
                </td>
                <?php else: ?>
                <td colspan="2">
                    ￥<?= number_format($totalRealityCost, 2) ?>
                    <p class="pattern">（总成本 = 人工成本 + 奖金 + 外部成本）</p>
                </td>
                <?php endif; ?>
            </tr>
            <tr class="tr">
                <th class="text-center">备注</th>
                <td><?= reset($workdes) ?></td>
                <td colspan="2"><?= reset($deliverydes) ?></td>
                <td><?=  Html::activeTextarea($model, 'des', ['class' => 'form-control', 'rows' => 4, 'value' => '无']); ?></td>
            </tr>
           
        </tbody>
        
    </table>
    <span class="pattern" style="float: right; margin-top: -15px;">（最大奖金 = 人工成本 + 人工成本 × 绩效分值）</span>
    
    <?= Html::activeHiddenInput($model, 'demand_delivery_id', ['value' => $deliveryModel->id]); ?>
    
    <?php ActiveForm::end(); ?>

</div>

<?php
$js =   
<<<JS
   $(function() {
        $('.chart').easyPieChart({  
                size: 70,
                onStep: function(from, to, percent) {  
                $(this.el).find('.percent').text(Math.round(percent));  
            }  
        }); 
    });
  
    if($pass){
        $("input:radio[name='DemandAcceptance[pass]']").eq(0).attr("checked",'checked');
        $("input:radio[name='DemandAcceptance[pass]']").eq(1).attr("disabled",'disabled');
        $(".btn-danger").addClass("disabled");
    }
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    ChartAsset::register($this);
?>