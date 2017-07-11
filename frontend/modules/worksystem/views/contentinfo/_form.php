<?php

use common\models\worksystem\WorksystemContent;
use common\models\worksystem\WorksystemTask;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
//@var $model WorksystemContentinfo */
/* @var $model WorksystemTask */
/* @var $form ActiveForm */

?>

<div class="worksystem-contentinfo-form">
    
    <div class="contentinfo-table">
    
        <table class="table table-striped table-list">

            <thead>
                <tr>
                    <th style="width: 10%"><?= Yii::t('rcoa/worksystem', 'Type Name') ?></th>
                    <th style="width: 9%"><?= Yii::t('rcoa/worksystem', 'Is New') ?></th>
                    <th style="width: 25%"><?= Yii::t('rcoa/worksystem', 'Price') ?><span class="reference">（参考）</span></th>
                    <th style="width: 18%"><?= Yii::t('rcoa/worksystem', 'Budget Number') ?></th>
                    <th style="width: 15%"><?= Yii::t('rcoa/worksystem', 'Info Cost') ?><span class="reference">（单价×数量）</span></th>
                    <th style="width: 3%">
                        <?= Html::a('添加', ['contentinfo/create'], [
                            'id' => 'add-to',
                            'class' => 'btn btn-success btn-sm',
                        ]) ?>
                    </th>
                </tr>
            </thead>

            <tbody>
                
            <?php if(!isset($model)): ?>
                
            <?php else: ?>
                <?php foreach($infos as $item): ?>
                    
                <tr id="<?= $item['id'].'_'.$item['is_new'] ?>">
                    <td>
                        <?= $item['type_name'] ?>
                        <?= Html::input('hidden', 'WorksystemContentinfo['.$item['id'].'_'.$item['is_new'].'][worksystem_content_id]', $item['worksystem_content_id']) ?>
                    </td>
                    <?php if($item['is_new'] == WorksystemContent::MODE_NEWLYBUILD ): ?>
                    <td>
                        新建
                        <?= Html::input('hidden', 'WorksystemContentinfo['.$item['id'].'_'.$item['is_new'].'][is_new]', $item['is_new']) ?>
                    </td>
                    <td>
                        <?= Html::input('number', 'WorksystemContentinfo['.$item['id'].'_'.$item['is_new'].'][price]', $item['price'], [
                            'id' => 'Worksystemcontentinfo-price-'.$item['id'].'_'.$item['is_new'],
                            'class' => 'price',
                            'onblur' => 'infoCost()',
                        ]) ?>
                        <span class="reference">（￥<?= $item['price_new'].'/'.$item['unit'] ?>）</span>
                    </td>
                    <td>
                        <?= Html::input('number', 'WorksystemContentinfo['.$item['id'].'_'.$item['is_new'].'][budget_number]', $item['budget_number'], [
                            'id' => 'Worksystemcontentinfo-budget_number-'.$item['id'].'_'.$item['is_new'],
                            'class' => 'number',
                            'onblur' => 'infoCost()',
                        ]) ?>
                        <span class="reference">（<?= $item['unit'] ?>）</span>
                    </td>
                    <td>
                        ￥<span id="Worksystemcontentinfo-budget_cost-number-<?= $item['id'].'_'.$item['is_new'] ?>"><?= number_format($item['budget_cost'], 2, '.', ',') ?></span>
                        <?= Html::input('hidden', 'WorksystemContentinfo['.$item['id'].'_'.$item['is_new'].'][budget_cost]', $item['budget_cost'], [
                            'id' => 'Worksystemcontentinfo-budget_cost-cost-'.$item['id'].'_'.$item['is_new'],
                            'class' => 'info-cost',
                        ]) ?>
                    </td>
                    <?php else: ?>
                    <td>
                        改造
                        <?= Html::input('hidden', 'WorksystemContentinfo['.$item['id'].'_'.$item['is_new'].'][is_new]', $item['is_new']) ?>
                    </td>
                    <td>
                        <?= Html::input('number', 'WorksystemContentinfo['.$item['id'].'_'.$item['is_new'].'][price]', $item['price'], [
                            'id' => 'Worksystemcontentinfo-price-'.$item['id'].'_'.$item['is_new'],
                            'class' => 'price',
                            'onblur' => 'infoCost()',
                        ]) ?>
                        <span class="reference">（￥<?= $item['price_remould'].'/'.$item['unit'] ?>）</span>
                    </td>
                    <td>
                        <?= Html::input('number', 'WorksystemContentinfo['.$item['id'].'_'.$item['is_new'].'][budget_number]', $item['budget_number'], [
                            'id' => 'Worksystemcontentinfo-budget_number-'.$item['id'].'_'.$item['is_new'],
                            'class' => 'number',
                            'onblur' => 'infoCost()',
                        ]) ?>
                        <span class="reference">（<?= $item['unit'] ?>）</span>
                    </td>
                    <td>
                        ￥<span id="Worksystemcontentinfo-budget_cost-number-<?= $item['id'].'_'.$item['is_new'] ?>"><?= number_format($item['budget_cost'], 2, '.', ',') ?></span>
                        <?= Html::input('hidden', 'WorksystemContentinfo['.$item['id'].'_'.$item['is_new'].'][budget_cost]', $item['budget_cost'], [
                            'id' => 'Worksystemcontentinfo-budget_cost-cost-'.$item['id'].'_'.$item['is_new'],
                            'class' => 'info-cost',
                        ]) ?>
                    </td>
                    <?php endif; ?>
                    <td>
                        <?= Html::a('删除', null, [
                            'class' => 'btn btn-danger btn-sm',
                            'onclick' => 'removeAttr($(this))',
                        ]) ?>
                    </td>
                </tr>
                
                <?php endforeach; ?>
            <?php endif; ?>
                
            </tbody>

        </table>    
        
        <div id="prompt"></div>
        <div class="budget-cost">
            <?php if(!isset($model)): ?>
                总成本：￥<span id="Worksystemtask-budget_cost-number">0.00</span>
                <?= Html::hiddenInput('WorksystemTask[budget_cost]', 0.00, ['id' => 'Worksystemtask-budget_cost-value']) ?>
            <?php else: ?>
                成本：￥<span id="Worksystemtask-budget_cost-number"><?= number_format($model->budget_cost, 2, '.', ',') ?></span>
                <?= Html::hiddenInput('WorksystemTask[budget_cost]', $model->budget_cost, ['id' => 'Worksystemtask-budget_cost-value']) ?>
            <?php endif; ?>
        </div>
        
    </div>

