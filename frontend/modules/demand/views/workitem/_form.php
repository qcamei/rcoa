<?php

use common\models\demand\DemandWorkitem;
use kartik\widgets\TouchSpin;
use wskeee\utils\DateUtil;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DemandWorkitem */
/* @var $form ActiveForm */

$allModel = [];
foreach ($allModels as $model) {
    $allModel[$model->workitemType->name][$model->workitem->name][] = [
        'id' => $model->id,
        'is_new' => $model->is_new,
        'value_type' => $model->value_type,
        'cost' => $model->cost,
        'value' => $model->value,
        'unit' => $model->workitem->unit
    ];
}

//$allModel = ArrayHelper::index($allModel, null, ['workitemTypeName']);
//var_dump($allModel);
//exit;
?>

<div class="demand-workitem-form">
            
        <table class="table table-bordered demand-workitem-table">
           
            <thead>
                <tr>
                    <th></th>
                    <td class="text-center"><?= Html::img(['/filedata/demand/image/mode_newbuilt.png'], ['style' => 'margin-right: 10px;']); ?>新建</td>
                    <td class="text-center"><?= Html::img(['/filedata/demand/image/mode_reform.png'], ['style' => 'margin-right: 10px;']); ?>改造</td>
                </tr>
            </thead>
            
            <tbody>
                <?php foreach ($allModel as $index => $models): ?>
                <tr class="tr">
                    <th class="text-center"><?= $index ?></th>
                    <td></td>
                    <td></td>
                </tr>
                    <?php foreach ($models as $keys => $elements): ?>
                        <tr>
                            <th class="text-right"><?= $keys ?></th>
                            <?php rsort($elements); foreach ($elements as $value): ?>
                            <td>
                                <?php if($value['value_type'] == true): ?>
                                    <div class="col-lg-5 col-md-7 col-sm-7 col-xs-12">
                                    <?= Html::input('text', 'value['.$value['id'].']', DateUtil::intToTime($value['value']), [
                                        'class' => 'form-control workitem-input'
                                    ]) ?>
                                    </div>
                                <?php else: ?>
                                    <div class="col-lg-4 col-md-6 col-sm-6 col-xs-12">
                                    <?= TouchSpin::widget([
                                        'name' => 'value['.$value['id'].']',
                                        'options' => [
                                            'class' => 'input-sm workitem-input',
                                            'style' => 'padding-left: 5px;',
                                            'placeholder' => '数量...'
                                        ],
                                        'pluginOptions' => [
                                            'initval' => $value['value'],
                                            'min' => 1,
                                            'max' => 99999,
                                            'buttonup_class' => 'btn btn-default btn-sm', 
                                            'buttondown_class' => 'btn btn-default btn-sm', 
                                            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>', 
                                            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
                                        ],
                                    ]); ?>
                                    </div>
                                <?php endif; ?>
                                <div class="workitem-tooltip" data-toggle="tooltip" data-placement="top" title="￥<?= $value['cost'] ?> / <?= $value['unit'] ?>"></div>
                                <div class="cost-unit"><span>( ￥<?= $value['cost'] ?> / <?= $value['unit'] ?> )</span></div>
                            </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
                        
        </table> 

</div>


<?php
$js =   
<<<JS
    var width = $(document).width();
    if(width <= 480){
        $('.col-xs-12').each(function(index, elem){
            $(elem).children('.workitem-input').focus(function(){
                $(elem).next('.workitem-tooltip').tooltip('show');
            });
            $(elem).children('.workitem-input').blur(function(){
                $(elem).next('.workitem-tooltip').tooltip('hide');
            });
            $(elem).children('.input-group').children('.workitem-input').focus(function(){
                $(elem).next('.workitem-tooltip').tooltip('show');
            });
            $(elem).children('.input-group').children('.workitem-input').blur(function(){
                $(elem).next('.workitem-tooltip').tooltip('hide');
            });
        });    
    }         
   
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>