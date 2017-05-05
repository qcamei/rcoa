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
        
    <div class="bs-example bs-example-tabs" data-example-id="togglable-tabs">
        <ul id="myTabs" class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#demand" role="tab" id="demand-tab"  data-toggle="tab" aria-controls="demand" aria-expanded="true">需求</a></li>
            <li role="presentation" class=""><a href="#deliver" role="tab" id="deliver-tab" data-toggle="tab" aria-controls="deliver" aria-expanded="false">交付</a></li>
        </ul>
        <br />
        <div id="myTabContent" class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="demand" aria-labelledby="demand-tab">
                <table class="table table-bordered demand-workitem-table">
                    <tbody>
                        <tr>
                            <th class="text-center" style="width: 100px">时间</th>
                            <td class="text-center" style="width: 400px"><?= reset($worktime) ?></td>
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
                            <td class="text-center" style="width: 400px"><?= date('Y-m-d H:i', time()) ?></td>
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
                                    <?php rsort($work['childs']); foreach ($work['childs'] as $child): ?>                         
                                        <div class="col-xs-6" style="padding: 0px">
                                            <div class="col-xs-8" style="padding: 0px 5px;">
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
                            <td><?=  Html::textarea('des', '无', ['class' => 'form-control', 'rows' => 4]); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
        
    <?php ActiveForm::end(); ?>
    
</div>

