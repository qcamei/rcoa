<?php

use common\models\teamwork\CourseManage;
use kartik\checkbox\CheckboxX;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;
use kartik\widgets\TouchSpin;
use yii\helpers\Html;
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
                    'padding-left' => 0,
                    'padding-right' => 0,
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

    <?= $form->field($model, 'lession_time')->widget(TouchSpin::classname(),  [
            'pluginOptions' => [
                'placeholder' => '学时 ...',
                'min' => 1,
            ],
    ])?>
    
    <?php
        echo Html::beginTag('div', ['class' => 'form-group field-itemmanage-forecast_time has-success']);
            echo Html::beginTag('label', [
                    'class' => 'col-lg-1 col-md-1 control-label', 
                    'style' => 'color: #999999; font-weight: normal; padding-left: 0; padding-right: 0;',
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
        echo Html::beginTag('div', ['class' => 'form-group field-itemmanage-forecast_time has-success']);
            echo Html::beginTag('label', [
                    'class' => 'col-lg-1 col-md-1 control-label', 
                    'style' => 'color: #999999; font-weight: normal; padding-left: 0; padding-right: 0;',
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
    
    <?php
        echo Html::beginTag('div', ['class' => 'form-group field-courseproducer-producer has-success']);
             echo Html::beginTag('label', [
                 'class' => 'col-lg-1 col-md-1 control-label',
                 'style' => 'color: #999999; font-weight: normal; padding-left: 0; padding-right: 0;',
                 'for' => 'courseproducer-producer'
                ]).'资源制作人'.Html::endTag('label');
             echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']);
                     echo Html::img(['/filedata/image/add_list_64.png'], [
                         'data-toggle' => 'collapse', 
                         'href' => '#collapseExample', 
                         'aria-expanded' => 'false',
                         'aria-controls' => 'collapseExample',
                         'width' => '48',
                         'height' => '48']);
                     echo Html::beginTag('div', ['class' => 'collapse', 'id' => 'collapseExample', ]).
                            Html::checkboxList('producer', array_keys($producer), $producerList, ['class' => 'well',
                                'itemOptions' => ['style'=>'margin-left:20px;']]).Html::endTag('div');
                      echo Html::beginTag('div',['id' => 'display-list', 'style' => 'margin-top:15px;']); 
                           foreach ($producer as $key => $value)
                                echo '<span value ="'.$key.'" class="producer img-rounded">'.$value.'<i class="delete-icon"></i></span>';
                      echo Html::endTag('div');
             echo Html::endTag('div');           
             echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']).
                    Html::beginTag('div', ['class' => 'help-block']).
                    Html::endTag('div').Html::endTag('div');
        echo Html::endTag('div');

    ?>
    
    <?php ActiveForm::end(); ?>

</div>


<?php
$js = 
<<<JS
    deleteIcon();
    function deleteIcon(){    
        $('.delete-icon').click(function()
        {
            var displayValue = $(this).parent().remove().attr("value");
            $('input[type="checkbox"]').each(function (i,e){
                if(e.checked == true && e.value == displayValue)
                    e.checked = false;
            });
        });
    }
    $('input[type="checkbox"]').change (function (){
            var val = $(this).val();
                text = $(this).parent().text();
                html = '<span value="'+val+'" class="producer img-rounded">'+text+'<i class="delete-icon"></i></span>';
        $(this).each(function (i,e){
            if(e.checked == true)
                $(html).appendTo('#display-list');
        });  
        deleteIcon();
    });
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>