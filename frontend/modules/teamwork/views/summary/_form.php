<?php

use common\models\teamwork\CourseSummary;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model CourseSummary */
/* @var $form ActiveForm */
?>

<div class="course-summary-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'course-summary-form',
            'class'=>'form-horizontal',
        ],
    ]) ?>

    <?php
        if(!$model->isNewRecord)
            echo '<span style="color:#ccc">时间：'.date('Y-m-d H:i', $model->created_at).'</span>'
    ?>
    
    <?= $form->field($model, 'content')->textarea([
        'style' => 'width:100%', 
        'rows' => 23, 
        'placeholder' => '课程总结...'
    ])->label('') ?>

    <?php ActiveForm::end(); ?>

</div>
