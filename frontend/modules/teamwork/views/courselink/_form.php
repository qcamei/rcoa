<?php

use common\models\teamwork\CourseLink;
use common\models\teamwork\CoursePhase;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model CourseLink */
/* @var $phaseModel CoursePhase */
/* @var $form ActiveForm */
?>

<div class="course-link-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'course-form',
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
    ]) ?>
    
    <?= $form->field($phaseModel, 'id')->widget(Select2::classname(), [
        'data' => $phase, 
        'hideSearch' => true,
        'disabled' => !$phaseModel->isNewRecord ? true : false,
        'options' => [
            'placeholder' => '请选择...'
        ],
        'pluginEvents' => [
            'change' => 'function(){wx_one(this);}'
        ]
    ]) ?>

    <?php echo Html::beginTag('div', ['class' => 'form-group field-courselink-link_id has-success']);
            echo Html::beginTag('label', ['class' => 'col-lg-1 col-md-1 control-label',
                    'style' => 'color: #999999; font-weight: normal; padding-left: 0; padding-right: 0;',
                    'for' => 'courselink-link_id'
                ]).'环节名称'.Html::endTag('label');
            echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']).
                    Select2::widget([
                        'name' => 'id',
                        'id' => 'courselink-link_id',
                        'data' => $link, 
                        'hideSearch' => true,
                        //'disabled' => empty($link) ? true : false,
                        'options' => [
                            'placeholder' => '请选择...',
                            'multiple' => true,    
                         ],
                        'toggleAllSettings' => [
                            'selectLabel' => '',
                            'unselectLabel' => '',
                            'selectOptions' => ['class' => ''],
                            'unselectOptions' => ['class' => ''],
                        ],
                    ]).Html::endTag('div');
            echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']).
                     Html::beginTag('div', ['class' => 'help-block']).Html::endTag('div').Html::endTag('div');
           
        echo Html::endTag('div');
    ?>
    
    
    
    <?= $form->field($phaseModel, 'weights')->textInput( ) ?>

    <?php ActiveForm::end(); ?>

</div>

<script type="text/javascript">
    function wx_one(e){
        $('.select2-selection__choice').remove();
	$("#courselink-link_id").html("");
	$.post("/teamwork/courselink/search?id="+$(e).val(),function(data)
        {
            $('<option/>').appendTo($("#courselink-link_id"));
            $.each(data['data'],function()
            {
                $('<option>').val(this['link']['id']).text(this['link']['name']).appendTo($("#courselink-link_id"));
                $('#coursephase-weights').val(this['weights']);
            });
	});
    }
</script>
