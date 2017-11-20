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
            'id' => 'form-activity',
            'class'=>'form-horizontal',
        ],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-10 col-md-10\">{input}</div>\n<div class=\"col-lg-10 col-md-10\">{error}</div>",  
            'labelOptions' => [
                'class' => 'col-lg-1 col-md-1 form-label',
            ],  
        ], 
    ]); ?>

    <?= $form->field($model, 'type_id')->textInput()->label(Yii::t('app', 'Type')) ?>
    
    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'des')->textarea(['rows'=>6,'value'=>$model->isNewRecord?'无':$model->des]) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('rcoa', 'Create') : Yii::t('rcoa', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

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