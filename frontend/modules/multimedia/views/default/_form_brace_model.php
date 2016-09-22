<?php

use common\models\multimedia\MultimediaTask;
use kartik\widgets\Select2;
use yii\widgets\ActiveForm;
    
/* @var $model MultimediaTask */

?>



<div class="modal fade" id="braceModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">选择支撑团队</h4>
            </div>
            <div class="modal-body">
                
                <?php $form = ActiveForm::begin(['id' => 'form-seek-brace', 'action'=>'seek-brace?id='.$model->id]); ?>
                
                <?php
                    echo Select2::widget([
                        'name' => 'MultimediaTask[make_team]',
                        'value' => !empty($model->make_team) ? $model->make_team : '',
                        'data' => $teams,
                        'options' => [
                            'placeholder' => '请选择...',
                        ],
                    ]);
                ?>
                
                <?php ActiveForm::end(); ?>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="brace-close" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="brace-save">确认</button>
            </div>
        </div>
    </div>
</div>