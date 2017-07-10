<?php

use common\models\worksystem\WorksystemTask;
use kartik\daterange\DateRangePicker;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $form ActiveForm */
?>

<div class="worksystem-task-search">

    <?php $form = ActiveForm::begin([
        'options'=>[
            'id' => 'worksystem-task-search',
            'class'=>'form-horizontal',
            'action' => ['index'],
            'method' => 'post',
        ],
        'fieldConfig' => [
            'options' => [
                'class' => 'col-sm-4 col-xs-12',
            ],
            'template' => "{label}\n<div class=\"col-lg-9 col-md-9\" style=\"padding: 7px 10px\">{input}</div>\n",  
            'labelOptions' => [
                'class' => 'col-lg-3 col-md-3 control-label',
            ],  
        ], 
    ]); ?>
    
    <div class="col-xs-12 search"> 
        <div class="search-input">
            <?= Html::textInput('WorksystemTask[keyword]', ArrayHelper::getValue($params, 'keyword'), [
                'class' => 'form-control search-text-input',
                'placeholder' => '请输入关键字...'
            ]); ?>
        </div>
        <div class = "search-btn-bg">
            <?= Html::a(Yii::t('rcoa', 'Search'), 'javascript:;', ['id' => 'submit', 'class' => 'btn', 'style' => 'float: left;']); ?>
            <div class="direction" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                <span id="down" style="display: block;color: #fff">▼</span>
                <span id="up" style="display: none;color: #fff">▲</span>
            </div>
        </div>
    </div>
    
    <div class="collapse" id="collapseExample">
        <div class="col-xs-12 condition">
        
        <?= $form->field($model, 'task_type_id')->checkboxList($taskTypes, [
            'value' => ArrayHelper::getValue($params, 'task_type_id'),
            'itemOptions'=>[
                'labelOptions'=>[
                    'style'=>[
                        'margin-right'=>'10px',
                        'margin-top' => '5px'
                    ]
                ]
            ],     
        ]) ?>
            
        <div class="col-sm-4 col-xs-12">
            <label id="label-team_id" for="team_id" class="col-lg-3 col-xs-12 control-label" style="padding: 15px 0 !important;">
                <?= Yii::t('rcoa/worksystem', 'Create → Brace'); ?>
            </label>
             <div class="col-lg-4 col-md-5 col-sm-5 col-xs-5" style="padding: 7px 0 7px 10px">
                <?= Select2::widget([
                    'value' => ArrayHelper::getValue($params, 'create_team'),
                    'name' => 'WorksystemTask[create_team]',
                    'data' => $teams,
                    'options' => [
                        'placeholder' => '全部',
                    ],
                ]); ?>
            </div>
            <?=  Html::img(['/filedata/multimedia/image/brace.png'], [
                'width' => '15', 
                'height' => '15', 
                'style' => 'float: left; margin: 15px 0px;'
            ])?>
            <div class="col-lg-4 col-md-6 col-sm-5 col-xs-6" style="padding: 7px 0 7px 10px">
                <?= Select2::widget([
                    'value' => ArrayHelper::getValue($params, 'external_team'),
                    'name' => 'WorksystemTask[external_team]',
                    'data' => $teams,
                    'options' => [
                        'placeholder' => '全部',
                    ],
                ]); ?>
            </div>
        </div>   
            
        <div class="col-sm-4 col-xs-12">
            <label id="label-item_type_id" for="item_type_id" class="col-lg-3 control-label">
                <?= Yii::t('rcoa/worksystem', 'Item Type ID'); ?>
            </label>
            <div class="col-lg-9" style="padding: 7px 10px">
                <?= Select2::widget([
                    'id' => 'worksystemtask-item_type_id',
                    'value' => ArrayHelper::getValue($params, 'item_type_id'),
                    'name' => 'WorksystemTask[item_type_id]',
                    'data' => $itemTypes,
                    'options' => [
                        'placeholder' => '全部',
                    ],
                ]); ?>
            </div>
        </div>
            
        <div class="col-sm-4 col-xs-12">
            <label id="label-item_id" for="item_id" class="col-lg-3 control-label">
                <?= Yii::t('rcoa/worksystem', 'Item ID'); ?>
            </label>
            <div class="col-lg-9" style="padding: 7px 10px">
                <?= Select2::widget([
                    'id' => 'worksystemtask-item_id',
                    'value' => ArrayHelper::getValue($params, 'item_id'),
                    'name' => 'WorksystemTask[item_id]',
                    'data' => $items,
                    'options' => [
                        'placeholder' => '全部',
                    ],
                ]); ?>
            </div>
        </div>
            
       <div class="col-sm-4 col-xs-12">
            <label id="label-item_child_id" for="item_child_id" class="col-lg-3 control-label">
                <?= Yii::t('rcoa/worksystem', 'Item Child ID'); ?>
            </label>
            <div class="col-lg-9" style="padding: 7px 10px">
                <?= Select2::widget([
                    'id' => 'worksystemtask-item_child_id',
                    'value' => ArrayHelper::getValue($params, 'item_child_id'),
                    'name' => 'WorksystemTask[item_child_id]',
                    'data' => $itemChilds,
                    'options' => [
                        'placeholder' => '全部',
                    ],
                ]); ?>
            </div>
        </div> 
            
        <div class="col-sm-4 col-xs-12">
            <label id="label-course_id" for="course_id" class="col-lg-3 control-label">
                <?= Yii::t('rcoa/worksystem', 'Course ID'); ?>
            </label>
            <div class="col-lg-9" style="padding: 7px 10px">
                <?= Select2::widget([
                    'id' => 'worksystemtask-course_id',
                    'value' => ArrayHelper::getValue($params, 'course_id'),
                    'name' => 'WorksystemTask[course_id]',
                    'data' => $courses,
                    'options' => [
                        'placeholder' => '全部',
                    ],
                ]); ?>
            </div>
        </div>    
                        
        <div class="col-sm-4 col-xs-12">
            <label id="label-create_by" for="create_by" class="col-lg-3 control-label">
                <?= Yii::t('rcoa', 'Create By'); ?>
            </label>
            <div class="col-lg-9" style="padding: 7px 10px">
                <?= Select2::widget([
                    'value' => ArrayHelper::getValue($params, 'create_by'),
                    'name' => 'WorksystemTask[create_by]',
                    'data' => $createBys,
                    'options' => [
                        'placeholder' => '全部',
                    ],
                ]); ?>
            </div>
        </div>
            
        <div class="col-sm-4 col-xs-12">
            <label id="label-producer" for="producer" class="col-lg-3 control-label">
                <?= Yii::t('rcoa/worksystem', 'Producer'); ?>
            </label>
            <div class="col-lg-9" style="padding: 7px 10px">
                <?= Select2::widget([
                    'value' => ArrayHelper::getValue($params, 'producer'),
                    'name' => 'WorksystemTask[producer]',
                    'data' => $producers,
                    'options' => [
                        'placeholder' => '全部',
                    ],
                ]); ?>
            </div>
        </div>    
            
        <div class="col-sm-4 col-xs-12">
            <label id="label-status" for="status" class="col-lg-3 control-label">
                <?= Yii::t('rcoa', 'Status'); ?>
            </label>
            <div class="col-lg-9" style="padding: 7px 10px">
                <?= Select2::widget([
                    'value' => ArrayHelper::getValue($params, 'status'),
                    'name' => 'WorksystemTask[status]',
                    'data' => [WorksystemTask::STATUS_DEFAULT => '未完成', WorksystemTask::STATUS_COMPLETED => '已完成'], 'options' => ['placeholder' => '全部'],
                    'options' => [
                        'placeholder' => '全部',
                    ],
                ]); ?>
            </div>
        </div>    
            
        <div class="col-sm-4 col-xs-12">
            <label for="time" class="col-lg-3 control-label">
                <?= Yii::t('rcoa/teamwork', 'Statistics-Time-Rang'); ?>
            </label>
            <div class="col-lg-9" style="padding: 7px 10px;">
                <?= DateRangePicker::widget([
                    'value' => ArrayHelper::getValue($params, 'time'),
                    'name' => 'WorksystemTask[time]',
                    //'presetDropdown' => true,
                    'hideInput' => true,
                    'convertFormat'=>true,
                    'pluginOptions'=>[
                        'locale'=>['format' => 'Y-m-d'],
                        'allowClear' => true,
                        'ranges' => [
                            Yii::t('rcoa/teamwork', "Statistics-Prev-Week") => ["moment().startOf('week').subtract(1,'week')", "moment().endOf('week').subtract(1,'week')"],
                            Yii::t('rcoa/teamwork', "Statistics-This-Week") => ["moment().startOf('week')", "moment().endOf('week')"],
                            Yii::t('rcoa/teamwork', "Statistics-Prev-Month") => ["moment().startOf('month').subtract(1,'month')", "moment().endOf('month').subtract(1,'month')"],
                            Yii::t('rcoa/teamwork', "Statistics-This-Month") => ["moment().startOf('month')", "moment().endOf('month')"],
                            Yii::t('rcoa/teamwork', "Statistics-First-Half-Year") => ["moment().startOf('year')", "moment().startOf('year').add(5,'month').endOf('month')"],
                            Yii::t('rcoa/teamwork', "Statistics-Next-Half-Year") => ["moment().startOf('year').add(6,'month')", "moment().endOf('year')"],
                            Yii::t('rcoa/teamwork', "Statistics-Full-Year") => ["moment().startOf('year')", "moment().endOf('year')"],
                        ]
                    ],
                ]); ?>
            </div>
        </div>

        </div>
    </div>
    
    <?= Html::hiddenInput('WorksystemTask[mark]', true) ?>
    
    <?php ActiveForm::end(); ?>

