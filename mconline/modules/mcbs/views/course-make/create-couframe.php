<?php

use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;


/* @var $this View */

$is_show = null;
if(Yii::$app->controller->action->id == 'create-couphase')
    $is_show = true;

$this->title = Yii::t(null, "{add}{$title}",[
    'add' => Yii::t('app', 'Add'),
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mcbs Courses'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="mcbs-create-couframe mcbs">

    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="myModalLabel"><?= Html::encode($this->title) ?></h4>
            </div>
            <div class="modal-body">
                <?= $this->render('couframe_form',[
                    'model' => $model,
                    'is_show' => $is_show
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
$item = json_encode(str_replace(array("\r\n", "\r", "\n"),"",$this->renderFile('@mconline/modules/mcbs/views/course-make/couframe_view.php')));

$js = 
<<<JS
            
    /** 提交表单 */
    $("#submitsave").click(function(){
        var item = $item;    
        $.post("$action",$('#form-couframe').serialize(),function(data){
            if(data['code'] == '200'){
                var dome = renderDom(item,{
                    frame_name: data['data']['frame_name'],
                    sub_frame: data['data']['sub_frame'],
                    id: data['data']['id'],
                    name: data['data']['name'],
                    value_percent: data['data']['value_percent'],
                });
                console.log(dome);
                doc = new DOMParser().parseFromString(dome, "text/html").body.firstChild;
                console.log($("#"+data['data']['phase_id']+" div>.list"));
                $("#"+data['data']['phase_id']+">.list").append(doc);
                sortable(".data-cou-"+data['data']['frame_name'],{
                    forcePlaceholderSize: true,
                    items: 'li',
                    handle: '.fa-arrows'
                });
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