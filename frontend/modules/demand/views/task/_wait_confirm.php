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
            <h4 class="modal-title" id="myModalLabel">是否确定</h4>
        </div>
        <div class="modal-body">

            <?php $form = ActiveForm::begin(['id' => 'form-wait-confirm']); ?>

                <?php
                    echo Html::encode('是否对各项评分无异议？');
                    echo Html::activeHiddenInput($model, 'status', ['value' => DemandTask::STATUS_COMPLETED]);
                    echo Html::activeHiddenInput($model, 'progress', ['value' => DemandTask::$statusProgress[DemandTask::STATUS_COMPLETED]]);
                    echo Html::activeHiddenInput($model, 'finished_at', ['value' => time()]);
                ?> 
            <?php ActiveForm::end(); ?>

        </div>
        <div class="modal-footer">
            <button id="submit-wait-confirm" class="btn btn-primary">确认</button>
        </div>
   </div>
</div> 

<script type="text/javascript">
    /** 承接操作 提交表单 */
    $("#submit-wait-confirm").click(function()
    {
        $('#form-wait-confirm').submit();
    });
</script>

<?php
    DemandAssets::register($this);
?>