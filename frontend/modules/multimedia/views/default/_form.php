<?php

use common\models\multimedia\MultimediaManage;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model MultimediaManage */
/* @var $form ActiveForm */
?>

<div class="multimedia-manage-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'multimedia-manage-form',
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
    
    <h5><b>基本信息</b></h5>
    <?= $form->field($model, 'item_type_id')->widget(Select2::className(), [
        'data' => $itemType, 'options' => ['placeholder' => '请选择...']
    ]) ?>

    <?= $form->field($model, 'item_id')->widget(Select2::className(), [
        'data' => $item, 'options' => ['placeholder' => '请选择...']
    ]) ?>

    <?= $form->field($model, 'item_child_id')->widget(Select2::className(), [
        'data' => $itemChild, 'options' => ['placeholder' => '请选择...']
    ]) ?>

    <?= $form->field($model, 'course_id')->widget(Select2::className(), [
        'data' => $course, 'options' => ['placeholder' => '请选择...']
    ]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'placeholder' => '请输入任务名称...']) ?>

    <?= $form->field($model, 'video_length', [
        'labelOptions'=> [
            'style' => [
                'color'=>'#999999',
                'font-weight'=>'normal',
                'padding-right' => '0',
                'padding-left' => '0',
            ]
        ]
    ])->textInput(['placeholder' => '00:00:00']) ?>
    
    <h5><b>开发信息</b></h5>
    <?= $form->field($model, 'content_type')->textInput() ?>

    <?php
        echo Html::beginTag('div', ['class' => 'form-group field-multimediamanage-carry_out_time has-success']);
            echo Html::beginTag('label', [
                    'class' => 'col-lg-1 col-md-1 control-label', 
                    'style' => 'color: #999999; font-weight: normal; padding-right: 0; padding-left: 0',
                    'for' => 'multimediamanage-carry_out_time'
                ]).Yii::t('rcoa/multimedia', 'Carry Out Time').Html::endTag('label');
            echo Html::beginTag('div', ['class' => 'col-sm-4']);
                echo DateControl::widget([
                    'name' => 'MultimediaManage[carry_out_time]',
                    'value' => $model->isNewRecord ? date('Y-m-d H:i', strtotime('+1 days')) : $model->carry_out_time, 
                    'type'=> DateControl::FORMAT_DATETIME,
                    'displayFormat' => 'yyyy-MM-dd H:i',
                    'saveFormat' => 'yyyy-MM-dd H:i',
                    'ajaxConversion'=> true,
                    'autoWidget' => true,
                    'readonly' => true,
                    'options' => [
                        'pluginOptions' => [
                            'autoclose' => true,
                        ],
                    ],
                ]);
                echo Html::beginTag('div', ['class' => 'col-lg-10 col-md-10']).Html::beginTag('div', ['class' => 'help-block']).Html::endTag('div').Html::endTag('div');
            echo Html::endTag('div');
        echo Html::endTag('div');
    ?>

    <?= $form->field($model, 'path')->textInput([
        'maxlength' => true,
        'placeholder' => '请输入框架表路径...'
    ]) ?>
    
    <?= $form->field($model, 'level')->radioList(MultimediaManage::$levelName, [
        'separator'=>'',
        'itemOptions'=>[
            'labelOptions'=>[
                'style'=>[
                    'margin-right'=>'30px',
                    'margin-top' => '5px'
                ]
            ]
        ],     
    ]) ?>

    <h5><b>其它信息</b></h5>
    <?= $form->field($model, 'des')->textarea(['rows' => 4]) ?>

    <?php ActiveForm::end(); ?>

</div>


<?php
$js = 
<<<JS
    /** 下拉选择【专业/工种】 */
    $('#multimediamanage-item_id').change(function(){
        var url = "/framework/api/search?id="+$(this).val(),
            element = $('#multimediamanage-item_child_id');
        $("#multimediamanage-item_child_id").html("");
        $('#select2-multimediamanage-item_child_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        wx(url, element, '请选择...');
    });
    /** 下拉选择【课程】 */
    $('#multimediamanage-item_child_id').change(function(){
        var url = "/framework/api/search?id="+$(this).val(),
            element = $('#multimediamanage-course_id');
        $("#multimediamanage-course_id").html("");
        $('#select2-multimediamanage-course_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        wx(url, element, '请选择...');
    });
     
JS;
    $this->registerJs($js,  View::POS_READY);
?>