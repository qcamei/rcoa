<?php

use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t(null, "{add}{$title}",[
    'add' => Yii::t('app', 'Add'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mcbs Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mcbs-create-activity mcbs mcbs-activity">
    
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
        }else if(tijiao() == false ){
            $("#uploader").addClass("has-error");
            $("#uploader >div >.help-block").html("还有文件未上传");
        }else{
            var item = '<li id="{%id%}">'+
                '<div class="head cou-default cou-activity">'+
                    '<a>'+
                        '<img src="{%icon_path%}" width="25" height="25" class="icon_path">'+
                        '<span class="type_name">【{%type_name%}】：</span>'+
                        '<span class="name">{%name%}</span>'+
                    '</a>'+
                    '<div class="cou-icon">'+
                        '<a href="../course-make/cou{%frame_name%}-view?id={%id%}" target="_blank"><i class="fa fa-eye"></i></a>'+
                        '<a href="../course-make/update-cou{%frame_name%}?id={%id%}" onclick="couFrame($(this));return false;"><i class="fa fa-pencil"></i></a>'+
                        '<a href="../course-make/delete-cou{%frame_name%}?id={%id%}" onclick="couFrame($(this));return false;"><i class="fa fa-times"></i></a>'+
                        '<a href="javascript:;" class="handle"><i class="fa fa-arrows"></i></a>'+
                    '</div>'+
                '</div>'+
            '</li>';    
            $.post("$action",$('#form-activity').serialize(),function(data){
                if(data['code'] == '200'){
                    var dome = renderDom(item,data['data']);
                    $("#"+data['data']['parent_id']+">div >.list").append(dome);
                    sortable(".sortable", {
                        forcePlaceholderSize: true,
                        items: 'li',
                        handle: '.fa-arrows'
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