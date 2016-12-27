<?php

use common\models\demand\DemandTaskProduct;
use kartik\widgets\TouchSpin;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DemandTaskProduct */
/* @var $form ActiveForm */
?>

<div class="demand-task-product-form">

    <?php $form = ActiveForm::begin([
        'id' => 'demand-task--product-form',
        'fieldConfig' => [  
            'template' => "<div class=\"form-input\">{input}</div>",  
        ], 
    ]); ?>

    <?= $form->field($model, 'number')->widget(TouchSpin::classname(),  [
        'options' => ['class' => 'input-sm', 'style' => 'padding:5px;'],
        'pluginOptions' => [
            'placeholder' => '数量 ...',
            'min' => 1,
            'max' => 999999,
            'buttonup_class' => 'btn btn-default btn-sm', 
            'buttondown_class' => 'btn btn-default btn-sm', 
            'buttonup_txt' => '<i class="glyphicon glyphicon-plus-sign"></i>', 
            'buttondown_txt' => '<i class="glyphicon glyphicon-minus-sign"></i>'
        ],
    ]) ?>  

    <?php ActiveForm::end(); ?>

</div>
