<?php

use common\models\demand\DemandTask;
use frontend\modules\demand\assets\DemandAssets;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">是否提交任务</h4>
</div>
<div class="modal-body">

    <?php $form = ActiveForm::begin(['id' => 'form-submit-task']); ?>

        <?php
            if($isEmpty){
                echo Html::encode('是否确定要提交该任务？');
                echo Html::activeHiddenInput($model, 'status', ['value' => DemandTask::STATUS_ACCEPTANCE]);
                echo Html::activeHiddenInput($model, 'progress', ['value' => DemandTask::$statusProgress[DemandTask::STATUS_ACCEPTANCE]]);
            }else
                echo Html::encode('课程开发数据不能为空！');
               
        ?> 
    <?php ActiveForm::end(); ?>

</div>

<?php
    echo Html::beginTag('div', ['class' => 'modal-footer']);
    if($isEmpty)
        echo Html::button('确认', ['id' => 'submit-task-save', 'class' => 'btn btn-primary']);
    echo Html::endTag('div');
?>

<script type="text/javascript">
    /** 承接操作 提交表单 */
    $("#submit-task-save").click(function()
    {
        $('#form-submit-task').submit();
    });
</script>

<?php
    DemandAssets::register($this);
?>