<?php

use common\models\demand\DemandDelivery;
use common\models\demand\DemandWorkitem;
use kartik\widgets\TouchSpin;
use wskeee\utils\DateUtil;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DemandDelivery */
/* @var $form ActiveForm */


$workitem = [];
foreach ($workitems as $data) {
    /* @var $data DemandWorkitem */
    $workitem[$data->workitemType->name][$data->workitem->name][] = [
        'id' => $data->id,
        'is_new' => $data->is_new,
        'value_type' => $data->value_type,
        'cost' => $data->cost,
        'value' => $data->value,
        'unit' => $data->workitem->unit,
    ];
}

?>

<div class="demand-delivery-form">
    
    <?php $form = ActiveForm::begin(['id' => 'demand-delivery-form']); ?>
        
        <table class="table table-bordered demand-workitem-table">

            <thead>
                <tr>
                    <th></th>
                    <td class="text-center">需求</td>
                    <td class="text-center">交付</td>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <th class="text-center">时间</th>
                    <td class="text-center"><?= $model->demandTask->plan_check_harvest_time ?></td>
                    <td class="text-center"><?= date('Y-m-d H:i', time()) ?></td>
                </tr>
                <?php foreach ($workitem as $index => $items): ?>
                <tr class="tr">
                    <th class="text-center"><?= $index ?></th>
                    <td></td>
                    <td class="text-center">
                        <?php if($index == '授'): ?>
                        <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                            <?= Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']) ?>新建
                        </div>
                        <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                            <?= Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']) ?>改造
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
                    <?php foreach ($items as $keys => $elements): ?>
                        <tr>
                            <th class="text-right"><?= $keys ?></th>
                            <td class="text-center" style="width: 450px;">
                            <?php rsort($elements); foreach ($elements as $value): ?>
                                <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                                <?= $value['is_new'] == true ? 
                                        Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']).$value['value'].$value['unit'] :
                                        Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']).$value['value'].$value['unit'];
                                ?>
                                </div>
                            <?php endforeach; ?>
                            </td>
                            <td class="text-center" style="min-width: 100px;">
                                
                                <?php rsort($elements); foreach ($elements as $value): ?>
                                <div class="col-xs-6">
                                    <div class="col-xs-9">
                                    <?= $value['is_new'] == true ? 
                                            Html::input('number', 'value['.$value['id'].']', 0, ['class' => 'form-control  col-xs-9 workitem-input', 'min' => 0]) :
                                            Html::input('number', 'value['.$value['id'].']', 0, ['class' => 'form-control  col-xs-9 workitem-input', 'min' => 0]);
                                    ?>
                                    </div>
                                    <div class="unit"><?= $value['unit'] ?></div>
                                </div>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <tr class="tr">
                    <th class="text-center">备注</th>
                    <td><?= $model->demandTask->des; ?></td>
                    <td><?=  Html::textarea('des', '无', ['class' => 'form-control', 'rows' => 4]); ?></td>
                </tr>
            </tbody>

        </table> 
        
    <?php ActiveForm::end(); ?>
    
</div>

