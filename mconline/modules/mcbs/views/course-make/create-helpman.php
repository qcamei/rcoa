<?php

use common\models\mconline\McbsCourseUser;
use kartik\widgets\Select2;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;


/* @var $this View */
/* @var $model McbsCourseUser */

$this->title = Yii::t(null, '{add}{helpman}',[
    'add' => Yii::t('app', 'Add'),
    'helpman' => Yii::t('app', 'Help Man')
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mcbs Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mcbs-create-helpman mcbs">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="modal-body mcbs-activity">
                <?php $form = ActiveForm::begin([
                    'options'=>[
                        'id' => 'form-helpman',
                        'class'=>'form-horizontal',
                    ],
                    'fieldConfig' => [  
                        'template' => "{label}\n<div class=\"col-lg-12 col-md-12\">{input}</div>\n<div class=\"col-lg-12 col-md-12\">{error}</div>",  
                        'labelOptions' => [
                            'class' => 'col-lg-12 col-md-12',
                        ],  
                    ], 
                ]); ?>

                <div class="form-group field-mcbsrecentcontacts-contacts_id">
                    <label class="col-lg-12 col-md-12" for="mcbsrecentcontacts-contacts_id">最近联系：</label>
                    <div class="col-lg-12 col-md-12">
                        <?php foreach ($contacts as $item): ?>
                        <div class="actitype">
                            <?= Html::img(WEB_ROOT.$item['avatar'],['class'=>'acticon']) ?>
                            <p class="actname" data-key="<?= $item['id'] ?>"><?= $item['nickname']; ?></p>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <?= Html::activeHiddenInput($model, 'course_id') ?>
                
                <?= $form->field($model, 'user_id')->widget(Select2::classname(), [
                    'data' => $helpmans, 
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
                    /*'pluginOptions' => [
                        'tags' => false,
                        'maximumInputLength' => 10,
                        'allowClear' => true,
                    ],*/
                ])->label(Yii::t(null, '{add}{people}',['add'=>Yii::t('app', 'Add'),'people'=> Yii::t('app', 'People')])) ?>

                <?= $form->field($model, 'privilege', [
                    'template' => "{label}\n<div class=\"col-lg-4 col-md-4\">{input}</div>\n<div class=\"col-lg-4 col-md-4\">{error}</div>",
                    'labelOptions' => [
                        'class' => 'col-lg-12 col-md-12',
                    ],
                ])->widget(Select2::classname(), [
                    'data' => McbsCourseUser::$privilegeName, 
                    'options' => [
                        'placeholder' => '请选择...'
                    ]
                ])->label(Yii::t(null, '{set}{privilege}',['set'=> Yii::t('app', 'Set'),'privilege'=> Yii::t('app', 'Privilege')])) ?>

                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <?= Html::button(Yii::t('app', 'Confirm'), [
                    'id'=>'submitsave','class'=>'btn btn-primary',
                    'data-dismiss'=>'modal','aria-label'=>'Close'
                ]) ?>
            </div>
       </div>
    </div>
    
</div>

<?php

$helpman = Url::to(['course-make/helpman-index', 'course_id' => $model->course_id]);
$helpmanUrl = Url::to(['course-make/create-helpman', 'course_id' => $model->course_id]);
$actlog = Url::to(['course-make/log-index', 'course_id' => $model->course_id]);

$js = 
<<<JS
        
    //选择最近联系人
     var temp = [];
    $(".actitype").click(function(){
        var dataKey = $(this).children("p").attr("data-key");
        if($.inArray(dataKey, temp)) {
            temp.push(dataKey);
        } else {
            temp = $.grep(temp, function(n,i){
                return n != dataKey;
            });
        }
        $("#mcbscourseuser-user_id").val(temp);
        $("#mcbscourseuser-user_id").trigger("change"); 
    });    
        
    /** 提交表单 */
    $("#submitsave").click(function(){
        $.post("$helpmanUrl",$('#form-helpman').serialize(),function(data){
            if(data['code'] == '200'){
                $("#help-man").load("$helpman");
                $("#action-log").load("$actlog");
            }
        });
    });   
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>