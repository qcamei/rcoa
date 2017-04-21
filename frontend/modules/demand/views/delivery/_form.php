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
                            <td style="width: 450px;">
                            <?php rsort($elements); foreach ($elements as $value): ?>
                                <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">
                                <?php if($value['value_type'] == true){
                                   echo $value['is_new'] == true ? 
                                        Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']).DateUtil::intToTime($value['value']) :
                                        Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']).DateUtil::intToTime($value['value']);
                                }else{
                                    echo $value['is_new'] == true ? 
                                        Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']).$value['value'].$value['unit'] :
                                        Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']).$value['value'].$value['unit'];
                                }?>
                                </div>
                            <?php endforeach; ?>
                            </td>
                            <td class="text-center" style="min-width: 100px;">
                                <?php rsort($elements); foreach ($elements as $value): ?>
                                <?php if($value['value_type'] == true){
                                   echo '<div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">';
                                   echo $value['is_new'] == true ? 
                                        Html::input('text', 'value['.$value['id'].']', DateUtil::intToTime(0), ['class' => 'form-control workitem-input']) :
                                        Html::input('text', 'value['.$value['id'].']', DateUtil::intToTime(0), ['class' => 'form-control workitem-input']);
                                   echo '</div>';
                                }else{
                                    echo '<div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">';
                                        echo '<div class="col-lg-7" style="padding:0px;">';
                                        echo $value['is_new'] == true ? 
                                            TouchSpin::widget([
                                                'name' => 'value['.$value['id'].']',
                                                'options' => [
                                                    'class' => 'input-sm workitem-input',
                                                    'style' => 'padding-left: 5px;',
                                                    'placeholder' => '数量...'
                                                ],
                                                'pluginOptions' => [
                                                    'initval' => 0,
                                                    'min' => 0,
                                                    'max' => 99999,
                                                    'buttonup_class' => 'btn btn-default btn-sm', 
                                                    'buttondown_class' => 'btn btn-default btn-sm', 
                                                    'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>', 
                                                    'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
                                                ],
                                            ]) :
                                            TouchSpin::widget([
                                            'name' => 'value['.$value['id'].']',
                                            'options' => [
                                                'class' => 'input-sm workitem-input',
                                                'style' => 'padding-left: 5px;',
                                                'placeholder' => '数量...'
                                            ],
                                            'pluginOptions' => [
                                                'initval' => 0,
                                                'min' => 0,
                                                'max' => 99999,
                                                'buttonup_class' => 'btn btn-default btn-sm', 
                                                'buttondown_class' => 'btn btn-default btn-sm', 
                                                'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>', 
                                                'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
                                            ],
                                        ]);
                                        echo '</div>';
                                    echo '</div>';
                                }?>
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

