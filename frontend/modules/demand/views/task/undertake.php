<?php

use common\models\demand\DemandTask;
use frontend\modules\demand\assets\DemandAssets;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h4 class="modal-title" id="myModalLabel">是否承接</h4>
</div>
<div class="modal-body">

    <?php $form = ActiveForm::begin(['id' => 'form-undertake']); ?>

        <?php
            if(is_array($team)){
                echo $form->field($model, 'team_id')->widget(Select2::classname(), [
                    'id' => 'demandtask-team_id', 'data' => $team, 'options' => ['placeholder' => '请选择...']
                ]);
                echo $form->field($model, 'undertake_person')->widget(Select2::classname(), [
                    'id' => 'demandtask-undertake_person', 'data' => $undertake, 'options' => ['placeholder' => '请选择...']
                ]);
            }
            else{
                echo Html::encode('是否确定要承接该任务？');
                echo Html::activeHiddenInput($model, 'team_id', ['value' => $team]);
                echo Html::activeHiddenInput($model, 'undertake_person', ['value' => $undertake]);
            }
        ?> 
        <?= Html::activeHiddenInput($model, 'status', ['value' => DemandTask::STATUS_DEVELOPING])?>
        <?= Html::activeHiddenInput($model, 'progress', ['value' => $model->getStatusProgress()])?>

    <?php ActiveForm::end(); ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-primary" id="undertake-save">确认</button>
</div>

<script type="text/javascript">
    /** 承接操作 提交表单 */
    $(" #undertake-save").click(function()
    {
        $('#form-undertake').submit();
    });
</script>

<?php
    DemandAssets::register($this);
?>