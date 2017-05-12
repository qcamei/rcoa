<?php

use common\models\demand\DemandTask;
use frontend\modules\demand\assets\DemandAssets;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model DemandTask */

?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">是否提交任务审核</h4>
        </div>
        <div class="modal-body">

            <?php $form = ActiveForm::begin(['id' => 'form-submit-check']); ?>

                <?php
                    if(empty($model->budget_cost))
                        echo Html::encode('工作项成本不能为空！');
                    else {
                        echo Html::encode('是否确定要提交该任务审核？');
                        echo Html::activeHiddenInput($model, 'status', ['value' => DemandTask::STATUS_CHECK]);
                        echo Html::activeHiddenInput($model, 'progress', ['value' => DemandTask::$statusProgress[DemandTask::STATUS_CHECK]]);
                    }    
                    
                ?> 
            <?php ActiveForm::end(); ?>

        </div>
        <div class="modal-footer">
            <?php if(empty($model->budget_cost)): ?>
            <button class="btn btn-primary" data-dismiss="modal" aria-label="Close">关闭</button>
            <?php else:?>
            <button id="submit-check-save" class="btn btn-primary">确认</button>
            <?php endif; ?>    
        </div>
   </div>
</div> 

<script type="text/javascript">
    /** 承接操作 提交表单 */
    $("#submit-check-save").click(function()
    {
        $('#form-submit-check').submit();
    });
</script>

<?php
    DemandAssets::register($this);
?>