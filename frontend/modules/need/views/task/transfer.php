<?php

use common\models\need\NeedTaskUser;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;


/* @var $this View */
/* @var $model NeedTaskUser */

$this->title = '转让';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Need Task Users'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="need-task-user-create">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin([
                    'options'=>['id' => 'need-task-form','class'=>'form-horizontal',],
                    'fieldConfig' => [  
                        'template' => "{label}\n<div class=\"col-lg-12 col-md-12\">{input}</div>\n<div class=\"col-lg-12 col-md-12\">{error}</div>",  
                        'labelOptions' => [
                            'class' => 'col-lg-12 col-md-12',
                        ],  
                    ], 
                ]); ?>

                <div class="form-group field-recentcontacts-contacts_id">
                    <label class="col-lg-12 col-md-12" for="recentcontacts-contacts_id">最近联系：</label>
                    <div class="col-lg-12 col-md-12">
                        <?php foreach ($userRecentContacts as $item): ?>
                        <div class="recent">
                            <?= Html::img($item['avatar'],['width' => 40, 'height' => 37]) ?>
                            <p data-key="<?= $item['id'] ?>"><?= $item['nickname']; ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <?= $form->field($model, 'receive_by')->widget(Select2::class, [
                    'data' => $receiveBys, 
                    'hideSearch' => true,
                    'options' => ['placeholder' => '请选择...',]
                ]) ?>

                <div class="form-group field-needtask-remarks">
                    <label class="col-lg-12 col-md-12" for="needtask-remarks">备注：</label>
                    <div class="col-lg-12 col-md-12">
                        <?= Html::textarea('remarks', '无', ['rows' => 6, 'class' => 'form-control']) ?>
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
        
    //选择最近联系人
    var temp = [];
    $(".recent").click(function(){
        var dataKey = $(this).children("p").attr("data-key");
        if($.inArray(dataKey, temp)) {
            temp.push(dataKey);
        } else {
            temp = $.grep(temp, function(n,i){
                return n != dataKey;
            });
        }
        $("#needtask-receive_by").val(temp);
        $("#needtask-receive_by").trigger("change"); 
    });    
        
    /** 提交表单 */
    $("#submitsave").click(function(){
        $('#need-task-form').submit();
    });   
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>