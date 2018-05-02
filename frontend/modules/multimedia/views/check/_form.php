<?php

use common\models\multimedia\MultimediaCheck;
use kartik\datecontrol\DateControl;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model MultimediaCheck */
/* @var $form ActiveForm */
?>

<div class="multimedia-check-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'multimedia-check-form',
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

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        
    <?= $form->field($model, 'remark')->textarea(['rows' => 4]) ?>

    <?php ActiveForm::end(); ?>

</div>
