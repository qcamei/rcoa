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
    
    <?php $form = ActiveForm::begin(['id' => 'demand-acceptance-form']); ?>
    
    <table class="table table-bordered demand-workitem-table">
        
        <thead>
            <tr>
                <th></th>
                <td class="text-center" style="width: 300px">需求</td>
                <td class="text-center" style="width: 300px">交付</td>
                <td class="text-center">验收</td>
            </tr>
        </thead>
        
        <tbody>
            <tr>
                <th class="text-center">时间</th>
                <td class="text-center"><?= reset($worktime) ?></td>
                <td class="text-center"><?= reset($deliverytime) ?></td>
                <td class="text-center"><?= date('Y-m-d H:i', time()) ?></td>
            </tr>
            <?php  foreach ($workitemType as $type): 
                if($percentage[$type['id']] == NUll) $percentage[$type['id']] = 100; else $percentage[$type['id']];
                if($percentage[$type['id']] < 70) $color = '#ff0000'; else if($percentage[$type['id']] < 100) $color = '#428BCA'; else $color = '#43c584';
            ?>
            <tr class="tr">
                <th class="text-center"><?= $type['name'] ?></th>
                <td></td>
                <td></td>
                <td class="text-center">
                    <?php if($is_show['id'] == $type['id'] ): ?>
                    <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">数量</div>
                    <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">质量</div>
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
                        <td class="text-center" rowspan="<?= $number[$type['id']] ?>">
                            <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                                <?php  if(isset($percentage[$type['id']])): ?>
                                <span class="chart" data-percent="<?= $percentage[$type['id']]; ?>" data-bar-color="<?= $color; ?>">
                                    <span class="percent" style="color: <?= $color; ?>"></span>
                                </span>
                                <?php endif; ?>
                            </div>
                            <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12" style="margin-top: 25px;">
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
                <th class="text-center">备注</th>
                <td><?= reset($workdes) ?></td>
                <td><?= reset($deliverydes) ?></td>
                <td>
                    <input type="hidden" name="DemandAcceptance[pass]" value="">
                    <div id="demandacceptance-pass">
                        <label><input type="radio" name="DemandAcceptance[pass]" value="1">&nbsp;<a class="btn btn-success">验收通过</a></label>
                        <label><input type="radio" name="DemandAcceptance[pass]" value="0"  checked="checked" >&nbsp;<a class="btn btn-danger">验收不通过</a></label>
                    </div>
                    <?=  Html::activeTextarea($model, 'des', ['class' => 'form-control', 'rows' => 4, 'value' => '无']); ?>
                </td>
            </tr>
           
        </tbody>
        
    </table>
    
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
  
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    ChartAsset::register($this);
?>