<?php

use common\models\need\NeedContent;
use yii\helpers\Html;
use yii\web\View;


/* @var $this View */
/* @var $model NeedContent */

$this->title = '内容添加';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Need Contents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="need-content-create">

    <div class="modal-dialog" role="document" style="width: 1000px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="modal-body">
                
                <?= $this->render('_form', [
                    'need_task_id' => $need_task_id,
                    'isRowspan' => [],
                    'contentPsds' => $contentPsds,
                    'modelContents' => $modelContents,
                ]) ?>

            </div>
            <div class="modal-footer">
                <?= Html::button(Yii::t('app', 'Confirm'), [
                    'id' => 'submitsave', 'class' =>'btn btn-primary', 'data-dismiss'=> 'modal', 'aria-label' => 'Close'
                ]) ?>
            </div>
       </div>
    </div>
    
</div>

<?php
$js = 
<<<JS
    
    //提交表单    
    $('#submitsave').click(function(){
        //$('#need-content-form').submit();
        $('#need-attachments-container').html('');
        $.post('../content/create?need_task_id=$need_task_id', $('#need-content-form').serialize(), function(rel){
            window.onloadUploader();    //加载文件上传
            if(rel['code'] == '200'){
                $('#need-content').load('../content/index?need_task_id=$need_task_id');
            }
        })
    });
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>