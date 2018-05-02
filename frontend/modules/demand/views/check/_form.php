<?php

use common\models\demand\DemandCheck;
use common\models\demand\DemandTask;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model DemandCheck */
/* @var $form ActiveForm */
?>

<div class="demand-check-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'demand-check-form',
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

    <?php if(empty($model->demandTask->budget_cost)): ?>
    
    <?= Html::encode('工作项成本不能为空！'); ?>
    
    <?php else: ?>
    
    <?php if($model->isNewRecord && $model->demandTask->getIsStatusDefault())
        echo Html::activeHiddenInput($model, 'content', ['value' => '任务创建']);
    ?>
    
    <?= $form->field($model, 'des')->textarea(['value' => !$model->isNewRecord ? $model->des : '无', 'rows' => 5]) ?>
    
    <?php endif; ?>

    <?php ActiveForm::end(); ?>

</div>
