<?php

use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $form ActiveForm */
?>

<div class="mcbs-couframe-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'form-couframe',
            'class'=>'form-horizontal',
        ],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-12 col-md-12\">{input}</div>\n<div class=\"col-lg-12 col-md-12\">{error}</div>",  
            'labelOptions' => [
                'class' => 'col-lg-12 col-md-12',
            ],  
        ], 
    ]); ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?php
        if($is_show != null)
            echo $form->field($model, 'value_percent',[
                'template' => "{label}\n<div class=\"col-lg-11 col-md-11\">{input}</div>%\n<div class=\"col-lg-11 col-md-11\">{error}</div>",
            ])->textInput(['type'=>'number']) 
    ?>

    <?= $form->field($model, 'des')->textarea(['rows'=>6,'value'=>$model->isNewRecord?'无':$model->des]) ?>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = 
<<<JS
        
        
        
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>