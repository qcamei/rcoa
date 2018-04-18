<?php

use common\models\need\searchs\NeedTaskSearch;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model NeedTaskSearch */
/* @var $form ActiveForm */
?>

<div class="need-task-search">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'need-task-form',
            'class'=>'form-horizontal',
            'action' => ['index'],
            'method' => 'get',
        ],
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-9 col-md-9\">{input}</div>",  
            'labelOptions' => [
                'class' => 'col-lg-3 col-md-3 control-label',
            ],  
        ], 
    ]); ?>
    
    <div class="col-xs-12 search"> 
        <div class="input">
            <?= Html::textInput('keyword', ArrayHelper::getValue([], 'keyword'), ['class' => 'form-control keyword', 'placeholder' => '请输入关键字...']); ?>
        </div>
        <div class="btngroup">
            <?= Html::a(Yii::t('rcoa', 'Search'), 'javascript:;', ['id' => 'submit', 'class' => 'btn']); ?>
            <?= Html::a('<i class="fa fa-caret-down"></i>', 'javascript:;', ['class' => 'arrow collapsed', 
                'data-toggle' => 'collapse', 'data-target' => '#collapse', 'aria-expanded' => 'false',
                'aria-controls' => 'collapse',
            ]) ?>
        </div>
    </div>

    <div id="collapse" class="collapse" aria-expanded="false">
        <div class="col-xs-12 search-frame">
            
            <div class="col-lg-4 col-md-4 frame">
            <?= $form->field($model, 'business_id')->widget(Select2::class, [
                'options' => ['placeholder' => '全部'], 'pluginOptions' => ['allowClear' => true]
            ]) ?>
            </div>
            
            <div class="col-lg-4 col-md-4 frame">
            <?= $form->field($model, 'layer_id')->widget(Select2::class, [
                'options' => ['placeholder' => '全部'], 'pluginOptions' => ['allowClear' => true]
            ]) ?>
            </div>
            
            <div class="col-lg-4 col-md-4 frame">
            <?= $form->field($model, 'profession_id')->widget(Select2::class, [
                'options' => ['placeholder' => '全部'], 'pluginOptions' => ['allowClear' => true]
            ]) ?>
            </div>
            
            <div class="col-lg-4 col-md-4 frame">
            <?= $form->field($model, 'course_id')->widget(Select2::class, [
                'options' => ['placeholder' => '全部'], 'pluginOptions' => ['allowClear' => true]
            ]) ?>
            </div>
            
            <div class="col-lg-4 col-md-4 frame">
            <?= $form->field($model, 'created_by')->widget(Select2::class, [
                'options' => ['placeholder' => '全部'], 'pluginOptions' => ['allowClear' => true]
            ]) ?>
            </div>
            
            <div class="col-lg-4 col-md-4 frame">
            <?= $form->field($model, 'receive_by')->widget(Select2::class, [
                'options' => ['placeholder' => '全部'], 'pluginOptions' => ['allowClear' => true]
            ]) ?>
            </div>
            
            <div class="col-lg-4 col-md-4 frame">
            <?= $form->field($model, 'status')->widget(Select2::class, [
                'options' => ['placeholder' => '全部'], 'pluginOptions' => ['allowClear' => true]
            ]) ?>
            </div>
            
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>

<?php
$js = 
<<<JS
    //如果是附加了条件的搜索的情况下默认显示高级搜索
    if(0){
        $('.collapse').addClass('in');
        $('.search .btngroup > a.arrow').html('<i class="fa fa-caret-up"></i>');
    }
    //单击切换图标
    $('.search .btngroup > a.arrow').click(function(){
        if($(this).attr('aria-expanded') === 'false'){
            $(this).html('<i class="fa fa-caret-up"></i>');
        }else{
            $(this).html('<i class="fa fa-caret-down"></i>');
        }
    });
    //提交搜索 
    $('#submit').click(function(){
        $('#need-task-form').submit();
    });
        
    $('#select2-item_id').change(function(){
        var url = "/framework/api/search?id="+$(this).val(),
            element = $('#select2-item_child_id');
        $("#select2-item_child_id").html("");
        $("#select2-select2-item_child_id-container").html('<span class="select2-selection__placeholder">全部</span>');
        $("#select2-course_id").html("");
        $("#select2-select2-course_id-container").html('<span class="select2-selection__placeholder">全部</span>');
        wx(url, element, '全部');
    });
    $('#select2-item_child_id').change(function(){
        var url = "/framework/api/search?id="+$(this).val(),
            element = $('#select2-course_id');
        $("#select2-course_id").html("");
        $("#select2-select2-course_id-container").html('<span class="select2-selection__placeholder">全部</span>');
        wx(url, element, '全部');
    });
    /** 导出数据 */    
    $('#export').click(function(){
        location.href = "/teamwork/export/run?" + $('#course-manage-search').serialize();
    });
JS;
    $this->registerJs($js,  View::POS_READY);
?>