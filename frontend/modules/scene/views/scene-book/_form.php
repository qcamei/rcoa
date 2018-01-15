<?php

use common\models\scene\SceneBook;
use kartik\widgets\Select2;
use kartik\widgets\TouchSpin;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model SceneBook */
/* @var $form ActiveForm */

?>

<div class="scene-book-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'scene-book-form',
            'class'=>'form-horizontal',
        ],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-10 col-md-10\">{input}</div>\n<div class=\"col-lg-10 col-md-10\">{error}</div>",  
            'labelOptions' => [
                'class' => 'col-lg-1 col-md-1 control-label form-label',
            ],  
        ], 
    ]); ?>
   
    <h5><b>课程信息</b></h5>
    <?= $form->field($model, 'business_id')->widget(Select2::classname(),[
        'data' => $business, 'options' => ['prompt'=>'请选择...',]
     ]) ?>
    
    <?= $form->field($model, 'level_id')->widget(Select2::classname(), [
        'data' => $levels, 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'profession_id')->widget(Select2::classname(), [
        'data' => $professions, 'options' => ['placeholder' => '请选择...']
    ]) ?>

    <?= $form->field($model, 'course_id')->widget(Select2::classname(), [
        'data' => $courses, 'options' => ['placeholder' => '请选择...',]
    ])?>

    <?= $form->field($model, 'lession_time')->widget(TouchSpin::classname(),  [
        'readonly' => true,
        'pluginOptions' => [
            'placeholder' => '请选择...',
            'min' => 1,
            'max' => 99999999,
        ],
    ])?>
    
    <?= $form->field($model, 'start_time')->textInput([
        'type'=>'time', 
        'value' => $model->isNewRecord ? $model->getstartTimeIndex() : $model->start_time
    ]) ?>

    
    <h5><b>老师信息</b></h5>
    <?= $form->field($model, 'teacher_id')->widget(Select2::classname(), [
        'data' => $teachers, 'options' => ['placeholder' => '请选择...']
    ])?>
    
    <div class="form-group field-scenebook-teacher_personal_image">
        <label class="col-lg-1 col-md-1 control-label form-label" for="scenebookuser-teacher_personal_image">
            <?= Yii::t('app', 'Personal Image') ?>
        </label>
        <div class="col-lg-10 col-md-10">
            <?= Html::img(!$model->isNewRecord ? [$model->teacher->personal_image] : null, [
                'id' => 'scenebook-teacher_personal_image',
                'width' => '128', 'height' => '125'
            ])?>
        </div>
        <div class="col-lg-10 col-md-10"><div class="help-block"></div></div>
    </div>
    
    <?= $form->field($model, 'teacher_phone')->textInput([
        'value' => !$model->isNewRecord ? $model->teacher->user->phone : null, 
        'disabled' => 'disabled'
    ]) ?>
    
    <?= $form->field($model, 'teacher_email')->textInput([
        'value' => !$model->isNewRecord ? $model->teacher->user->email : null, 
        'disabled' => 'disabled'
    ]) ?>

    
    <h5><b>拍摄信息</b></h5>
    <?= $form->field($model, 'content_type', [
        'template' => "{label}\n<div class=\"col-lg-10 col-md-10\" style=\"margin-top: 5px;\">{input}</div>\n<div class=\"col-lg-10 col-md-10\">{error}</div>"
    ])->radioList($contentTypeMap, [
        'separator'=>'',
        'itemOptions'=>[
            'labelOptions'=>[
                'style'=>[
                     'margin-right'=>'30px'
                ]
            ]
        ],
    ]) ?>
    
    <?= $form->field($model, 'camera_count')->widget(TouchSpin::classname(),  [
        'readonly' => true,
        'pluginOptions' => [
            'placeholder' => '请选择...',
            'min' => 1,
            'max' => 99999999,
        ],
    ])?>
    
    <div class="form-group field-scenebook-is_photograph">
        <label class="col-lg-1 col-md-1 control-label form-label" for="scenebook-is_photograph">
            <?= Yii::t('app', 'Is Photograph') ?>
        </label>
        <div class="col-lg-10 col-md-10" style="margin-top: 5px;">
            <?= Html::checkbox('SceneBook[is_photograph]') ?>
            <label>需要</label>
        </div>
        <div class="col-lg-10 col-md-10"><div class="help-block"></div></div>
    </div>
    
    
    
    <h5><b>其他信息</b></h5>
    <?= $form->field($model, 'booker_id')->widget(Select2::classname(), [
        'data' => $createSceneBookUser, 
        'options' => [
            'value' => $model->isNewRecord ? Yii::$app->user->id : $model->booker_id, 
            'placeholder' => '请选择...'
        ]
    ])?>
    
    <div class="form-group field-scenebookuser-user_id">
        <label class="col-lg-1 col-md-1 control-label form-label" for="scenebookuser-user_id"><?= Yii::t('app', 'Contacter') ?></label>
        <div class="col-lg-10 col-md-10">
            
            <?= Select2::widget([
                'name' => 'SceneBookUser[user_id][]',
                'value' => $model->isNewRecord ? Yii::$app->user->id : array_keys($existSceneBookUser),
                'data' => $createSceneBookUser,
                'maintainOrder' => true,    //无序排列
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
                'pluginOptions' => [
                    'tags' => false,
                    'maximumInputLength' => 10,
                    'allowClear' => true,
                ],
                'pluginEvents' => [
                    'change' => 'function(){ select2Log();}'
                ]
            ]); ?>
            
        </div>
        
        <div class="col-lg-10 col-md-10"><div class="help-block"></div></div>
    </div>
    
    <?= $form->field($model, 'remark')->textarea(['rows' => 6]) ?>
    
    <?= Html::hiddenInput('SceneBook[multi_period]', null, ['id' => 'multi-period']) ?>
    
    <?php ActiveForm::end(); ?>

</div>


<?php

$js = <<<JS
    window.select2Log = function(){
        $("ul.select2-selection__rendered").find("li.select2-selection__choice").eq(0).css({border:"1px solid blue"});
    }
    //接洽人，默认给第一个加边框
    $("ul.select2-selection__rendered").find("li.select2-selection__choice").eq(0).css({border:"1px solid blue"});
    //获取专业/工种
    $("#scenebook-level_id").change(function(){
	$("#scenebook-profession_id").html("");
        $("#select2-scenebook-profession_id-container").html("");
        $("#scenebook-course_id").html("");
	$("#select2-scenebook-course_id-container").html("");
	$.post("/framework/api/search?id="+$(this).val(),function(data){
            $('<option/>').appendTo($("#scenebook-profession_id"));
            $.each(data['data'],function(){
                $('<option>').val(this['id']).text(this['name']).appendTo($("#scenebook-profession_id"));
            });
	});
    });
    //获取课程
    $("#scenebook-profession_id").change(function(){
        $("#scenebook-course_id").html("");
	$("#select2-scenebook-course_id-container").html("");
	$.post("/framework/api/search?id="+$(this).val(),function(data){
            $('<option/>').appendTo($("#scenebook-course_id"));
            $.each(data['data'],function(){
                $('<option>').val(this['id']).text(this['name']).appendTo($("#scenebook-course_id"));
            });
	});
    });
    //获取老师头像、手机、邮箱
    $("#scenebook-teacher_id").change(function(){
        $.post("/scene/scene-book/teacher-search?user_id="+$(this).val(),function(result){
            console.log(result['data']['personal_image']);
            if(result['code'] == '200'){
                $("#scenebook-teacher_personal_image").attr({'src': result['data']['personal_image']});
                $("#scenebook-teacher_phone").val(result['data']['phone']);
                $("#scenebook-teacher_email").val(result['data']['email']);
            }
	});
    });
      
JS;
    $this->registerJs($js, View::POS_READY);
?>