<?php

use common\models\demand\DemandAcceptance;
use common\widgets\cslider\CSlider;
use frontend\modules\demand\assets\ChartAsset;
use frontend\modules\demand\assets\DemandAssets;
use kartik\widgets\Select2;
use wskeee\utils\DateUtil;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model DemandAcceptance */

//$this->title = Yii::t('rcoa/demand', 'Demand Acceptances');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Acceptances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$is_show = reset($workitemType);   //获取数组的第一个值
$worktime = ArrayHelper::getColumn($workitem, 'demand_time');
$workdes = ArrayHelper::getColumn($workitem, 'des');
$d_realityCost = ArrayHelper::getColumn($delivery, 'reality_cost');
$d_externalRealityCost = ArrayHelper::getColumn($delivery, 'external_reality_cost');
$deliverytime = ArrayHelper::getColumn($delivery, 'delivery_time');
$deliverydes = ArrayHelper::getColumn($delivery, 'des');
$acceptancetime = ArrayHelper::getColumn($acceptance, 'acceptance_time');
$acceptancepass = ArrayHelper::getColumn($acceptance, 'pass');
$acceptancedes = ArrayHelper::getColumn($acceptance, 'des');


$number = [];   //合并单元格数
foreach ($workitem as $work){
    if(!isset($number[$work['workitem_type']]))
        $number[$work['workitem_type']] = 0;
    $number[$work['workitem_type']] ++;
}

?>

