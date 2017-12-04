<?php

use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t(null, "{edit}{$title}：{$model->name}", [
    'edit' => Yii::t('app', 'Edit'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mcbs Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="mcbs-update-activity mcbs mcbs-activity">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="modal-body" style="overflow-y: auto;">
                <?= $this->render('activity_form',[
                    'model' => $model,
                    'actiType' => $actiType,
                    'files' => $file,
                ]) ?>
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

$action = Url::to(Yii::$app->request->url);
$actlog = Url::to(['course-make/log-index', 'course_id' => $course_id]);


$js = 
<<<JS
        
    /** 提交表单 */
    $("#submitsave").click(function(){
        var value =  $("#mcbscourseactivity-type_id").val();
        if(value <= 0){
            $(".field-mcbscourseactivity-type_id").addClass("has-error");
            $(".field-mcbscourseactivity-type_id .help-block").html("类型不能为空");
        }else if(tijiao() == false){
            $("#uploader").addClass("has-error");
            $("#uploader >div >.help-block").html("还有文件未上传");
        }else{
            //$("#form-activity").submit(); return;
            $.post("$action",$('#form-activity').serialize(),function(data){
                if(data['code'] == '200'){
                    $.each(data['data'],function(key,value){
                        $("#$model->id").find('> div.head img.'+key).attr('src',value);
                        $("#$model->id").find('> div.head span.'+key).html(value);
                    });
                    $("#action-log").load("$actlog");
                }
            });
        }
    });
        
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>