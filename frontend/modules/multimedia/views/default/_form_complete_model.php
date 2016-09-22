<?php

use common\models\multimedia\MultimediaTask;
use wskeee\utils\DateUtil;
use yii\widgets\ActiveForm;
    
/* @var $model MultimediaTask */

?>



<div class="modal fade" id="completeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">成品视频时长</h4>
            </div>
            <div class="modal-body">
                
                <?php $form = ActiveForm::begin(['id' => 'form-complete', 'action'=>'complete?id='.$model->id]); ?>
                
                <?= $form->field($model, 'production_video_length', [
                    'labelOptions'=> [
                        'style' => [
                            'color'=>'#999999',
                            'font-weight'=>'normal',
                            'padding-right' => '0',
                            'padding-left' => '0',
                        ]
                    ]
                ])->textInput([
                    'value' => DateUtil::intToTime($model->production_video_length),
                    'placeholder' => '00:00:00'
                ]) ?>
                
                <?php ActiveForm::end(); ?>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="complete-close" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="complete-save">确认</button>
            </div>
        </div>
    </div>
</div>