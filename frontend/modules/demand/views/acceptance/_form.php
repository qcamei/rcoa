<?php

use common\models\demand\DemandAcceptance;
use common\models\demand\DemandDelivery;
use kartik\widgets\TouchSpin;
use wskeee\utils\DateUtil;
use yii\helpers\Html;
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
                <?php foreach ($wdArrays as $index => $items): ?>
                <tr class="tr">
                    <th class="text-center"><?= $index ?></th>
                    <td></td>
                    <td></td>
                    <td class="text-center">
                        <?php if($index == '授'): ?>
                        <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">数量</div>
                        <div class="col-lg-6 col-md-7 col-sm-7 col-xs-12">质量</div>
                        <?php endif; ?>
                    </td>
                </tr>
                    <?php foreach ($items as $keys => $elements): ?>
                        <tr>
                            <th class="text-right"><?= $keys ?></th>
                            <td style="width: 300px">
                            <?php rsort($elements); foreach ($elements as $value): ?>
                                <?php if($value['is_workitem'] == true): ?>
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
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </td>
                            <td style="width: 300px">
                            <?php rsort($elements); foreach ($elements as $value): ?>
                                <?php if($value['is_workitem'] == false): ?>
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
                                <?php endif; ?>
                            <?php endforeach; ?>
                            </td>
                            <?= $keys;  ?>
                             
                            <td rowspan="3"></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
                <tr class="tr">
                    <th class="text-center">备注</th>
                    <td><?= $model->demandTask->des; ?></td>
                    <td><?= $delivery->des; ?></td>
                    <td><?=  Html::textarea('des', '无', ['class' => 'form-control', 'rows' => 4]); ?></td>
                </tr>
            </tbody>

        </table> 

    
    <?php ActiveForm::end(); ?>

</div>
