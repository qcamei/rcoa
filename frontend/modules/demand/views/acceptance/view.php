<?php

use common\models\demand\DemandAcceptance;
use frontend\modules\demand\assets\ChartAsset;
use frontend\modules\demand\assets\DemandAssets;
use kartik\slider\Slider;
use wskeee\utils\DateUtil;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model DemandAcceptance */

//$this->title = Yii::t('rcoa/demand', 'Demand Acceptances');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Acceptances'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$percent = [];
foreach ($datas['workitemType'] as  $workitemType){
    if($datas['acceptance'][$workitemType['id']]['workitem_value'] == 0)
        $datas['acceptance'][$workitemType['id']]['workitem_value'] = 1;
    else 
        $datas['acceptance'][$workitemType['id']]['workitem_value'];
    $nums = $datas['acceptance'][$workitemType['id']]['deliver_value'] / $datas['acceptance'][$workitemType['id']]['workitem_value'] * 100;
    $percent[$workitemType['id']] = (int)ceil($nums);
}

$number = []; 
foreach ($datas['demandDelivery'] as $demandDelivery){
    if(!isset($number[$demandDelivery['workitem_type']]))
        $number[$demandDelivery['workitem_type']] = 0;
    $number[$demandDelivery['workitem_type']] ++;
}

?>
<div class="demand-acceptance-view has-title">
    
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
                <td class="text-center"><?= ArrayHelper::getValue($datas['timeDes'][$demand_task_id], 'demand_workitem_time') ?></td>
                <td class="text-center"><?= ArrayHelper::getValue($datas['timeDes'][$demand_task_id], 'delivery_time') ?></td>
                <td class="text-center"><?= ArrayHelper::getValue($datas['timeDes'][$demand_task_id], 'acceptance_time') ?></td>
            </tr>
            <?php $array = []; foreach ($datas['workitemType'] as  $workitemType): 
                if($percent[$workitemType['id']] < 0) $percent[$workitemType['id']] = 1; else  $percent[$workitemType['id']];
                if($percent[$workitemType['id']] < 70) $color = '#ff0000'; else if($percent[$workitemType['id']] < 100) $color = '#428BCA'; else $color = '#43c584';
            ?>
            <tr class="tr">
                <th class="text-center"><?= $workitemType['name'] ?></th>
                <td></td>
                <td></td>
                <td class="text-center">
                    <?php if($workitemType['name'] == '授'): ?>
                    <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">数量</div>
                    <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">质量</div>
                    <?php endif; ?>
                </td>
            </tr>
                
                <?php foreach ($datas['demandDelivery'] as $demandDelivery): ?>
                    <?php if($demandDelivery['workitem_type'] == $workitemType['id']): ?>
                    <tr>
                        <th class="text-right"><?= $demandDelivery['name'] ?></th>
                        <td style="width: 300px">
                        <?php rsort($demandDelivery['childs']); foreach ($demandDelivery['childs'] as $child): ?>                         
                        <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                             <?php if($child['value_type'] == true){
                                echo $child['is_new'] == true ? 
                                     Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']).DateUtil::intToTime($child['demand_workitem_value']) :
                                     Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']).DateUtil::intToTime($child['demand_workitem_value']);
                            }else{
                                echo $child['is_new'] == true ? 
                                    Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']).$child['demand_workitem_value'].$child['unit'] :
                                    Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']).$child['demand_workitem_value'].$child['unit'];
                            }?>
                        </div>
                        <?php endforeach; ?>
                        </td>
                        <td style="width: 300px">
                        <?php rsort($demandDelivery['childs']); foreach ($demandDelivery['childs'] as $child): ?>                         
                        <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                             <?php if($child['value_type'] == true){
                                echo $child['is_new'] == true ? 
                                     Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']).DateUtil::intToTime($child['deliver_data_value']) :
                                     Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']).DateUtil::intToTime($child['deliver_data_value']);
                            }else{
                                echo $child['is_new'] == true ? 
                                    Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']).$child['deliver_data_value'].$child['unit'] :
                                    Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']).$child['deliver_data_value'].$child['unit'];
                            }?>
                        </div>
                        <?php endforeach; ?>
                        </td>
                        <?php if(!isset($array[$workitemType['id']])): $array[$workitemType['id']] = true;?>
                        <td class="text-center" rowspan="<?= $number[$workitemType['id']] ?>">
                            <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                                <span class="chart" data-percent="<?= $percent[$workitemType['id']]; ?>" data-bar-color="<?= $color; ?>">
                                    <span class="percent"></span>
                                </span>        
                            </div>
                            <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12" style="margin-top: 25px;">
                            
                            <?= Slider::widget([
                                'id' => 'acceptance'.$workitemType['id'],
                                'name'=> 'value['.$workitemType['id'].']',
                                'value'=> (int)$datas['acceptance'][$workitemType['id']]['acceptance_data_value'],
                                'sliderColor'=> ((int)$datas['acceptance'][$workitemType['id']]['acceptance_data_value'] < 7 ? Slider::TYPE_DANGER : 
                                        ((int)$datas['acceptance'][$workitemType['id']]['acceptance_data_value'] < 10 ? Slider::TYPE_PRIMARY : Slider::TYPE_SUCCESS)),
                                'handleColor'=> ((int)$datas['acceptance'][$workitemType['id']]['acceptance_data_value'] < 7 ? Slider::TYPE_DANGER : 
                                        ((int)$datas['acceptance'][$workitemType['id']]['acceptance_data_value'] < 10 ? Slider::TYPE_PRIMARY : Slider::TYPE_SUCCESS)),   
                                'options' => [
                                   'style' => [
                                       'width' => '100%',
                                       'height' => '20px;'
                                   ],
                                ],
                                'pluginOptions'=>[
                                    'step' => 1,
                                    'tooltip'=>'always',
                                    'enabled' => false,
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
                <td><?= ArrayHelper::getValue($datas['timeDes'][$demand_task_id], 'demand_workitem_des') ?></td>
                <td><?= ArrayHelper::getValue($datas['timeDes'][$demand_task_id], 'delivery_des') ?></td>
                <td>
                    <div class="acceptance-pass">
                        <?php if(ArrayHelper::getValue($datas['timeDes'][$demand_task_id], 'pass') == 0): ?>
                        <a class="btn btn-danger">验收不通过</a>
                        <?php else: ?>
                        <a class="btn btn-success">验收通过</a>
                        <?php endif; ?>
                    </div>
                    <div class="acceptance-des">
                        <?= ArrayHelper::getValue($datas['timeDes'][$demand_task_id], 'acceptance_des') ?>
                    </div>
                </td>
            </tr>        
        </tbody>

    </table> 
    

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
    DemandAssets::register($this);
    ChartAsset::register($this);
?>