<?php

use common\models\demand\DemandAppeal;
use common\models\demand\DemandTask;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DemandAppeal */
/* @var $form ActiveForm */
?>

<div class="demand-appeal-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'demand-appeal-form',
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

    <?= $form->field($model, 'reason')->textarea(['value' => '无', 'rows' => 5]) ?>
    
    <?= Html::activeHiddenInput($model, 'des', ['value' => '无']);?>
    
    <?= Html::activeHiddenInput($model, 'create_by', ['value' => Yii::$app->user->id]);?> 

    <?php ActiveForm::end(); ?>

</div>
