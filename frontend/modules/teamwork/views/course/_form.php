<?php

use common\models\teamwork\CourseManage;
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
            'readonly' => true,
            'pluginOptions' => [
                'placeholder' => '学时 ...',
                'min' => 1,
                //'max' => 5,
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

    <?php ActiveForm::end(); ?>

</div>
