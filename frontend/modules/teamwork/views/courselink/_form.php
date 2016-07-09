<?php

use common\models\teamwork\CourseLink;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model CourseLink */
/* @var $form ActiveForm */
?>

<div class="course-link-form">

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

    <?= $form->field($model, 'course_id')->textInput() ?>

    <?= $form->field($model, 'course_phase_id')->textInput() ?>

    <?= $form->field($model, 'link_id')->textInput() ?>

    <?= $form->field($model, 'total')->textInput() ?>

    <?= $form->field($model, 'completed')->textInput() ?>

    <?= $form->field($model, 'is_delete')->textInput(['maxlength' => true]) ?>

    <?php ActiveForm::end(); ?>

</div>
