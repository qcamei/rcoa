<?php

use common\models\demand\DemandTask;
use common\models\worksystem\WorksystemTask;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;


/* @var $this View */
/* @var $model WorksystemTask */

$this->title = Yii::t('rcoa/worksystem', 'Worksystem Tasks');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="worksystem worksystem-task-create_check">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">验收通过</h4>
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
                
                <?= Html::encode('是否确定验收通过？') ?>
                
                <?= Html::activeHiddenInput($model, 'status', ['value' => WorksystemTask::STATUS_COMPLETED])?>
                <?= Html::activeHiddenInput($model, 'progress', ['value' => WorksystemTask::$statusProgress[WorksystemTask::STATUS_COMPLETED]])?>
                <?= Html::activeHiddenInput($model, 'finished_at', ['value' => time()])?>
                
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
        
    $('#submit-save').click(function()
    {
        $('#worksystem-task-form').submit();
    });
        
JS;
    $this->registerJs($js,  View::POS_READY);
?>