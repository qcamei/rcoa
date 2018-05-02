<?php

use common\models\worksystem\WorksystemContent;
use common\models\worksystem\WorksystemTask;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;


/* @var $this View */
/* @var $model WorksystemTask */

$this->title = Yii::t('rcoa/worksystem', 'Worksystem Tasks');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="worksystem worksystem-task-submit_check">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">提交验收</h4>
            </div>
            <div class="modal-body">
                    
                <?php $form = ActiveForm::begin([
                    'options'=>[
                        'id' => 'worksystem-task-form',
                        'class'=>'form-horizontal',
                    ],
                    'fieldConfig' => [  
                        'template' => "{label}\n<div class=\"col-lg-10 col-md-10\">{input}</div>\n<div class=\"col-lg-10 col-md-10\">{error}</div>",  
                        'labelOptions' => [
                            'class' => 'col-lg-1 col-md-1 control-label',
                            'style'=>[
                                'color'=>'#999999',
                                'font-weight'=>'normal',
                                'padding-right' => '0'
                            ]
                        ],  
                    ], 
                ]); ?>
                
                <div class="form-group field-worksystemoperation-des">
                    <label class="col-lg-1 col-md-1 control-label" style="color: #999999; font-weight: normal; padding-right: 0;" for="worksystemoperation-des">
                        <b>内容：</b>
                    </label>
                </div>
                
                <div class="contentinfo-table">
                    
                    <table class="table table-striped table-list">

                        <thead>
                            <tr>
                                <th style="width: 25%"><?= Yii::t('rcoa/worksystem', 'Type Name') ?></th>
                                <th style="width: 25%"><?= Yii::t('rcoa/worksystem', 'Is New') ?></th>
                                <th style="width: 25%"><?= Yii::t('rcoa/worksystem', 'Budget Number') ?></th>
                                <th style="width: 25%"><?= Yii::t('rcoa/worksystem', 'Reality Number') ?></th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php foreach($infos as $item): ?>
                            
                            <tr>
                                <td>
                                    <?= $item['type_name'] ?>
                                </td>
                                <?php if($item['is_new'] == WorksystemContent::MODE_NEWLYBUILD ): ?>
                                <td>新建</td>
                                <?php else: ?>
                                <td>改造</td>
                                <?php endif; ?>
                                <td>
                                    <?= $item['budget_number'] ?><span class="reference"><?= $item['unit'] ?></span>
                                </td>
                                <td>
                                    <?= Html::input('number', 'WorksystemContentinfo['.$item['info_id'].'][reality_number]', $item['reality_number'] == 0 ? $item['budget_number'] : $item['reality_number'], [
                                        'id' => $item['info_id'],
                                        'class' => 'number',
                                        'data-price' => $item['price'],
                                        'onblur' => 'infoCost()',
                                    ]) ?>
                                    <?= Html::input('hidden', 'WorksystemContentinfo['.$item['info_id'].'][reality_cost]', $item['reality_cost'] == 0 ? $item['budget_cost'] : $item['reality_cost'], [
                                        'id' => 'Worksystemcontentinfo-reality_cost-'.$item['info_id'],
                                        'class' => 'info-cost',
                                    ]) ?>
                                    <span class="reference"><?= $item['unit'] ?></span>
                                </td>
                            </tr>
                            
                            <?php endforeach;?>

                        </tbody>

                    </table>
                    
                </div>
                    
                <div class="form-group field-worksystemoperation-des">
                    <label class="col-lg-1 col-md-1 control-label" style="color: #999999; font-weight: normal; padding-right: 0;" for="worksystemoperation-des">
                        <?= Yii::t('rcoa/worksystem', 'Des') ?>
                    </label>
                    <div class="col-lg-10 col-md-10">
                         <textarea id="worksystemoperation-des" class="form-control" name="WorksystemOperation[des]" rows="4" aria-invalid="false">无</textarea>
                    </div>
                    <div class="col-lg-10 col-md-10"><div class="help-block"></div></div>
                </div>
                
                <?php 
                    if($model->getIsStatusWorking()){
                        echo Html::activeHiddenInput($model, 'status', ['value' => WorksystemTask::STATUS_WAITACCEPTANCE]);
                        echo Html::activeHiddenInput($model, 'progress', ['value' => WorksystemTask::$statusProgress[WorksystemTask::STATUS_WAITACCEPTANCE]]);
                    }else{
                        echo Html::activeHiddenInput($model, 'status', ['value' => WorksystemTask::STATUS_ACCEPTANCEING]);
                        echo Html::activeHiddenInput($model, 'progress', ['value' => WorksystemTask::$statusProgress[WorksystemTask::STATUS_ACCEPTANCEING]]);
                    }
                ?>
                
                <?= Html::activeHiddenInput($model, 'reality_cost', [
                    'value' => $model->reality_cost == 0 ? $model->budget_cost : $model->reality_cost,
                ]) ?>
                
                <?php ActiveForm::end(); ?>
                
            </div>
            <div class="modal-footer">
                <button id="submit-save" class="btn btn-primary" data-dismiss="modal" aria-label="Close">确认</button>
            </div>
       </div>
    </div>

</div>

<?php
$js =   
<<<JS
        
    window.infoCost = function(){
        var totalCost = 0;
        $('.number').each(function(){
            var price = $(this).attr('data-price');
            var number =$(this).val();
            var infocost = Number(price)*Number(number);
            $('#Worksystemcontentinfo-reality_cost-'+$(this).attr('id')).val(infocost);
        });
        $('.info-cost').each(function(){
            totalCost += Number($(this).val());
        });
        $('#worksystemtask-reality_cost').val(totalCost);
    };   
        
        
    $('#submit-save').click(function()
    {
        $('#worksystem-task-form').submit();
    });
        
JS;
    $this->registerJs($js,  View::POS_READY);
?>