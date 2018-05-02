<?php

use common\models\need\NeedTaskUser;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;


/* @var $this View */
/* @var $model NeedTaskUser */

$this->title = '取消';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Need Task Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="need-task-audit">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(['options'=>['id' => 'need-task-form','class'=>'form-horizontal']]); ?>

                <div class="form-group field-needtask-remarks">
                    <label class="col-lg-1 col-md-1 control-label form-label" for="needtask-remarks">备注：</label>
                    <div class="col-lg-11 col-md-11">
                        <?= Html::textarea('remarks', '无', ['rows' => 8, 'class' => 'form-control']) ?>
                    </div>
                </div>
                
                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <?= Html::button(Yii::t('app', 'Confirm'), [
                    'id' => 'submitsave','class' => 'btn btn-primary','data-dismiss' => 'modal','aria-label' => 'Close'
                ]) ?>
            </div>
       </div>
    </div>

</div>

<?php
$js = 
<<<JS
   
    /** 提交表单 */
    $("#submitsave").click(function(){
        $('#need-task-form').submit();
    });   
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>