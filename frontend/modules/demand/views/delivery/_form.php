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
$worktime = ArrayHelper::getColumn($workitem, 'demand_time');
$workdes = ArrayHelper::getColumn($workitem, 'des');

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
                <td class="text-center"><?= reset($worktime) ?></td>
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
                                    Html::input('number', 'value['.$child['id'].']', 0, ['class' => 'form-control  col-xs-9 workitem-input', 'min' => 0]) :
                                    Html::input('number', 'value['.$child['id'].']', 0, ['class' => 'form-control  col-xs-9 workitem-input', 'min' => 0]);
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
                <th class="text-center">备注</th>
                <td><?= reset($workdes) ?></td>
                <td><?=  Html::textarea('des', '无', ['class' => 'form-control', 'rows' => 4]); ?></td>
            </tr>
        </tbody>
        
    </table>
        
    <?php ActiveForm::end(); ?>
    
</div>

