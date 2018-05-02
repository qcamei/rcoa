<?php

use common\models\teamwork\ItemManage;
use kartik\datecontrol\DateControl;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
//use kartik\widgets\ActiveForm;

/* @var $this View */
/* @var $model ItemManage */
/* @var $form ActiveForm */
?>

<div class="item-manage-form">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'item-manage-form',
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
    
    <h5><b>基础信息</b></h5>
    
    <?= $form->field($model, 'item_type_id')->widget(Select2::classname(), [
        'data' => $itemType, 'options' => ['placeholder' => '请选择...']
    ]) ?>

    <?= $form->field($model, 'item_id')->widget(Select2::classname(), [
        'data' => $items, 'options' => ['placeholder' => '请选择...',]
    ]) ?>
    
    <?= $form->field($model, 'item_child_id')->widget(Select2::classname(), [
        'data' => $itemChilds, 'options' => ['placeholder' => '请选择...']
    ]) ?>
    
    <h5><b>开发信息</b></h5>
    
    <?php
        echo Html::beginTag('div', ['class' => 'form-group field-itemmanage-forecast_time has-success']);
            echo Html::beginTag('label', [
                    'class' => 'col-lg-1 col-md-1 control-label', 
                    'style' => 'color: #999999; font-weight: normal;padding-right:0;padding-left:10px;',
                    'for' => 'itemmanage-forecast_time'
                ]).Yii::t('rcoa/teamwork', 'Forecast Time').Html::endTag('label');
            echo Html::beginTag('div', ['class' => 'col-sm-4']);
                echo DateControl::widget([
                    'name' => 'ItemManage[forecast_time]',
                    'value' => $model->isNewRecord ? date('Y-m-d H:i', strtotime('+3 day')) : $model->forecast_time, 
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

    <h5><b>其他信息</b></h5>
    
    <?= $form->field($model, 'background')->textarea(['rows' => 4]) ?>

    <?= $form->field($model, 'use')->textarea(['rows' => 4]) ?>

    <?php ActiveForm::end(); ?>

</div>

<?php
$js = 
<<<JS
    $('#itemmanage-item_id').change(function(){
        var url = "/teamwork/default/search-select?id="+$(this).val(),
            element = $('#itemmanage-item_child_id');
        $("#itemmanage-item_child_id").html("");
        $('#select2-itemmanage-item_child_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        wx(url, element, '请选择...');
    });
     
JS;
    $this->registerJs($js,  View::POS_READY);
?>
