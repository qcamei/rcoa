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
$is_rowspan = [];  //是否合并单元格
$worktime = ArrayHelper::getColumn($workitem, 'demand_time');
$workdes = ArrayHelper::getColumn($workitem, 'des');
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
            <div role="tabpanel" class="tab-pane fade active in" id="demand" aria-labelledby="demand-tab">
                <table class="table table-bordered demand-workitem-table">
                    <tbody>
                        <tr>
                            <th class="text-center" style="width: 100px">时间</th>
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
                                    <td style="width: 300px">
                                    <?php rsort($work['childs']); foreach ($work['childs'] as $child): ?>                         
                                        <div class="col-lg-6 col-md-7 col-sm-7 col-xs-6">
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
                            <th class="text-center">备注</th>
                            <td><?= reset($workdes) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="deliver" aria-labelledby="deliver-tab">
                <table class="table table-bordered demand-workitem-table">
                    <tbody>
                        <tr>
                            <th class="text-center" style="width: 100px">时间</th>
                            <td class="text-center"><?= reset($deliverytime) ?></td>
                        </tr>
                        <?php foreach ($workitemType as $type): ?>
                        <tr class="tr">
                            <th class="text-center"><?= $type['name'] ?></th>
                            <td></td>
                        </tr>
                            <?php foreach ($workitem as $work): ?>
                                <?php if($work['workitem_type'] == $type['id']): ?>
                                <tr>
                                    <th class="text-right"><?= $work['name'] ?></th>
                                    <td class="text-center">
                                    <?php rsort($delivery[$work['id']]['childs']); foreach ($delivery[$work['id']]['childs'] as $child): ?>                        
                                        <div class="col-lg-6 col-md-7 col-sm-7 col-xs-6">
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
                            <th class="text-center">备注</th>
                            <td><?= reset($deliverydes) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="acceptance" aria-labelledby="acceptance-tab">
                <table class="table table-bordered demand-workitem-table">
                    <tbody>
                        <tr>
                            <th class="text-center" style="width: 100px">时间</th>
                            <td class="text-center"><?= reset($acceptancetime) ?></td>
                        </tr>
                        <?php foreach ($workitemType as $type): 
                            if($percentage[$type['id']] == NUll) $percentage[$type['id']] = 100; else $percentage[$type['id']];
                            if($percentage[$type['id']] < 70) $color = '#ff0000'; else if($percentage[$type['id']] < 100) $color = '#428BCA'; else $color = '#43c584';
                        ?>
                        <tr class="tr">
                            <th class="text-center"><?= $type['name'] ?></th>
                            <td class="text-center">
                                <?php if($is_show['id'] == $type['id'] ): ?>
                                <div class="col-lg-6 col-md-7 col-sm-7 col-xs-4">数量</div>
                                <div class="col-lg-6 col-md-7 col-sm-7 col-xs-8">质量</div>
                                <?php endif; ?>
                            </td>
                        </tr>
                            <?php foreach ($workitem as $work): ?>
                                <?php if($work['workitem_type'] == $type['id']): ?>
                                <tr>
                                    <th class="text-right"><?= $work['name'] ?></th>
                                    <?php if(!isset($is_rowspan[$type['id']])): $is_rowspan[$type['id']] = true;?>
                                    <td class="text-center" rowspan="<?= $number[$type['id']] ?>">
                                        <div class="col-lg-6 col-md-7 col-sm-7 col-xs-6">
                                            <?php  if(isset($percentage[$type['id']])): ?>
                                            <span class="chart" data-percent="<?= $percentage[$type['id']]; ?>" data-bar-color="<?= $color; ?>">
                                                <span class="percent" style="color: <?= $color; ?>"></span>
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-lg-6 col-md-7 col-sm-7 col-xs-6">
                                            <?php if(isset($acceptance[$type['id']])): ?>
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
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                        <tr class="tr">
                            <th class="text-center">备注</th>
                            <td>
                                <?php $is_pass = reset($acceptancepass); ?>
                                <?php if($is_pass !== false): ?>
                                <div class="acceptance-pass">结果：
                                    <?php if($is_pass == 0): ?>
                                        <span class="btn btn-danger btn-sm">验收不通过</span>
                                    <?php else: ?>
                                    <span class="btn btn-success btn-sm">验收通过</span>
                                    <?php endif; ?>
                                </div>
                                <div class="acceptance-des">
                                    原因：<?= reset($acceptancedes) ?>
                                </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center">绩效得分<p class="pattern">(满分为10分)</p></th>
                            <td>
                                <?= !empty($model->demandTask->score) && $is_pass !== false && $is_pass != 0? 
                                number_format($model->demandTask->score * 10, 2, '.', ',').'<p><span class="pattern">（ 绩效得分 = 各项评分 × 各项实际成本 / 总实际成本 ）</span></p>' : '无' ?>
                            </td>
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