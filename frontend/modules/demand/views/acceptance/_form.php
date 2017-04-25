<?php

use common\models\demand\DemandAcceptance;
use common\models\demand\DemandDelivery;
use frontend\modules\demand\assets\ChartAsset;
use kartik\slider\Slider;
use wskeee\utils\DateUtil;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DemandAcceptance */
/* @var $form ActiveForm */
/* @var $delivery DemandDelivery */

?>

<div class="demand-acceptance-form">

    <?php $form = ActiveForm::begin(['id' => 'demand-acceptance-form']); ?>

    <table class="table table-bordered demand-workitem-table">

            <thead>
                <tr>
                    <th></th>
                    <td class="text-center">需求</td>
                    <td class="text-center">交付</td>
                    <td class="text-center">验收</td>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <th class="text-center">时间</th>
                    <td class="text-center"><?= $model->demandTask->plan_check_harvest_time ?></td>
                    <td class="text-center"><?= date('Y-m-d H:i', $delivery->created_at) ?></td>
                    <td class="text-center"><?= date('Y-m-d H:i', time()) ?></td>
                </tr>
                <?php $array = []; foreach ($wdArrays as  $items): 
                    if($percentage[$items['id']] == NUll) $percentage[$items['id']] = 100; else $percentage[$items['id']];
                    if($percentage[$items['id']] < 70) $color = '#ff0000'; else if($percentage[$items['id']] < 100) $color = '#428BCA'; else $color = '#43c584';
                ?>
                <tr class="tr">
                    <th class="text-center"><?= $items['name'] ?></th>
                    <td></td>
                    <td></td>
                    <td class="text-center">
                        <?php if($items['name'] == '授'): ?>
                        <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">数量</div>
                        <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">质量</div>
                        <?php endif; ?>
                    </td>
                </tr>
                    <?php foreach ($items['childs'] as $childs): ?>
                    <tr>
                        <th class="text-right"><?= $childs['name'] ?></th>
                        <td style="width: 300px">
                        <?php rsort($childs['childs']); foreach ($childs['childs'] as $child): ?> 
                            <?php if($child['is_workitem'] == true): ?>
                                <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                                <?= $child['is_new'] == true ? 
                                        Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']).$child['value'].$child['unit'] :
                                        Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']).$child['value'].$child['unit'];
                                ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>    
                        </td>
                        <td style="width: 300px">
                        <?php rsort($childs['childs']); foreach ($childs['childs'] as $child): ?> 
                            <?php if($child['is_workitem'] == false): ?>
                                <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                                <?= $child['is_new'] == true ? 
                                        Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']).$child['value'].$child['unit'] :
                                        Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']).$child['value'].$child['unit'];
                                ?>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        </td>
                        <?php if(!isset($array[$items['id']])): $array[$items['id']] = true;$number = count($items['childs']); ?>
                        <td class="text-center" rowspan="<?= $number ?>">
                            <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                                <span class="chart" data-percent="<?= $percentage[$items['id']] ?>" data-bar-color="<?= $color; ?>">
                                    <span class="percent" style="color: <?= $color; ?>"></span>
                                </span>        
                            </div>
                            <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12" style="margin-top: 25px;">
                                <?= Slider::widget([
                                    'class' => 'acceptance-value',
                                    'name'=> 'value['.$items['id'].']',
                                    'value'=> $percentage[$items['id']] >= 100 ?  10 : 0,
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
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <tr class="tr">
                    <th class="text-center">备注</th>
                    <td><?= $model->demandTask->des; ?></td>
                    <td><?= $delivery->des; ?></td>
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

    <?= Html::activeHiddenInput($model, 'demand_delivery_id', ['value' => $delivery->id]); ?>
    
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