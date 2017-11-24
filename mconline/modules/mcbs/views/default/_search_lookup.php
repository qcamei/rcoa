<?php

use kartik\widgets\Select2;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $form ActiveForm */
?>

<div class="default-search">
    
    <?php $form = ActiveForm::begin([
        'id' => 'mcbs-search',
        'action' => ['lookup'],
        'method' => 'get',
    ]); ?>
    
    <div class="col-xs-12 search"> 
        <div class="search-input">
            <?= Html::textInput('keyword', ArrayHelper::getValue($params, 'keyword'), [
                'class' => 'form-control search-text-input',
                'placeholder' => '输入名称查询课程'
            ]); ?>
        </div>
        <div class = "search-btn-bg">
            <?= Html::a('', 'javascript:;', ['id' => 'submit', 'class' => 'btn fa fa-search', 'style' => 'float: left;']); ?>
        </div>
    </div>
    
    <div class="collapse in visible-lg-block" id="collapseExample">
        <div class="col-xs-12 condition">
            <!--层次/类型-->            
            <div class="col-lg-4 col-xs-12 control">
                <div class="col-sm-9 control-widget">
                    <?= Select2::widget([
                        'id' => 'mcbs-item_id',
                        'value' => ArrayHelper::getValue($params, 'item_id'),
                        'name' => 'item_id',
                        'data' => $items,
                        'options' => [
                            'placeholder' => '层次/类型',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
            </div>
            <!--专业/工种-->            
            <div class="col-lg-4 col-xs-12  control">
                <div class="col-sm-9 control-widget">
                    <?= Select2::widget([
                        'id' => 'mcbs-item_child_id',
                        'value' => ArrayHelper::getValue($params, 'item_child_id'),
                        'name' => 'item_child_id',
                        'data' => $itemChilds,
                        'options' => [
                            'placeholder' => '专业/工种',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
            </div>
            <!--创建者-->            
            <div class="col-lg-4 col-xs-12 control">
                <div class="col-sm-9 control-widget">
                    <?= Select2::widget([
                        'id' => 'mcbs-created_by',
                        'value' => ArrayHelper::getValue($params, 'created_by', '创建者'),
                        'name' => 'created_by',
                        'data' => $createBys,
                        'options' => [
                            'placeholder' => '创建者',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php ActiveForm::end(); ?>

</div>

<?php

$js = 
<<<JS
        
    /** 下拉选择【专业/工种】 */
    $('#mcbs-item_id').change(function(){
        $("#mcbs-item_child_id").html("");
        $('#select2-mcbs-item_child_id-container').html('<span class="select2-selection__placeholder">全部</span>');
        $("#mcbs-course_id").html('<option value="">全部</option>');
        $('#select2-mcbs-course_id-container').html('<span class="select2-selection__placeholder">全部</span>');
        $.post("/framework/api/search?id="+$(this).val(),function(data)
        {
            $('<option/>').val('').text(this['name']).appendTo($('#mcbs-item_child_id'));
            $.each(data['data'],function()
            {
                $('<option>').val(this['id']).text(this['name']).appendTo($('#mcbs-item_child_id'));
            });
        });
    });
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>
