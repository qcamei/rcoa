<?php

use common\models\teamwork\CourseManage;
use common\models\teamwork\CourseSummary;
use frontend\modules\teamwork\TwAsset;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

$this->title = Yii::t('rcoa/teamwork', 'Create Course Summary');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
    $weekinfo = [];
    $results = [];
    foreach ($twTool->getWeekInfo(end($weeklyMonth)) as $value) {
        $result = $twTool->getWeeklyInfo($model->id, $value['start'], $value['end']);
        $weekinfo[] = [
            'date' => date('m/d', strtotime($value['start'])).'～'.date('m/d', strtotime($value['end'])),
            'class' => !empty($result) ?  'btn btn-info weekinfo' : 'btn btn-info weekinfo disabled',
            'start' => $value['start'],
            'end' => $value['end']
        ];
        if(!empty($result)){
            $results = [
                'course_id' => $result->course_id,
                'create_time' => $result->create_time < $value['start'] ? null : $result->create_time,
                'content' => $result->content,
                'create_by' => $result->weeklyCreateBy->weeklyEditorsPeople->u->nickname.
                                '('.$result->weeklyCreateBy->weeklyEditorsPeople->position.')',
                'created_at' => date('Y-m-d H:i', $result->created_at)
            ];
        }
    }
    if(empty($results)){
        $results = [
            'course_id' => null,
            'create_time' => null,
            'content' => '无',
            'create_by' => '无',
            'created_at' => '无'
        ];
    }
?>

<h4> 开发周报：</h4>
    <span style="color: blue;">本周开发者：<?= empty($model->weekly_editors_people)? '无' : 
            $model->weeklyEditorsPeople->u->nickname.' ('.$model->weeklyEditorsPeople->position.')' ?>
    </span>
    
    <div class="row">
    <?php
        echo Html::beginTag('div', ['class' => 'col-lg-2 col-md-2 col-sm-12 col-xs-12', 
            'style' => 'padding:0px;margin: 5px 5px 5px 0px;']);
            echo  Select2::widget([
                'name' => 'create_time',
                'value' => end($weeklyMonth),
                'data' => $weeklyMonth,
                'hideSearch' => true,
                'options' => [
                    'id' => 'weekly-month',
                    'placeholder' => '请选择月份...',
                ],
                'pluginEvents' => [
                    'change' => 'function(){ select2Log($(this));}'
                ]
             ]);
        echo Html::endTag('div');
        
        echo Html::beginTag('div', [
            'id' => 'weekinfo',
            'class' => 'col-lg-7 col-md-8 col-sm-10 col-xs-12', 
            'style' => 'padding:0px;'
        ]);
        echo Html::endTag('div');
        
        echo Html::beginTag('div', ['class' => 'col-lg-2 col-md-2 col-sm-2 col-xs-5', 'style' => 'padding:0px;']).
             Html::a('编辑', [
                'summary/update', 'course_id' => $model->id, 'create_time' => $results['create_time'],], 
                ['class' => 'btn btn-primary weekinfo']).' '.
             Html::a('新增', ['summary/create', 'course_id' => $model->id], [
                 'class' => 'btn btn-primary weekinfo']);
        echo Html::endTag('div');
        
        /* @var $model CourseManage */
        echo Html::beginTag('div', ['class' => 'col-lg-12 col-md-12 col-sm-12 col-xs-12', 'style' => 'padding:0']).
             Html::beginTag('div',['class' => 'summar']).
                
             Html::endTag('div');
        echo Html::endTag('div');
    ?>
    </div>

<?php
$weekinfo = json_encode($weekinfo);
$results = json_encode($results);
$js = 
<<<JS
    var weekinfo = $weekinfo,
        result = $results;
    /** 每月周数列表 */
    $.each(weekinfo, function(){
       $('<a>').text(this['date']).addClass(this['class']).attr({
            "start": this['start'], 
            "end" : this['end']
            //"onclick" : clickWeekinfo($(this))
        }).appendTo($("#weekinfo"));
    });
    $('.weekinfo').click(function(){
        clickWeekinfo($(this));
    });
    /** 周报详情 */
    $('<p>').html('<span>时间：'+result['created_at']+'</span>&nbsp;&nbsp;<span>周报开发者：'+result['create_by']+'</span>').addClass('time').appendTo(".summar");
    $('<p>').html(result['content']).addClass('content').insertAfter('.time');
        
    
    /** 下拉框AJAX */
    function select2Log(e){
        $("#weekinfo").html("");
        $.post("/teamwork/summary/index?course_id=$model->id&date="+$(e).val(),function(data)
        {
            $.each(data['data'], function(){
                $('<a>').text(this['date']).addClass(this['class']).attr({
                     "start": this['start'], 
                     "end" : this['end']
                 }).appendTo($("#weekinfo"));
            });
            $('.weekinfo').click(function(){
                clickWeekinfo($(this));
            }); 
	});
    }
    /** 每周列表AJAX */          
    function clickWeekinfo(e){
        var start = $(e).attr("start"),
            end = $(e).attr("end");
        $.post("/teamwork/summary/view?course_id=$model->id&start="+start+"&end="+end, function(data)
        {
            $('.summar').html('');
            $('<p>').html('<span>时间：'+data['data']['created_at']+'</span>&nbsp;&nbsp;<span>周报开发者：'+data['data']['create_by']+'</span>').addClass('time').appendTo(".summar");
            $('<p>').html(data['data']['content']).addClass('content').insertAfter('.time')
        });
    }
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>