<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TwAsset;
use frontend\modules\teamwork\utils\TeamworkTool;
use kartik\widgets\Select2;
use wskeee\rbac\RbacName;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $twTool TeamworkTool */
$isAuthorization = Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER);    //是否为项目管理员
?>

<?php
    $start = 0;
    $weekinfo = [];                                 //每月的周数列表信息
    $results = [];                                  //查询的结果数组
    $currentTime = date('Y-m-d',  time());          //当前时间
    $thisWeek = $twTool->getWeek($currentTime);     //当前周
    $startTime = date('Y-m-d', strtotime($model->real_start_time));     //课程计划开始时间
    $weeks = $twTool->getWeekInfo($startTime, end($weeklyMonth));       //获取1月每周的星期1和星期天日期
    $weekStart = reset($weeks);
    $weekEnd = end($weeks);
    $date = ['start' => $weekStart['start'], 'end' => $weekEnd['end']];
    $weeklys = $twTool->getWeeklyInfo($model->id, $date);
    $weekly = end($weeklys);
    $weeklyDate = ArrayHelper::getColumn($weeklys, 'create_time');
    foreach ($weeks as &$week) {
        if(!empty($weeklyDate)){
            for ($i = $start; $i < count($weeklyDate); $i++) {
                if ($week['start'] <= $weeklyDate[$i] && $week['end'] >= $weeklyDate[$i]) {
                    $week['has'] = true;
                    //$start = $i + 1;
                    break;
                }else {
                    $week['has'] = false;
                }
            }
        }  else {
            $week['has'] = false;
        }
        $weekinfo[] = [
            'date' => date('m/d', strtotime($week['start'])).'～'.  date('m/d', strtotime($week['end'])),
            'class' => $currentTime < $week['start'] ? 'btn btn-default weekinfo disabled' : 
                       ($week['has'] == false && $currentTime > $week['end'] ? 'btn btn-danger weekinfo disabled' : 
                       ($currentTime >= $week['start'] && $currentTime <= $week['end'] ? 'btn btn-info weekinfo active' : 'btn btn-info weekinfo')),
            'icon' => $currentTime < $week['start'] ? 'not-to' : ($week['has'] == false && $currentTime > $week['end'] ? 'leak-write' :
                    ($currentTime >= $week['start'] && $currentTime <= $week['end'] ? 'this-week' : 'already-write')),
            'week' => $week
        ];
    }
    
    if(!empty($weekly) && ($weekly->create_time >= $thisWeek['start'] && $weekly->create_time <= $thisWeek['end'])){
        $results = [
            'create_time' => $weekly->create_time,
            'content' => $weekly->content,
            'create_by' => $model->weeklyEditorsPeople->user->nickname,
            'created_at' => date('Y-m-d H:i', $weekly->created_at)
        ];
    }else{
        $results = [
            'create_time' => null,
            'content' => '无',
            'create_by' => '无',
            'created_at' => '无'
        ];
    }
?>

<h4><?= Yii::t('rcoa/teamwork', 'Development Weekly').'：'; ?></h4>
<span class="team-leader">
    <?php echo Yii::t('rcoa/teamwork', 'This Week Weekly Developer').'：'; 
        echo empty($model->weekly_editors_people)? '' : 
            $model->weeklyEditorsPeople->user->nickname; 
    ?>
