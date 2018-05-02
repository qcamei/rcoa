<?php

use common\models\need\NeedTask;
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
        'options'=>['id' => 'need-task-form', 'class'=>'form-horizontal'],
        'action' => ['index'], 
        'method' => 'get',
        'fieldConfig' => [  
            'template' => "{label}\n<div class=\"col-lg-9 col-md-9\">{input}</div>",  
            'labelOptions' => [
                'class' => 'col-lg-3 col-md-3 control-label',
            ],  
        ], 
    ]); ?>
    
    <div class="col-xs-12 search"> 
        <div class="input">
            <?= $form->field($model, 'keyword', [
                'template' => "<div class=\"col-lg-12 col-md-12\">{input}</div>",  
            ])->textInput(['class' => 'form-control keyword', 'placeholder' => '请输入关键字...']) ?>
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
                'data' => $allBusiness,  'hideSearch' => true,
                'options' => ['placeholder' => '全部'], 'pluginOptions' => ['allowClear' => true]
            ]) ?>
            </div>
            
            <div class="col-lg-4 col-md-4 frame">
            <?= $form->field($model, 'layer_id')->widget(Select2::class, [
                'data' => $allLayer, 'hideSearch' => true,
                'options' => ['placeholder' => '全部'], 'pluginOptions' => ['allowClear' => true]
            ]) ?>
            </div>
            
            <div class="col-lg-4 col-md-4 frame">
            <?= $form->field($model, 'profession_id')->widget(Select2::class, [
                'data' => $allProfession, 'hideSearch' => true,
                'options' => ['placeholder' => '全部'], 'pluginOptions' => ['allowClear' => true]
            ]) ?>
            </div>
            
            <div class="col-lg-4 col-md-4 frame">
            <?= $form->field($model, 'course_id')->widget(Select2::class, [
                'data' => $allCourse, 'hideSearch' => true,
                'options' => ['placeholder' => '全部'], 'pluginOptions' => ['allowClear' => true]
            ]) ?>
            </div>
            
            <div class="col-lg-4 col-md-4 frame">
            <?= $form->field($model, 'created_by')->widget(Select2::class, [
                'data' => $allCreatedBy, 'hideSearch' => true,
                'options' => ['placeholder' => '全部'], 'pluginOptions' => ['allowClear' => true]
            ]) ?>
            </div>
            
            <div class="col-lg-4 col-md-4 frame">
            <?= $form->field($model, 'receive_by')->widget(Select2::class, [
                'data' => $allReceiveBy, 'hideSearch' => true,
                'options' => ['placeholder' => '全部'], 'pluginOptions' => ['allowClear' => true]
            ]) ?>
            </div>
            
            <div class="col-lg-4 col-md-4 frame">
            <?= $form->field($model, 'status')->widget(Select2::class, [
                'data' => [0 => '未完成', NeedTask::STATUS_FINISHED => '已完成'],
                'hideSearch' => true,
                'options' => ['placeholder' => '全部'], 'pluginOptions' => ['allowClear' => true]
            ]) ?>
            </div>
            
        </div>
    </div>
    
    <?= Html::hiddenInput('is_search', 1) ?>
    
    <?php ActiveForm::end(); ?>

</div>

<?php
$is_search = ArrayHelper::getValue(Yii::$app->request->queryParams, 'is_search', 0);    //是否为搜索
$js = 
<<<JS
    //如果是附加了条件的搜索的情况下默认显示高级搜索
    if($is_search){
        $('.collapse').addClass('in');
        $('.search .btngroup > a.arrow > i').removeClass('fa-caret-down').addClass('fa-caret-up');
    }
    //单击切换图标
    $('.search .btngroup > a.arrow').click(function(){
        if($(this).attr('aria-expanded') == 'false'){
            $(this).find('i').removeClass('fa-caret-down').addClass('fa-caret-up');
        }else{
            $(this).find('i').removeClass('fa-caret-up').addClass('fa-caret-down');
        }
    });
    //提交搜索 
    $('#submit').click(function(){
        $('#need-task-form').submit();
    });
        
    /** 下拉选择【专业/工种】 */
    $('#needtasksearch-layer_id').change(function(){
        $("#needtasksearch-profession_id").html("");
        $('#select2-needtasksearch-profession_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        $("#needtasksearch-course_id").html("");
        $('#select2-needtasksearch-course_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        //获取【专业/工种】，组装下拉
        $.post("/framework/api/search?id="+$(this).val(), function(rel){
            $('<option/>').val('').text('请选择...').appendTo($('#needtasksearch-profession_id'));
            $.each(rel['data'], function(){
                $('<option>').val(this['id']).text(this['name']).appendTo($('#needtasksearch-profession_id'));
            });
        });
    });
    /** 下拉选择【课程】 */
    $('#needtasksearch-profession_id').change(function(){
        $("#needtasksearch-course_id").html("");
        $('#select2-needtasksearch-course_id-container').html('<span class="select2-selection__placeholder">请选择...</span>');
        //获取【课程】，组装下拉
        $.post("/framework/api/search?id="+$(this).val(), function(rel){
            $('<option/>').val('').text('请选择...').appendTo($('#needtasksearch-course_id'));
            $.each(rel['data'], function(){
                $('<option>').val(this['id']).text(this['name']).appendTo($('#needtasksearch-course_id'));
            });
        });
    });
        
    /** 导出数据 */    
    $('#export').click(function(){
        location.href = "/teamwork/export/run?" + $('#course-manage-search').serialize();
    });
JS;
    $this->registerJs($js,  View::POS_READY);
?>