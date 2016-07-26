<?php

use common\models\teamwork\CourseLink;
use common\models\teamwork\Link;
use frontend\modules\teamwork\TwAsset;
use kartik\widgets\SwitchInput;
use kartik\widgets\TouchSpin;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;


/* @var $this View */
/* @var $model CourseLink */
/* @var $form ActiveForm */
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h3 style="margin:0; padding:0"><?= $model->name?></h3>
</div>

<div class="entry-link-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'entry-manage-form',
            'class'=>'form-horizontal ',
        ],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-10 col-md-10 \">{input}</div>\n<div class=\"col-lg-10 col-md-10\">{error}</div>",  
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

    <?php
        if($model->type == Link::AMOUNT){
            echo Html::beginTag('div', ['class' => 'form-group field-courselink-total']);
                echo Html::beginTag('label', [
                    'class' => 'col-lg-1 col-md-1 control-label',
                    'style' => 'color: #999999; font-weight: normal; padding-left: 0; padding-right: 0; float:left',
                    'for' => 'field-courselink-total',
                ]).Yii::t('rcoa/teamwork', 'Total').Html::endTag('label');
                echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']);
                    echo Html::textInput('CourseLink[total]', $model->total, [
                        'id' => 'courselink-total',
                        'min' => 1,
                        'class' => 'form-control',
                        'type' => 'number',
                        'style' => 'float:left',
                    ]);
                echo Html::endTag('div').'<span style="float:left;display: block;padding: 5px;">'.$model->unit.'</span>';
                echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']).Html::beginTag('div',['class' => 'help-block']);
                echo Html::endTag('div').Html::endTag('div');
            echo Html::endTag('div');
            
            echo $form->field($model, 'completed')->widget(TouchSpin::classname(),  [
                    'value' =>$model->completed,
                    'pluginOptions' => [
                        'placeholder' => '已完成数 ...',
                        'min' => 0,
                        'max' => $model->total,
                    ],
                ]);
        }else {
            echo $form->field($model, 'completed')->checkbox()->label('状态');
        }
    ?>
      
    <?php ActiveForm::end(); ?>

</div>

<div class="modal-footer">
    <?= Html::submitButton('保存', ['id' => 'submit', 'class' => 'btn btn-primary'])?>

</div>

<?php
$js = 
<<<JS
    $('#courselink-total').change(function(){    
        var oldValue = $('#courselink-completed').val();    
        var maxValue = $(this).val();    
        console.log(maxValue);  
        if(oldValue>maxValue)    
            oldValue = maxValue;    
        $("#courselink-completed").trigger("touchspin.updatesettings", {max: maxValue});  
        $('#courselink-completed').val(oldValue);  
    });  
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>       