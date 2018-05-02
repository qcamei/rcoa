<?php

use common\models\multimedia\MultimediaTask;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
    
/* @var $model MultimediaTask */

?>

<div class="modal fade myModal" id="assignModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">选择制作人</h4>
            </div>
            <div class="modal-body">

                <?php $form = ActiveForm::begin(['id' => 'form-assign', 'action'=>'assign?id='.$model->id]); ?>
                
                <?php
                    echo Select2::widget([
                        'id' => 'producer-select',
                        'name' => 'producer[]',
                        'value' => !empty($producer) ? array_keys($producer) : '',
                        'data' => $producerList,
                        'options' => [
                            'placeholder' => '请选择制作人...',
                            //'multiple' => true
                        ],
                        'toggleAllSettings' => [
                            'selectLabel' => '<i class="glyphicon glyphicon-ok-circle"></i> 添加全部',
                            'unselectLabel' => '<i class="glyphicon glyphicon-remove-circle"></i> 取消全部',
                            'selectOptions' => ['class' => 'text-success'],
                            'unselectOptions' => ['class' => 'text-danger'],
                        ],
                        'pluginOptions' => [
                            'tags' => false,
                            'maximumInputLength' => 10,
                            'allowClear' => true,
                        ],
                    ]) 
                ?>
                
                <?php ActiveForm::end(); ?>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="assign-save">确认</button>
            </div>
        </div>
    </div>
</div>