<?php

use common\models\need\NeedTaskUser;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;


/* @var $this View */
/* @var $model NeedTaskUser */

$this->title = '添加人员';
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Need Task Users'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = $this->title;
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
                    'options'=>['id' => 'need-task-user-form','class'=>'form-horizontal',],
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
                
                <?= Html::activeHiddenInput($model, 'need_task_id') ?>
                
                <?= $form->field($model, 'user_id')->widget(Select2::class, [
                    'data' => $taskUsers, 
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => '请选择...',
                        'multiple' => true,     //设置多选
                    ],
                    'toggleAllSettings' => [
                        'selectLabel' => '<i class="glyphicon glyphicon-ok-circle"></i> 添加全部',
                        'unselectLabel' => '<i class="glyphicon glyphicon-remove-circle"></i> 取消全部',
                        'selectOptions' => ['class' => 'text-success'],
                        'unselectOptions' => ['class' => 'text-danger'],
                    ],
                ])->label(Yii::t(null, '{add}{people}',['add'=>Yii::t('app', 'Add'),'people'=> Yii::t('app', 'People')])) ?>

                <?= $form->field($model, 'privilege', [
                    'template' => "{label}\n<div class=\"col-lg-4 col-md-4\">{input}</div>\n<div class=\"col-lg-4 col-md-4\">{error}</div>",
                    'labelOptions' => [
                        'class' => 'col-lg-12 col-md-12',
                    ],
                ])->widget(Select2::class, [
                    'data' => NeedTaskUser::$privilegeMap,
                    'hideSearch' => true,
                    'disabled' => true,
                    'options' => [
                        'placeholder' => '请选择...'
                    ]
                ])->label(Yii::t('app', '{set}{privilege}',['set'=> Yii::t('app', 'Set'), 'privilege'=> Yii::t('app', 'Privilege')])) ?>

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
        $("#needtaskuser-user_id").val(temp);
        $("#needtaskuser-user_id").trigger("change"); 
    });    
        
    /** 提交表单 */
    $("#submitsave").click(function(){
        //$('#need-task-user-form').submit();return;
        $.post("../user/create?need_task_id=$model->need_task_id", $('#need-task-user-form').serialize(),function(rel){
            if(rel['code'] == '200'){
                $("#developer").load("../user/index?need_task_id=$model->need_task_id");
                $("#needtasklog").load("../log/index?need_task_id=$model->need_task_id");
            }
        });
    });   
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>