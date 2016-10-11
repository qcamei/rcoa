<?php

use common\models\multimedia\MultimediaTask;
use kartik\daterange\DateRangePicker;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

?>

<?php $form = ActiveForm::begin([
    'id' => 'multimedia-task-search',
    'action' => ['list'],
    'method' => 'get',
]); ?>

<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search-box"> 
    <div class="search-text-input">
        <?= Html::textInput('keyword', $keyword, [
            'class' => 'form-control search-input',
            'placeholder' => '请输入关键字...'
        ]); ?>
    </div>
    <div class = "search-btn-bg">
        <?= Html::a(Yii::t('rcoa', 'Search'), 'javascript:;', ['id' => 'submit', 'class' => 'btn', 'style' => 'float: left;']); ?>
        <div class="search-arrow-box" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
            <span id="down" style="display: block;">▼</span>
            <span id="up" style="display: none;">▲</span>
        </div>
    </div>
</div>
<div class="collapse" id="collapseExample">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 search-list">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding: 5px 0px;">
            <label id="label-team_id" for="team_id" class="col-lg-3 col-xs-12 control-label" style="padding-right: 0px;">
                <?= Yii::t('rcoa/multimedia', 'Create Brace'); ?>
            </label>
            <div class="col-lg-4 col-md-5 col-sm-5 col-xs-5" style="padding-right: 0px;">
                <?= Select2::widget([
                    'id' => 'select2-team_id',
                    'value'=> $create_team,
                    'name' => 'create_team',
                    'data' => $team,
                    'options' => [
                        'placeholder' => '全部',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            <?=  Html::img(['/filedata/multimedia/image/brace.png'], [
                'width' => '15', 
                'height' => '15', 
                'style' => 'float: left; margin: 9px 0px;'
            ])?>
            <div class="col-lg-4 col-md-6 col-sm-5 col-xs-6" style="padding-right: 0px;">
                <?= Select2::widget([
                    'id' => 'select2-brace_team_id',
                    'value'=> $make_team,
                    'name' => 'make_team',
                    'data' => $team,
                    'options' => [
                        'placeholder' => '全部',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding: 5px 0px;">
            <label id="label-item_type_id" for="content_type" class="col-lg-3 control-label">
                <?= Yii::t('rcoa/multimedia', 'Content Type'); ?>
            </label>
            <div class="col-lg-9">
                <?= Select2::widget([
                    'id' => 'select2-content_type',
                    'value'=> $content_type,
                    'name' => 'content_type',
                    'data' => $contentType,
                    'options' => [
                        'placeholder' => '全部',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding: 5px 0px;">
            <label id="label-item_type_id" for="item_type_id" class="col-lg-3 control-label">
                <?= Yii::t('rcoa/multimedia', 'Item Type'); ?>
            </label>
            <div class="col-lg-9">
                <?= Select2::widget([
                    'id' => 'select2-item_type_id',
                    'value'=> $item_type_id,
                    'name' => 'item_type_id',
                    'data' => $itemType,
                    'options' => [
                        'placeholder' => '全部',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding: 5px 0px;">
            <label id="label-item_id" for="item_id" class="col-lg-3 control-label">
                <?= Yii::t('rcoa/multimedia', 'Item'); ?>
            </label>
            <div class="col-lg-9">
                <?= Select2::widget([
                    'id' => 'select2-item_id',
                    'value'=> $item_id,
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
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding: 5px 0px;">
            <label id="label-item_child_id" for="item_child_id" class="col-lg-3 control-label">
                <?= Yii::t('rcoa/multimedia', 'Item Child'); ?>
            </label>
            <div class="col-lg-9">
                <?= Select2::widget([
                    'id' => 'select2-item_child_id',
                    'value'=> $item_child_id,
                    'name' => 'item_child_id',
                    'data' => $itemChild,
                    'options' => [
                        'placeholder' => '全部',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding: 5px 0px;">
            <label id="label-course_id" for="course_id" class="col-lg-3 control-label">
                <?= Yii::t('rcoa/multimedia', 'Course'); ?>
            </label>
            <div class="col-lg-9">
                <?= Select2::widget([
                    'id' => 'select2-course_id',
                    'value'=> $course_id,
                    'name' => 'course_id',
                    'data' => $course,
                    'options' => [
                        'placeholder' => '全部',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding: 5px 0px;">
            <label id="label-status" for="create_by" class="col-lg-3 control-label">
                <?= Yii::t('rcoa', 'Create By'); ?>
            </label>
            <div class="col-lg-9">
                <?= Select2::widget([
                    'id' => 'select2-create_by',
                    'value'=> $create_by,
                    'name' => 'create_by',
                    'data' => $createBy,
                    'options' => [
                        'placeholder' => '全部',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding: 5px 0px;">
            <label id="label-status" for="producer" class="col-lg-3 control-label">
                <?= Yii::t('rcoa/multimedia', 'Producer'); ?>
            </label>
            <div class="col-lg-9">
                <?= Select2::widget([
                    'id' => 'select2-producer',
                    'value'=> $producer,
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
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding: 5px 0px;">
            <label id="label-status" for="status" class="col-lg-3 control-label">
                <?= Yii::t('rcoa', 'Status'); ?>
            </label>
            <div class="col-lg-9">
                <?= Select2::widget([
                    'id' => 'select2-status',
                    'value'=> $status,
                    'name' => 'status',
                    'data' => [
                        1 => '未完成', 
                        MultimediaTask::STATUS_COMPLETED => '已完成', 
                        MultimediaTask::STATUS_CANCEL => '已取消'
                    ],
                    'options' => [
                        'placeholder' => '全部',
                        //'multiple' => true
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding: 5px 0px;">
            <label for="time" class="col-lg-3 control-label">
                <?= Yii::t('rcoa/teamwork', 'Statistics-Time-Rang'); ?>
            </label>
            <div class="col-lg-9">
                <?= DateRangePicker::widget([
                    'value'=> $time,
                    'name' => 'time',
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
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding: 5px 15px;">
            <a id="export" role="button" class="col-lg-3 btn btn-primary disabled">导出详细</a>
        </div>
    </div>
</div>

<?= Html::hiddenInput('mark', 1)?>

<?php ActiveForm::end(); ?>  

<?php
//$url = Yii::$app->urlManager->createUrl(['teamwork/course/search']);

$js = 
<<<JS
    var mark = $mark;
    if(mark == 1){
        $('#collapseExample').addClass('in');
        $('#down').addClass('down');
        $('#up').addClass('up');
    }
    $('.search-arrow-box').click(function()
    {
        $('#down').toggleClass('down');
        $('#up').toggleClass('up');
    });
    /** 提交表单数据 */
    $('#submit').click(function(){
        $('#multimedia-task-search').submit();
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
        
    $('#export').click(function(){
        location.href = "/teamwork/export/run?" + $('#course-manage-search').serialize();
    });
     
JS;
    $this->registerJs($js,  View::POS_READY);
?>

