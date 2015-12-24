<?php

    use yii\helpers\Html;
?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"> 是否编辑 </h4>
            </div>
            <div class="modal-body">
                <?= Html::textInput('edit', null, ['class' => 'form-control', 'placeholder'=>'请输入编辑原因...']) ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="close" data-dismiss="modal">关闭</button>
                <button type="button" class="btn btn-primary" id="save">确认</button>
            </div>
        </div>
    </div>
</div>