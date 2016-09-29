<?php

use common\models\multimedia\MultimediaTask;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
    
/* @var $model MultimediaTask */

?>



<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">取消原因</h4>
            </div>
            <div class="modal-body">
                
                <?php $form = ActiveForm::begin(['id' => 'form-cancel', 'action'=>'cancel?id='.$model->id]); ?>
                
                <?= Html::textInput('reason', null, ['class' => 'form-control', 'placeholder'=>'请输入取消原因...']) ?>
                
                <?php ActiveForm::end(); ?>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="cancel-close" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="cancel-save">确认</button>
            </div>
        </div>
    </div>
</div>