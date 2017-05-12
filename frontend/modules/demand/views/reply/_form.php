<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\demand\DemandReply */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="demand-reply-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'demand-reply-form',
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
    
    <?= $form->field($model, 'des')->textarea(['rows' => 6]) ?>
    
    <?= Html::activeHiddenInput($model, 'pass', ['value' => 0]); ?>
   
    <?php ActiveForm::end(); ?>

</div>
