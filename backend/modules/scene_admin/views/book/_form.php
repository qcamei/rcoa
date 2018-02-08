<?php

use common\models\scene\SceneBook;
use kartik\widgets\DatePicker;
use kartik\widgets\Select2;
use kartik\widgets\SwitchInput;
use kartik\widgets\TouchSpin;
use yii\helpers\ArrayHelper;
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

    <h4><b>拍摄信息</b></h4>
    <?= $form->field($model, 'site_id')->dropDownList($siteName,[
        'options' => ['placeholder' => Yii::t('app', 'Select Placeholder'),],
        'onchange' => '
            $(".form-group#scenebook-site_id").hide(); 
            $.post("'.Yii::$app->urlManager->createUrl('scene_admin/book/scene-site').'?site_id="+$(this).val(),function(data){  
                $("#scenebook-content_type").html(data);  
        });',
    ])->label(Yii::t('app', 'Site')) ?>

    <?= $form->field($model, 'date')->widget(DatePicker::className(),[
        'options' => ['placeholder' => ''], 
        'pluginOptions' => [ 
            'autoclose' => true, 
            'format' => 'yyyy-mm-dd', 
            'todayHighlight' => true, 
        ] 
    ]) ?>

    <?= $form->field($model, 'time_index')->widget(Select2::className(),[
        'data' => SceneBook::$timeIndexMap,
        'hideSearch' => true,
        'options' => ['placeholder' => Yii::t('app', 'Select Placeholder'),]
    ])->label(Yii::t('app', 'Time Interval')) ?>
    
    <?= $form->field($model, 'start_time')->textInput([
        'type'=>'time', 
        'value' => $model->isNewRecord ? $model->getstartTimeIndex() : $model->start_time
    ]) ?>
    
    <?= $form->field($model, 'is_photograph')->widget(SwitchInput::className(),[
        'pluginOptions' => [
            'onText' => Yii::t('app', 'Y'),
            'offText' => Yii::t('app', 'N'),
        ]
    ])->label(Yii::t('app', '{Is}{Photograph}',['Is' => Yii::t('app', 'Is'),'Photograph' => Yii::t('app', 'Photograph'),])) ?>

    <?= $form->field($model, 'camera_count')->widget(TouchSpin::classname(),  [
        'readonly' => true,
        'pluginOptions' => [
            'placeholder' => Yii::t('app', 'Select Placeholder'),
            'min' => 1,
            'max' => 99999999,
        ],
    ])?>
    
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
    
    <h4><b>课程信息</b></h4>
    <?= $form->field($model, 'business_id')->widget(Select2::classname(),[
        'data' => $business, 'options' => ['prompt' => Yii::t('app', 'Select Placeholder')]
     ]) ?>
    
    <?= $form->field($model, 'level_id')->widget(Select2::classname(), [
        'data' => $levels, 'options' => ['placeholder' => Yii::t('app', 'Select Placeholder')]
    ]) ?>
    
    <?= $form->field($model, 'profession_id')->widget(Select2::classname(), [
        'data' => $professions, 'options' => ['placeholder' => Yii::t('app', 'Select Placeholder')]
    ]) ?>

    <?= $form->field($model, 'course_id')->widget(Select2::classname(), [
        'data' => $courses, 'options' => ['placeholder' => Yii::t('app', 'Select Placeholder')]
    ])?>

    <?= $form->field($model, 'lession_time')->widget(TouchSpin::classname(),  [
        'readonly' => true,
        'pluginOptions' => [
            'placeholder' => Yii::t('app', 'Select Placeholder'),
            'min' => 1,
            'max' => 99999999,
        ],
    ])?>

    <?= $form->field($model, 'teacher_id')->widget(Select2::classname(), [
        'data' => $teachers, 'options' => ['placeholder' => Yii::t('app', 'Select Placeholder')]
    ])?>

    <h4><b>其他信息</b></h4>
    <?= $form->field($model, 'booker_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::merge($createSceneBookUser, [Yii::$app->user->id => Yii::$app->user->identity->nickname]), 
        'options' => [
            'value' => !$model->getIsValid() ? Yii::$app->user->id : $model->booker_id, 
            'placeholder' => Yii::t('app', 'Select Placeholder'),
        ]
    ])?>

    <div class="form-group field-scenebookuser-user_id">
        <label class="col-lg-1 col-md-1 control-label form-label" for="scenebookuser-user_id"><?= Yii::t('app', 'Contacter') ?></label>
        <div class="col-lg-10 col-md-10">
            <?php
                $user_ids = [];
                foreach ($existSceneBookUser as $key => $value) {
                    $user_ids[] = (string) $key;
                }
                echo Select2::widget([
                    'id' => 'scenebookuser-user_id',
                    'name' => 'SceneBookUser[user_id][]',
                    'value' => !$model->getIsValid() ? Yii::$app->user->id : $user_ids,
                    'data' => $createSceneBookUser,
                    'maintainOrder' => true,    //无序排列
                    'hideSearch' => true,
                    'options' => [
                        'placeholder' => Yii::t('app', 'Select Placeholder'),
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
                ]); 
            ?>
        </div>
        <div class="col-lg-10 col-md-10"><div class="help-block"></div></div>
    </div>
    
    <?= $form->field($model, 'remark')->textarea(['rows' => 6]) ?>
    
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>

    <?php ActiveForm::end(); ?>

</div>
<?php

$js = <<<JS
    //接洽人 设置第一个选择边框为蓝色
    window.select2Log = function(){
        $(".field-scenebookuser-user_id").removeClass("has-error");
        $(".field-scenebookuser-user_id .help-block").html("");
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
JS;
    $this->registerJs($js, View::POS_READY);
?>
