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
        'id' => 'worksystem-task-search',
        'action' => ['index'],
        'method' => 'get',
        /*'fieldConfig' => [
            'options' => [
                'class' => 'col-sm-4 col-xs-12',
            ],
            'template' => "{label}\n<div class=\"col-sm-9 col-md-9\" style=\"padding: 7px 10px\">{input}</div>\n",  
            'labelOptions' => [
                'class' => 'col-sm-3 col-md-3 control-label',
            ],  
        ],*/
    ]); ?>
    
    <div class="col-xs-12 search"> 
        <div class="search-input">
            <?= Html::textInput('keyword', ArrayHelper::getValue($params, 'keyword'), [
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
            <!--任务类型-->
            <div class="col-lg-12 col-xs-12 control">
                <label id="label-task_type_id" for="worksystemtask-task_type_id" class="col-lg-1 control-label">
                    <?= Yii::t('rcoa/worksystem', 'Task Type ID'); ?>
                </label>
                <div class="col-lg-11  control-widget" style="padding: 0 10px;">
                    <?= Select2::widget([
                        'id' => 'worksystemtask-task_type_id',
                        'value' => ArrayHelper::getValue($params, 'task_type_id'),
                        'name' => 'task_type_id',
                        'data' => $taskTypes,
                        'options' => [
                            'placeholder' => '全部',
                            'multiple' => true, 
                        ],
                        'toggleAllSettings' => [
                            'selectLabel' => '<i class="glyphicon glyphicon-ok-circle"></i> 添加全部',
                            'unselectLabel' => '<i class="glyphicon glyphicon-remove-circle"></i> 取消全部',
                            'selectOptions' => ['class' => 'text-success'],
                            'unselectOptions' => ['class' => 'text-danger'],
                        ],
                    ]); ?>
                </div>
            </div>
            <!--创建 → 支撑-->
            <div class="col-lg-4 col-xs-12 control">
                <label id="label-team_id" for="worksystemtask-team_id" class="col-sm-3 col-xs-12 control-label" style="padding: 7px 0px 0px">
                    <?= Yii::t('rcoa/worksystem', 'Create → Brace'); ?>
                </label>
                <div class="col-sm-4 col-xs-5 control-widget">
                    <?= Select2::widget([
                        //'id' => 'worksystemtask-team_id',
                        'value' => ArrayHelper::getValue($params, 'create_team'),
                        'name' => 'create_team',
                        'data' => $createTeams,
                        'options' => [
                            'placeholder' => '全部',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
                <div class="col-sm-1 col-xs-2" style="padding: 9px 15px 9px 0;"><?=  Html::img(['/filedata/worksystem/image/brace.png'], ['class' => 'brace']) ?></div>
                <div class="col-sm-4 col-xs-5 control-widget" style="float: right;">
                    <?= Select2::widget([
                        //'id' => 'worksystemtask-team_id',
                        'value' => ArrayHelper::getValue($params, 'external_team'),
                        'name' => 'external_team',
                        'data' => $externalTeams,
                        'options' => [
                            'placeholder' => '全部',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
            </div>
            <!--行业-->            
            <div class="col-lg-4 col-xs-12 control">
                <label id="label-item_type_id" for="worksystemtask-item_type_id" class="col-sm-3 control-label">
                    <?= Yii::t('rcoa/worksystem', 'Item Type ID'); ?>
                </label>
                <div class="col-sm-9 control-widget">
                    <?= Select2::widget([
                        'id' => 'worksystemtask-item_type_id',
                        'value' => ArrayHelper::getValue($params, 'item_type_id'),
                        'name' => 'item_type_id',
                        'data' => $itemTypes,
                        'options' => [
                            'placeholder' => '全部',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
            </div>
            <!--层次/类型-->            
            <div class="col-lg-4 col-xs-12 control">
                <label id="label-item_id" for="worksystemtask-item_id" class="col-sm-3 control-label">
                    <?= Yii::t('rcoa/worksystem', 'Item ID'); ?>
                </label>
                <div class="col-sm-9 control-widget">
                    <?= Select2::widget([
                        'id' => 'worksystemtask-item_id',
                        'value' => ArrayHelper::getValue($params, 'item_id'),
                        'name' => 'item_id',
                        'data' => $items,
                        'options' => [
                            'placeholder' => '全部',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
            </div>
            <!--层次/类型-->            
            <div class="col-lg-4 col-xs-12  control">
                <label id="label-item_child_id" for="worksystemtask-item_child_id" class="col-sm-3 control-label">
                    <?= Yii::t('rcoa/worksystem', 'Item Child ID'); ?>
                </label>
                <div class="col-sm-9 control-widget">
                    <?= Select2::widget([
                        'id' => 'worksystemtask-item_child_id',
                        'value' => ArrayHelper::getValue($params, 'item_child_id'),
                        'name' => 'item_child_id',
                        'data' => $itemChilds,
                        'options' => [
                            'placeholder' => '全部',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
            </div>
            <!--课程名称-->            
            <div class="col-lg-4 col-xs-12 control">
                <label id="label-course_id" for="worksystemtask-course_id" class="col-sm-3 control-label">
                    <?= Yii::t('rcoa/worksystem', 'Course ID'); ?>
                </label>
                <div class="col-sm-9 control-widget">
                    <?= Select2::widget([
                        'id' => 'worksystemtask-course_id',
                        'value' => ArrayHelper::getValue($params, 'course_id'),
                        'name' => 'course_id',
                        'data' => $courses,
                        'options' => [
                            'placeholder' => '全部',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
            </div>
            <!--创建者-->            
            <div class="col-lg-4 col-xs-12 control">
                <label id="label-create_by" for="worksystemtask-create_by" class="col-sm-3 control-label">
                    <?= Yii::t('rcoa', 'Create By'); ?>
                </label>
                <div class="col-sm-9 control-widget">
                    <?= Select2::widget([
                        'id' => 'worksystemtask-create_by',
                        'value' => ArrayHelper::getValue($params, 'create_by', Yii::$app->user->id),
                        'name' => 'create_by',
                        'data' => $createBys,
                        'options' => [
                            'placeholder' => '全部',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
            </div>
            <!--制作人-->  
            <div class="col-lg-4 col-xs-12 control">
                <label id="label-producer" for="worksystemtask-producer" class="col-sm-3 control-label">
                    <?= Yii::t('rcoa/worksystem', 'Producer'); ?>
                </label>
                <div class="col-sm-9 control-widget">
                    <?= Select2::widget([
                        'id' => 'worksystemtask-producer',
                        'value' => ArrayHelper::getValue($params, 'producer', Yii::$app->user->id),
                        'name' => 'producer',
                        'data' => $producers,
                        'options' => [
                            'placeholder' => '全部',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
            </div>
            <!--状态-->  
            <div class="col-lg-4 col-xs-12 control">
                <label id="label-status" for="worksystemtask-status" class="col-sm-3 control-label">
                    <?= Yii::t('rcoa', 'Status'); ?>
                </label>
                <div class="col-sm-9 control-widget">
                    <?= Select2::widget([
                        'id' => 'worksystemtask-status',
                        'value' => ArrayHelper::getValue($params, 'status', WorksystemTask::STATUS_DEFAULT),
                        'name' => 'status',
                        'data' => [WorksystemTask::STATUS_DEFAULT => '未完成', WorksystemTask::STATUS_COMPLETED => '已完成'],
                        'options' => [
                            'placeholder' => '全部',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]); ?>
                </div>
            </div>
            <!--时间段-->  
            <div class="col-lg-4 col-xs-12 control">
                <label id="label-time" for="worksystemtask-time" class="col-sm-3 control-label">
                    <?= Yii::t('rcoa/teamwork', 'Statistics-Time-Rang'); ?>
                </label>
                <div class="col-sm-9 control-widget">
                   <?= DateRangePicker::widget([
                       'id' => 'worksystemtask-time',
                        'value' => ArrayHelper::getValue($params, 'time'),
                        'name' => 'time',
                        //'presetDropdown' => true,
                        'hideInput' => true,
                        'convertFormat'=>true,
                        'pluginOptions'=>[
                            'locale'=>['format' => 'Y-m-d'],
                            'allowClear' => true,
                            'opens'=>'right',
                            'ranges' => [
                                Yii::t('rcoa/teamwork', "Statistics-Prev-Week") => ["moment().startOf('week').subtract(1,'week')", "moment().endOf('week').subtract(1,'week')"],
                                Yii::t('rcoa/teamwork', "Statistics-This-Week") => ["moment().startOf('week')", "moment().endOf('week')"],
                                Yii::t('rcoa/teamwork', "Statistics-Prev-Month") => ["moment().startOf('month').subtract(1,'month')", "moment().endOf('month').subtract(1,'month')"],
                                Yii::t('rcoa/teamwork', "Statistics-This-Month") => ["moment().startOf('month')", "moment().endOf('month')"],
                                Yii::t('rcoa/teamwork', "Statistics-First-Half-Year") => ["moment().startOf('year')", "moment().startOf('year').add(5,'month').endOf('month')"],
                                Yii::t('rcoa/teamwork', "Statistics-Next-Half-Year") => ["moment().startOf('year').add(6,'month')", "moment().endOf('year')"],
                                Yii::t('rcoa/teamwork', "Statistics-Full-Year") => ["moment().startOf('year')", "moment().endOf('year')"],
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
           

        </div>
    </div>
    
    <?= Html::hiddenInput('mark', 1) ?>
    
    <?php ActiveForm::end(); ?>

</div>

<?php
$mark = ArrayHelper::getValue($params, 'mark', 0);
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