<?php

use common\models\demand\DemandTask;
use frontend\modules\demand\assets\DemandAssets;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">是否完成</h4>
</div>
<div class="modal-body">

    <?php $form = ActiveForm::begin(['id' => 'form-complete']); ?>

        <?= Html::encode('是否确定完成该任务？') ?>
        <?= Html::activeHiddenInput($model, 'status', ['value' => DemandTask::STATUS_COMPLETED])?>
        <?= Html::activeHiddenInput($model, 'progress', ['value' => $model->getStatusProgress()])?>
        <?= Html::activeHiddenInput($model, 'reality_check_harvest_time', ['value' => date('Y-m-d H:i', time())])?>

    <?php ActiveForm::end(); ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" id="complete-save">确认</button>
</div>

<script type="text/javascript">
    /** 完成操作 提交表单 */
    $(" #complete-save").click(function()
    {
        $('#form-complete').submit();
    });
</script>

<?php
    DemandAssets::register($this);
?>