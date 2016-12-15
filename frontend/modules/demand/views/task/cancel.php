<?php

use frontend\modules\demand\assets\DemandAssets;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">取消原因</h4>
</div>
<div class="modal-body">

    <?php $form = ActiveForm::begin(['id' => 'form-cancel']); ?>

        <?= Html::textInput('reason', null, ['class' => 'form-control', 'placeholder'=>'请输入取消原因...']) ?>

    <?php ActiveForm::end(); ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" id="cancel-save">确认</button>
</div>

<script type="text/javascript">
    /** 取消操作 提交表单 */
    $(" #cancel-save").click(function()
    {
        $('#form-cancel').submit();
    });
</script>

<?php
    DemandAssets::register($this);
?>