</span>
    
    <div class="row">
    <?php
        /** 下拉月份选择 */
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
        /** 每月周数列表信息 */
        echo Html::beginTag('div', [
            'id' => 'weekinfo',
            'class' => 'col-lg-8 col-md-10 col-sm-12 col-xs-12', 
            'style' => 'padding:0px;',
        ]);
        echo Html::endTag('div');
        /** 编辑、新增按钮 */
        echo Html::beginTag('div', ['class' => 'col-lg-2 col-md-2 col-sm-2 col-xs-5', 'style' => 'padding:0px;']);
            if(($twTool->getIsAuthority('is_leader', 'Y') && $model->create_by == Yii::$app->user->id) 
                || $twTool->getIsAuthority('id', $model->weekly_editors_people)
                || $twTool->getIsAuthority('id', $model->course_principal)
                || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER))
            {
                /**
                 * 提交 按钮显示必须满足以下条件：
                 * 1、状态非为【已完成】
                 * 2、周报必须不能为空
                 * 3、(必须是【队长】 and 课程 【创建者】 是自己)
                 * or 【周报编辑人】 or 【项目管理员】 or 【课程负责人】
                 */
                if($model->getIsNormal() && $weeklyInfoResult)
                    echo Html::a(Yii::t('rcoa/teamwork', 'Updated Weekly'), [
                        'summary/update', 'course_id' => $model->id, 'create_time' => $results['create_time']], 
                        ['id' => 'update', 'class' => 'btn btn-primary weekinfo']);
                /**
                 * 提交 按钮显示必须满足以下条件：
                 * 1、状态非为【已完成】
                 * 2、周报必须为空
                 * 3、(必须是【队长】 and 课程 【创建者】 是自己)
                 * or 【周报编辑人】 or 【项目管理员】 or 【课程负责人】
                 */
                if($model->getIsNormal() && !$weeklyInfoResult)
                    echo Html::a(Yii::t('rcoa/teamwork', 'Create Weekly'), ['summary/create', 'course_id' => $model->id], [
                     'class' => 'btn btn-primary weekinfo']);
            }
        echo Html::endTag('div');
        /** 内容信息 */
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
$createdAt = Yii::t('rcoa/teamwork', 'Created At');
$createdBy = Yii::t('rcoa', 'Create By');
$isAuthorization = $isAuthorization == true ? 1 : 0;
$js = 
<<<JS
    var weekinfo = $weekinfo,
        result = $results,
        createdAt = "$createdAt",
        createdBy = "$createdBy";
    /** 每月周数列表 */
    $.each(weekinfo, function(){
        $('<a>').html('<i class="state-icon '+this['icon']+'"></i>'+this['date']).addClass(this['class']).attr(
           'date', this.week['start']+'/'+this.week['end']).appendTo($("#weekinfo"));
    });
    /** 单击选中 */
    $('.weekinfo').click(function(){
        clickSelect($(this));
    });
    /** 周报详情 */
    $('<p>').html('<span>'+createdAt+'：'+result['created_at']+'</span>&nbsp;&nbsp;<span>'+createdBy+'：'+result['create_by']+'</span>').addClass('time').appendTo(".summar");
    $('<p>').html(result['content']).addClass('content').insertAfter('.time');
        
    
    /** 下拉框AJAX */
    function select2Log(e){
        $("#weekinfo").html("");
        $.post("/teamwork/summary/index?course_id=$model->id&date="+$(e).val(),function(data)
        {
            $.each(data['data'], function(){
                $('<a>').html('<i class="state-icon '+this['icon']+'"></i>'+this['date']).addClass(this['class']).attr(
                    'date', this.week['start']+'/'+this.week['end']).appendTo($("#weekinfo"));
            });
            /** 单击选中 */
            $('.weekinfo').click(function(){
                clickSelect($(this));
            }); 
	});
    }
    /** 每周列表AJAX */          
    function clickWeekinfo(e){
        var date = $(e).attr('date'),
            isAuthorization = $isAuthorization; 
        $.post("/teamwork/summary/view?course_id=$model->id&date="+date, function(data)
        {
            if(isAuthorization)
                $('#update').attr('href', '/teamwork/summary/update?course_id=$model->id&create_time='+data['data']['create_time'])
            
            /** 周报详情 */
            $('.summar').html('');
            $('<p>').html('<span>'+createdAt+'：'+data['data']['created_at']+'</span>&nbsp;&nbsp;<span>'+createdBy+'：'+data['data']['create_by']+'</span>').addClass('time').appendTo(".summar");
            $('<p>').html(data['data']['content']).addClass('content').insertAfter('.time')
        });
    }
    /** 按钮选中状态 */
    function clickSelect(e){
        if($('.btn-info').hasClass('active'))
            $('.btn-info').removeClass('active')
        $(e).addClass('active');
        clickWeekinfo($(e));
    }
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    TwAsset::register($this);
?>