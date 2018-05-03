<?php

use common\models\need\NeedContent;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model NeedContent */
/* @var $form ActiveForm */

$this->title = '进度';
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Need Contents'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="need-content-update">
    
    <div class="modal-dialog" role="document" style="width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="modal-body">
                
                <?= $this->render('_form', [
                    'need_task_id' => $need_task_id,
                    'isRowspan' => [],
                    'contentPsds' => $contentPsds,
                    'modelContents' => $modelContents,
                ]) ?>
                
                <div class="need-task-form">
                    <?php $form = ActiveForm::begin([
                        'options' => [ 'id' => 'need-task-form', 'class' => 'form-horizontal'],
                        'action' => ['content/submit', 'id' => $need_task_id]
                    ]) ?>

                    <div class="form-group field-needtask-reality_outsourcing_cost">
                        <label class="col-lg-1 col-md-1 control-label form-label" for="needtask-reality_outsourcing_cost">外包费用：</label>
                        <div class="col-lg-11 col-md-11">
                            <?= Html::textInput('NeedTask[reality_outsourcing_cost]', '0.00', ['type' => 'number', 'class' => 'form-control', 'min' => 0]) ?>
                        </div>
                    </div>

                    <div class="form-group field-needtask-save_path">
                        <label class="col-lg-1 col-md-1 control-label form-label" for="needtask-save_path">成品路径：</label>
                        <div class="col-lg-11 col-md-11">
                            <?= Html::textInput('NeedTask[save_path]', null, ['class' => 'form-control', 'placeholder' => '最终成品保存路径，提交验收时填写...']) ?>
                        </div>
                    </div>
                    
                    <?= Html::hiddenInput('NeedTask[reality_content_cost]', $totalCost, ['id' => 'needtask-reality_content_cost']) ?>
                    
                    <?php ActiveForm::end(); ?>
                </div>
                
            </div>
            <div class="modal-footer">
                <span class="stamp" style="float: left; font-size: 14px">【保存进度】只保存数据，不触发【提交验收】操作</span>
                <?php
                    echo Html::button('重置', [
                        'id' => 'reset', 'class' =>'btn btn-danger', 'data-dismiss'=> '', 'aria-label' => 'Close'
                    ]);
                    echo Html::button('保存进度', [
                        'id' => 'submitsave', 'class' =>'btn btn-primary', 'data-dismiss'=> 'modal', 'aria-label' => 'Close'
                    ]);
                    echo Html::button('提交验收', [
                        'id' => 'submitcheck', 'class' =>'btn btn-success', 'data-dismiss'=> 'modal', 'aria-label' => 'Close'
                    ]);
                ?>
            </div>
       </div>
    </div>

</div>

<?php
$js = 
<<<JS
    
    //重置
    $('#reset').click(function(){
        $('#need-content-form input').removeClass('danger').val(0);
        $('.table tr td').find('span').removeClass('danger');
    });
    //保存进度
    $('#submitsave').click(function(){
        $('#need-content-form').submit();
    });
    //提交验收
    $('#submitcheck').click(function(){
        $.post('../content/create?need_task_id=$need_task_id&isNewRecord=0', $('#need-content-form').serialize());
        $.post('../content/submit?id=$need_task_id', $('#need-task-form').serialize());
        window.location.href = "../task/view?id=$need_task_id";
    });
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>