</div>    

<?php
$js =   
<<<JS
        
    /** 添加操作 弹出模态框 */
    $('#add-to').click(function(){
        var value = $('#task_type_id-worksystemtask-task_type_id').val();
        if(value == ''){
            $('.myModal').modal("show");
        }else{
            var val = $('#task_type_id-worksystemtask-task_type_id').val();
            var href = $(this).attr("href")+'?task_type_id='+val;
            $(".myModal").html("");
            $('.myModal').modal("show").load(href);
        }
        return false;
    });     
        
    window.removeAttr = function (elem){
        $(elem).parent().parent().remove();
    };
    
    window.infoCost = function(){
        var totalCost = 0;
        $('.table tbody tr').each(function(){
            var price = $('#Worksystemcontentinfo-price-'+$(this).attr('id')).val();
            var number = $('#Worksystemcontentinfo-budget_number-'+$(this).attr('id')).val();
            var infocost = Number(price)*Number(number);
            $('#Worksystemcontentinfo-budget_cost-number-'+$(this).attr('id')).text(number_format(infocost, 2, '.', ','));
            $('#Worksystemcontentinfo-budget_cost-cost-'+$(this).attr('id')).val(infocost);
        });
        $('.info-cost').each(function(){
            totalCost += Number($(this).val());
        });
        $('#Worksystemtask-budget_cost-number').text(number_format(totalCost, 2, '.', ','));
        $('#Worksystemtask-budget_cost-value').val(totalCost);
    };
    
    /** 数字格式化 */
    function number_format(number, decimals, dec_point, thousands_sep) {  
        var n = !isFinite(+number) ? 0 : +number,  
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),  
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,  
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,  
            s = '',  
            toFixedFix = function (n, prec) {  
                var k = Math.pow(10, prec);  
                return '' + Math.round(n * k) / k;        };  
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;  
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');  
        if (s[0].length > 3) {  
            s[0] = s[0].replace(/(\d)(?=(?:\d{3})+$)/g, '$1'+sep);  
        }  
        if ((s[1] || '').length < prec) {  
            s[1] = s[1] || '';  
            s[1] += new Array(prec - s[1].length + 1).join('0');  
        }      
        return s.join(dec);  
    } 
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    WorksystemAssets::register($this);
?>