<div class="demand-acceptance-view has-title">
    <?php if(empty($delivery)): ?>
    没有找到数据。
    <?php else: ?>
    <div class="select-date">
        <?= Select2::widget([
            'id' => 'date',
            'name' => 'created_at',
            'value' => $delivery_id,
            'data' => $dates, 
            'hideSearch' => true,
            'options' => [
                'placeholder' => '请选择...',
            ]
        ])?>
    </div>
    <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
        <ul id="myTabs" class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#demand" role="tab" id="demand-tab"  data-toggle="tab" aria-controls="demand" aria-expanded="true">需求</a></li>
            <li role="presentation" class=""><a href="#deliver" role="tab" id="deliver-tab" data-toggle="tab" aria-controls="deliver" aria-expanded="false">交付</a></li>
            <li role="presentation" class=""><a href="#acceptance" role="tab" id="acceptance-tab" data-toggle="tab" aria-controls="acceptance" aria-expanded="false">验收</a></li>
        </ul>
        <br />
        <div id="myTabContent" class="tab-content">
            <!--需求-->
            <div role="tabpanel" class="tab-pane fade active in" id="demand" aria-labelledby="demand-tab">
                <table class="table table-bordered demand-workitem-table">
                    <tbody>
                        <tr>
                            <th class="text-center" style="width: 25%">时间</th>
                            <td class="text-center"><?= reset($worktime) ?></td>
                        </tr>
                        <?php foreach ($workitemType as $type): ?>
                        <tr class="tr">
                            <th class="text-center"><?= $type['name'] ?></th>
                            <td></td>
                        </tr>
                            <?php foreach ($workitem as $work): ?>
                                <?php if($work['workitem_type'] == $type['id']): ?>
                                <tr class="text-center">
                                    <th class="text-right"><?= $work['name'] ?></th>
                                    <td>
                                    <?php rsort($work['childs']); foreach ($work['childs'] as $child): ?>                         
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                        <?= $child['is_new'] == true ? 
                                               Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']).$child['value'].$child['unit'] :
                                               Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']).$child['value'].$child['unit'];
                                        ?>
                                        </div>
                                    <?php endforeach; ?>       
                                    </td>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        <tr class="tr">
                            <th class="text-center">人工成本</th>
                            <td>￥<?= number_format($model->demandTask->budget_cost, 2) ?></td>
                        </tr>
                        <tr class="tr">
                            <th class="text-center">外部成本</th>
                            <td>￥<?= number_format($model->demandTask->external_budget_cost, 2) ?></td>
                        </tr>
                        <tr class="tr">
                            <th class="text-center">总成本</th>
                            <?php $budgetCost = $model->demandTask->budget_cost + $model->demandTask->budget_cost * $model->demandTask->bonus_proportion;
                                   $totalBudgetCost = $budgetCost + $model->demandTask->external_budget_cost;?>
                            <td>
                                ￥<?= number_format($totalBudgetCost, 2) ?>
                                <p class="pattern">（总成本=人工成本+奖金+外部成本）</p>
                            </td>
                        </tr>
                        <tr class="tr">
                            <th class="text-center">备注</th>
                            <td><?= reset($workdes) ?></td>
                        </tr>
                    </tbody>
                </table>
                <span class="pattern" style="float: right; margin-top: -15px;">（最大奖金=人工成本+人工成本×绩效比值）</span>
            </div>
            <!--交付-->
            <div role="tabpanel" class="tab-pane fade" id="deliver" aria-labelledby="deliver-tab">
                <table class="table table-bordered demand-workitem-table">
                    <tbody>
                        <tr>
                            <th class="text-center" style="width: 25%">时间</th>
                            <td class="text-center" colspan="2"><?= reset($deliverytime) ?></td>
                        </tr>
                        <?php $is_rowspan = []; //是否合并单元格 ?>
                        <?php foreach ($workitemType as $type): 
                            if($percentage[$type['id']] == NUll) $percentage[$type['id']] = 100; else $percentage[$type['id']];
                            if($percentage[$type['id']] < 70) $color = '#ff0000'; else if($percentage[$type['id']] < 100) $color = '#428BCA'; else $color = '#43c584';
                        ?>
                        <tr class="tr">
                            <th class="text-center"><?= $type['name'] ?></th>
                            <td colspan="2"></td>
                        </tr>
                            <?php foreach ($workitem as $work): ?>
                                <?php if($work['workitem_type'] == $type['id']): ?>
                                <tr>
                                    <th class="text-right"><?= $work['name'] ?></th>
                                    <td class="text-center">
                                    <?php rsort($delivery[$work['id']]['childs']); foreach ($delivery[$work['id']]['childs'] as $child): ?>                        
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding:0px;" >
                                        <?= $child['is_new'] == true ? 
                                               Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']).$child['value'].$child['unit'] :
                                               Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']).$child['value'].$child['unit'];
                                        ?>
                                        </div>
                                    <?php endforeach; ?>       
                                    </td>
                                    <?php if(!isset($is_rowspan[$type['id']])): $is_rowspan[$type['id']] = true;?>
                                    <td class="text-center" rowspan="<?= $number[$type['id']] ?>">
                                        <?php  if(isset($percentage[$type['id']])): ?>
                                        <span class="chart" data-percent="<?= $percentage[$type['id']]; ?>" data-bar-color="<?= $color; ?>">
                                            <span class="percent" style="color: <?= $color; ?>"></span>
                                        </span>
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        <tr class="tr">
                            <th class="text-center">人工成本</th>
                            <td colspan="2">
                                <?php $surplus = reset($d_realityCost) - $model->demandTask->budget_cost; 
                                    if(reset($d_realityCost) > $model->demandTask->budget_cost): ?>
                                <span style="color:#ff0000">￥<?= number_format(reset($d_realityCost), 2) ?></span>
                                <p class="pattern" style="color: #000">（超出预算￥<?= number_format($surplus, 2) ?>）</p>
                                <?php  else: ?>
                                <span>￥<?= number_format(reset($d_realityCost), 2) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="tr">
                            <th class="text-center">外部成本</th>
                            <td colspan="2">
                                <?php $surplus = reset($d_externalRealityCost) - $model->demandTask->external_budget_cost; 
                                    if(reset($d_externalRealityCost) > $model->demandTask->external_budget_cost): ?>
                                <span style="color:#ff0000">￥<?= number_format(reset($d_externalRealityCost), 2) ?></span>
                                <p class="pattern" style="color: #000">（超出预算￥<?= number_format($surplus, 2) ?>）</p>
                                <?php else: ?>
                                <span>￥<?= number_format(reset($d_externalRealityCost), 2) ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="tr">
                            <th class="text-center">总成本</th>
                            <td colspan="2">
                                <?php $realityCost = reset($d_realityCost) + reset($d_realityCost) * $model->demandTask->bonus_proportion;
                                       $totalRealityCost = $realityCost + reset($d_externalRealityCost);?>
                                <?php $surplus = $totalRealityCost - $totalBudgetCost;
                                    if($totalRealityCost > $totalBudgetCost): ?>
                                    <span  style="color: #ff0000">￥<?= number_format($totalRealityCost, 2) ?></span><br>
                                    <span style="color: #000">（超出预算￥<?= number_format($surplus, 2) ?>）</span>
                                <?php else: ?>
                                    <span>￥<?= number_format($totalRealityCost, 2) ?></span>
                                <?php endif; ?>
                                    <p class="pattern">（总成本=人工成本+奖金+外部成本）</p>
                            </td>
                        </tr>
                        <tr class="tr">
                            <th class="text-center">备注</th>
                            <td colspan="2"><?= reset($deliverydes) ?></td>
                        </tr>
                    </tbody>
                </table>
                <span class="pattern" style="float: right; margin-top: -15px;">（最大奖金=人工成本+人工成本×绩效比值）</span>
            </div>
            <!--验收-->
            <div role="tabpanel" class="tab-pane fade" id="acceptance" aria-labelledby="acceptance-tab">
                <table class="table table-bordered demand-workitem-table">
                    <tbody>
                        <tr>
                            <th class="text-center" style="width: 30%">时间</th>
                            <td class="text-center"><?= reset($acceptancetime) ?></td>
                        </tr>
                        <?php $is_rowspan = []; //是否合并单元格 ?>
                        <?php foreach ($workitemType as $type): 
                            if($percentage[$type['id']] == NUll) $percentage[$type['id']] = 100; else $percentage[$type['id']];
                            if($percentage[$type['id']] < 70) $color = '#ff0000'; else if($percentage[$type['id']] < 100) $color = '#428BCA'; else $color = '#43c584';
                        ?>
                        <tr class="tr">
                            <th class="text-center"><?= $type['name'] ?></th>
                            <td></td>
                        </tr>
                            <?php foreach ($workitem as $work): ?>
                                <?php if($work['workitem_type'] == $type['id']): ?>
                                <tr>
                                    <th class="text-right"><?= $work['name'] ?></th>                                    
                                    <?php if(!isset($is_rowspan[$type['id']])): $is_rowspan[$type['id']] = true; ?>
                                    <td class="text-center" rowspan="<?= $number[$type['id']] ?>">
                                        <?php if(isset($acceptance[$type['id']])): ?>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4"><span>评分：</span></div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-7" style="margin-top: -25px;">
                                            <?= CSlider::widget([
                                                'plugOptions' => [
                                                    'height' => 6,                  //进度条高度
                                                    'max' => 10,                    //最大值，默认是1
                                                    'value' => (int)$acceptance[$type['id']]['value'],
                                                    'sliderColor' => $acceptance[$type['id']]['value'] < 7 ? '#ef1e25' : 
                                                            ($acceptance[$type['id']]['value'] < 10 ? '#428bca' : '#56cb90'),     //已选择颜色 #ef1e25 红色，#428bca 蓝色，#56cb90 绿色
                                                    'tooltipColor' => $acceptance[$type['id']]['value'] < 7 ? '#ef1e25' : 
                                                            ($acceptance[$type['id']]['value'] < 10 ? '#428bca' : '#56cb90'),    //提示颜色
                                                ]
                                            ]) ?>
                                        </div>
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        <?php $is_pass = reset($acceptancepass); ?>
                        <tr class="tr">
                            <th class="text-center">结果</th>
                            <td>
                                <?php if($is_pass !== false): ?>
                                <div class="acceptance-pass">
                                    <?php if($is_pass == 0): ?>
                                        <span class="btn btn-danger btn-sm">验收不通过</span>
                                    <?php else: ?>
                                    <span class="btn btn-success btn-sm">验收通过</span>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr class="tr">
                            <th class="text-center">备注</th>
                            <td>
                                <?php if($is_pass !== false): ?>
                                <div class="acceptance-des"><?= reset($acceptancedes) ?></div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center">绩效得分<p class="pattern">(满分为10分)</p></th>
                            <td>
                                <?= !empty($model->demandTask->score) && $is_pass !== false && $is_pass != 0 ? 
                                number_format($model->demandTask->score * 10, 2, '.', ',').'分<p><span class="pattern">（绩效得分=各项评分×各项实际成本÷总实际成本）</span></p>' : '无' ?>                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>
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
  
    $('#date').change(function(){
        $("#demand-acceptance-view").load("/demand/acceptance/view?demand_task_id=$demand_task_id&delivery_id="+$(this).val());
    });    
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    DemandAssets::register($this);
    ChartAsset::register($this);
?>