</div>

<?php
$mark = ArrayHelper::getValue($params, 'mark');
$js = 
<<<JS
        
    if($mark){
        $('#collapseExample').addClass('in');
        $('#down').addClass('down');
        $('#up').addClass('up');
    }
        
    $('.direction').click(function()
    {
        $('#down').toggleClass('down');
        $('#up').toggleClass('up');
    });   
        
    /** 下拉选择【专业/工种】 */
    $('#worksystemtask-item_id').change(function(){
        var url = "/framework/api/search?id="+$(this).val(),
            element = $('#worksystemtask-item_child_id');
        $("#worksystemtask-item_child_id").html("");
        $('#select2-worksystemtask-item_child_id-container').html('<span class="select2-selection__placeholder">全部</span>');
        $("#worksystemtask-course_id").html('<option value="">全部</option>');
        $('#select2-worksystemtask-course_id-container').html('<span class="select2-selection__placeholder">全部</span>');
        wx(url, element, '全部');
    });
    /** 下拉选择【课程】 */
    $('#worksystemtask-item_child_id').change(function(){
        var url = "/framework/api/search?id="+$(this).val(),
            element = $('#worksystemtask-course_id');
        $("#worksystemtask-course_id").html("");
        $('#select2-worksystemtask-course_id-container').html('<span class="select2-selection__placeholder">全部</span>');
        wx(url, element, '全部');
    });
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>