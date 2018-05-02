<?php

use common\models\need\NeedContentPsd;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model NeedContentPsd */
/* @var $form ActiveForm */

$allModels = [];
foreach ($contentPsds as $pad){
    $allModels[$pad->workitem_type_id][] = $pad;
}

?>

<div class="need-content-form">

    <?php $form = ActiveForm::begin(['id' => 'need-content-form', 'class' => 'form-horizontal']); ?>

    <table class="table table-bordered table-frame ">
        
        <thead>
            <tr class="rows">
                <th style="width: 55px;">类型</th>
                <th style="width: 150px;">名称</th>
                <th style="width: 210px;">新建</th>
                <th style="width: 210px;">改造</th>
                <th style="width: 40px;">单位</th>
            </tr>
        </thead>
        
        <tbody>
            <?php foreach ($allModels as $typeId => $itemModels): ?>
                <?php foreach ($itemModels as $model): ?>
                <tr class="<?= count($allModels[$typeId]) == 1 ? 'rows' : null ?>">
                    <?php if(!isset($isRowspan[$typeId])): $isRowspan[$typeId] = true; ?>
                    <td class="cols" rowspan="<?= count($allModels[$typeId]) ?>"><?= $model->workType->name ?></td>
                    <?php endif; ?>
                    <td id="<?= $model->workitem_id ?>">
                        <span class="<?= isset($modelContents[$typeId][$model->workitem_id]) ? 'danger' : ''?>" style="float: none">
                            <?= $model->workitem->name ?>
                        </span>
                    </td>
                    <td>
                        <?php
                            $new_number = isset($modelContents[$typeId][$model->workitem_id][0]) ? $modelContents[$typeId][$model->workitem_id][0] : 0;
                            echo Html::input('number', 'NeedContent[number][' . $model->workitem_id . '][]', $new_number, [
                                'class' => $new_number > 0 ? 'danger' : '', 
                                'data-workid' => $model->workitem_id,
                                'data-price' => $model->price_new,
                                'min' => 0, 
                                'onfocus' => 'focusObject($(this));',
                                'onblur' => 'blurObject($(this));'
                            ]) . '<span class="stamp">（￥' . $model->price_new . '）</span>' 
                        ?>
                    </td>
                    <td>
                        <?php
                            $remould_number = isset($modelContents[$typeId][$model->workitem_id][1]) ? $modelContents[$typeId][$model->workitem_id][1] : 0;
                            echo Html::input('number', 'NeedContent[number][' . $model->workitem_id . '][]', $remould_number, [
                                'class' => $remould_number > 0 ? 'danger' : '', 
                                'data-workid' => $model->workitem_id,
                                'data-price' => $model->price_remould,
                                'min' => 0,
                                'onblur' => 'blurObject($(this));',
                                'onblur' => 'blurObject($(this));'
                            ]) . '<span class="stamp">（￥' . $model->price_remould . '）</span>' 
                        ?>
                    </td>
                    <td><?= $model->workitem->unit ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </tbody>
        
    </table>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = 
<<<JS
    
    window.focusObject = function(elem){
        //计算内容成本
        var total = $('#needtask-reality_content_cost').val();
        var cost = elem.val() * elem.attr('data-price');
        $('#needtask-reality_content_cost').val(Number(total) - Number(cost));
    }
        
    window.blurObject = function(elem){
        elem.addClass('danger');
        if(elem.val() == '' || elem.val() == 0){
            elem.val(0);
            elem.removeClass('danger');
        }
        $('#' + elem.attr('data-workid') + '> span').addClass('danger');
        //计算内容成本
        var total = $('#needtask-reality_content_cost').val();
        var cost = elem.val() * elem.attr('data-price');
        $('#needtask-reality_content_cost').val(Number(total) + Number(cost));
    }
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>