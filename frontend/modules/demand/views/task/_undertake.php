<?php

use common\models\demand\DemandTask;
use frontend\modules\demand\assets\DemandAssets;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="modal-dialog" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">是否承接</h4>
        </div>
        <div class="modal-body">

            <?php $form = ActiveForm::begin(['id' => 'form-undertake']); ?>
            
            <?php if(count($teams) == 1) echo Html::encode('是否确定要承接该任务？'); ?>
            
            <?= $form->field($model, 'team_id', [
                'labelOptions' => [
                    'style' => [
                        'padding-left' => '0',
                        'display' =>  count($teams) > 1 ? 'block' : 'none'
                     ]
                ]
            ])->dropDownList($teams, ['style ' => count($teams) > 1 ? 'display:block' : 'display:none']) ?>

            <?= Html::activeHiddenInput($model, 'undertake_person', ['value' => Yii::$app->user->id]) ?>
            
            <?php // Html::activeHiddenInput($model, 'develop_principals', ['value' => Yii::$app->user->id]) ?>

            <?= Html::activeHiddenInput($model, 'status', ['value' => DemandTask::STATUS_DEVELOPING])?>
            
            <?= Html::activeHiddenInput($model, 'progress', ['value' => DemandTask::$statusProgress[DemandTask::STATUS_DEVELOPING]])?>
                        
            <?php ActiveForm::end(); ?>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="undertake-save">确认</button>
        </div>
   </div>
</div>         

<script type="text/javascript">
    /** 承接操作 提交表单 */
    $("#undertake-save").click(function()
    {
        $('#form-undertake').submit();
    });
</script>

<?php
    DemandAssets::register($this);
?>