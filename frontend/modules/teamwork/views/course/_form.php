<?php

use common\models\teamwork\CourseManage;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;
use kartik\widgets\TouchSpin;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\ActiveForm;



/* @var $this View */
/* @var $model CourseManage */
/* @var $form ActiveForm */

?>

<div class="course-manage-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'course-manage-form',
            'class'=>'form-horizontal',
        ],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-10 col-md-10\">{input}</div>\n<div class=\"col-lg-10 col-md-10\">{error}</div>",  
            'labelOptions' => [
                'class' => 'col-lg-1 col-md-1 control-label',
                'style'=>[
                    'color'=>'#999999',
                    'font-weight'=>'normal',
                    'padding-right' => '0'
                ]
            ],  
        ], 
    ]); ?>

    <?= $form->field($model, 'project.item_type_id')->textInput([
        'value' => $model->project->itemType->name, 'disabled' => 'disabled'
    ]) ?>
    
    <?= $form->field($model, 'project.item_id')->textInput([
        'value' => $model->project->item->name, 'disabled' => 'disabled'
    ]) ?>
    
    <?= $form->field($model, 'project.item_child_id')->textInput([
        'value' => $model->project->itemChild->name, 'disabled' => 'disabled'
    ]) ?>
    
    <?= $form->field($model, 'course_id')->widget(Select2::classname(), [
        'data' => $courses, 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'teacher')->widget(Select2::classname(), [
        'data' => $teachers, 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?= $form->field($model, 'credit')->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '学分 ...',
            'min' => 1,
        ],
    ]) ?>
    
    <?= $form->field($model, 'lession_time')->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '学时 ...',
            'min' => 1,
        ],
    ]) ?>
    
    <?php
        echo Html::beginTag('div', ['class' => 'form-group field-coursemanage-video_length has-success']);
            echo Html::beginTag('label', [
                    'class' => 'col-lg-1 col-md-1 control-label', 
                    'style' => 'color: #999999; font-weight: normal; padding-right:0;padding-left:10px;',
                    'for' => 'coursemanage-video_length'
                ]).Yii::t('rcoa/teamwork', 'Video Length').Html::endTag('label');
            echo Html::beginTag('div', ['class' => 'col-sm-4']);
                echo DateControl::widget([
                    'name' => 'CourseManage[video_length]',
                    'value' => $model->isNewRecord ? date('H:i:s', strtotime('01:00:00')) : date('H:i:s', $model->video_length), 
                    'type'=> DateControl::FORMAT_TIME,
                    'displayFormat' => 'H:i:s',
                    'saveFormat' => 'H:i:s',
                    'ajaxConversion'=> true,
                    'autoWidget' => true,
                    'options' => [
                        'pluginOptions' => [
                            'autoclose' => true,
                        ],
                    ],
                ]);
                echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']).Html::beginTag('div', ['class' => 'help-block']).Html::endTag('div').Html::endTag('div');
            echo Html::endTag('div');
        echo Html::endTag('div');
    ?>
    
    <?= $form->field($model, 'question_mete')->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '题量 ...',
            'min' => 1,
        ],
    ]) ?>
    
    <?= $form->field($model, 'case_number')->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '案例数...',
            'min' => 1,
        ],
    ]) ?>
    
    <?= $form->field($model, 'activity_number')->widget(TouchSpin::classname(),  [
        'pluginOptions' => [
            'placeholder' => '活动数 ...',
            'min' => 1,
        ],
    ]) ?>
    
    <?= $form->field($model, 'course_ops')->widget(Select2::classname(), [
        'data' => $producerList, 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <?php
        echo Html::beginTag('div', ['class' => 'form-group field-coursemanage-weekly_editors_people required has-success']);
             echo Html::beginTag('label', [
                 'class' => 'col-lg-1 col-md-1 control-label',
                 'style' => 'color: #999999; font-weight: normal;padding-right:0;padding-left:10px;',
                 'for' => 'weekly_editors_people'
                ]).Yii::t('rcoa/teamwork', 'Weekly Editors People').Html::endTag('label');
             echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']);
                echo Select2::widget([
                    'name' => 'CourseManage[weekly_editors_people]',
                    'value' => $model->isNewRecord ? Yii::$app->user->id : $model->weekly_editors_people,
                    'data' => $weeklyEditors,
                    'options' => [
                        'placeholder' => 'Select a state ...',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]);
                
             echo Html::endTag('div');           
             echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']).Html::beginTag('div', ['class' => 'help-block']).
                    Html::endTag('div').Html::endTag('div');
        echo Html::endTag('div');

    ?>
   
    <?php
        echo Html::beginTag('div', ['class' => 'form-group field-coursemanage-plan_start_time has-success']);
            echo Html::beginTag('label', [
                    'class' => 'col-lg-1 col-md-1 control-label', 
                    'style' => 'color: #999999; font-weight: normal; padding-right:0;padding-left:10px;',
                    'for' => 'coursemanage-plan_start_time'
                ]).Yii::t('rcoa/teamwork', 'Plan Start Time').Html::endTag('label');
            echo Html::beginTag('div', ['class' => 'col-sm-4']);
                echo DateControl::widget([
                    'name' => 'CourseManage[plan_start_time]',
                    'value' => $model->isNewRecord ? date('Y-m-d H:i', time()) : $model->plan_start_time, 
                    'type'=> DateControl::FORMAT_DATETIME,
                    'displayFormat' => 'yyyy-MM-dd H:i',
                    'saveFormat' => 'yyyy-MM-dd H:i',
                    'ajaxConversion'=> true,
                    'autoWidget' => true,
                    'readonly' => true,
                    'options' => [
                        'pluginOptions' => [
                            'autoclose' => true,
                        ],
                    ],
                ]);
                echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']).Html::beginTag('div', ['class' => 'help-block']).Html::endTag('div').Html::endTag('div');
            echo Html::endTag('div');
        echo Html::endTag('div');
    ?>
    
    <?php
        echo Html::beginTag('div', ['class' => 'form-group field-coursemanage-plan_end_time has-success']);
            echo Html::beginTag('label', [
                    'class' => 'col-lg-1 col-md-1 control-label', 
                    'style' => 'color: #999999; font-weight: normal; padding-right:0;padding-left:10px;',
                    'for' => 'coursemanage-plan_end_time'
                ]).Yii::t('rcoa/teamwork', 'Plan End Time').Html::endTag('label');
            echo Html::beginTag('div', ['class' => 'col-sm-4']);
                echo DateControl::widget([
                    'name' => 'CourseManage[plan_end_time]',
                    'value' => $model->isNewRecord ? date('Y-m-d H:i', time()) : $model->plan_end_time, 
                    'type'=> DateControl::FORMAT_DATETIME,
                    'displayFormat' => 'yyyy-MM-dd H:i',
                    'saveFormat' => 'yyyy-MM-dd H:i',
                    'ajaxConversion'=> true,
                    'autoWidget' => true,
                    'readonly' => true,
                    'options' => [
                        'pluginOptions' => [
                            'autoclose' => true,
                        ],
                    ],
                ]);
                echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']).Html::beginTag('div', ['class' => 'help-block']).Html::endTag('div').Html::endTag('div');
            echo Html::endTag('div');
        echo Html::endTag('div');
    ?>
    
    <?= $form->field($model, 'des')->textarea(['rows' => 4]) ?>
    
    <?= $form->field($model, 'path')->textInput(['placeholder' => '存储服务器路径...']) ?>
    
    <?php
        echo Html::beginTag('div', ['class' => 'form-group field-courseproducer-producer has-success']);
             echo Html::beginTag('label', [
                 'class' => 'col-lg-1 col-md-1 control-label',
                 'style' => 'color: #999999; font-weight: normal; padding-right:0;padding-left:10px;',
                 'for' => 'courseproducer-producer'
                ]).Yii::t('rcoa/teamwork', 'Resource People').Html::endTag('label');
             echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']);
                echo Select2::widget([
                    'name' => 'producer',
                    'value' => array_keys($producer),
                    'data' => $producerList,
                    'options' => [
                        'placeholder' => 'Select a state ...',
                        'multiple' => true,
                    ],
                    'pluginOptions' => [
                        //'templateResult' => new JsExpression('format'),
                        //'templateSelection' => new JsExpression('format'),
                        'escapeMarkup' => new JsExpression("function(m) { return m; }"),
                        'allowClear' => true
                    ],
                    'pluginEvents' => [
                        'change' => "function() { log($(this)); }",
                    ],
                ]);
                
             echo Html::endTag('div');           
             echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']).Html::beginTag('div', ['class' => 'help-block']).
                    Html::endTag('div').Html::endTag('div');
        echo Html::endTag('div');

    ?>
    
    <?php ActiveForm::end(); ?>

</div>


<?php
$url = Yii::$app->urlManager->baseUrl . '/images/flags/';
$format = 
<<< SCRIPT
    function format(state) {
        if (!state.id) return state.text; // optgroup
        src = '$url' +  state.id.toLowerCase() + '.png'
        return '<img class="flag" src="' + src + '"/>' + state.text;
    }
SCRIPT;
    //$this->registerJs($format, View::POS_HEAD);
$sourceProducers = [];  
    foreach ($producerList as $teams){  
        foreach($teams as $id=>$name){  
            $sourceProducers[$id]=$name;  
        }  
    }  
$sourceProducers = json_encode($sourceProducers);  
$js =   
<<<JS
    var sourceProducers = $sourceProducers; 
    function log(value){   
        var hasSelected = $("#coursemanage-weekly_editors_people").val();    
        $("#coursemanage-weekly_editors_people").html("");    
        var producers = $(value).val();
        for(var i=0,len=producers.length;i<len;i++){    
            $('<option>').val(producers[i]).text(sourceProducers[producers[i]]).appendTo($("#coursemanage-weekly_editors_people"));     
        }    
        
        if(producers.indexOf(hasSelected)!=-1)    
            $("#coursemanage-weekly_editors_people").val(hasSelected);    
        else{  
            $("#coursemanage-weekly_editors_people").val("");  
            $("#select2-coursemanage-weekly_editors_people-container").html("请选择...");  
        }  
    }    

    
JS;
    $this->registerJs($js,  View::POS_READY);
?>
