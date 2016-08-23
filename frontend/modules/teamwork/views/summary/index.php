<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TwAsset;
use kartik\widgets\Select2;
use wskeee\rbac\RbacName;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */

?>

<?php
    $weekinfo = [];     //每月的周数列表信息
    $results = [];      //查询的结果数组
    $currentTime = date('Y-m-d',  time());  //当前时间
    $startTime = empty($model->real_start_time) ? $currentTime : date('Y-m-d', strtotime($model->real_start_time));
    $isAuthorization = Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER);    //是否为项目管理员
    foreach ($twTool->getWeekInfo($startTime, end($weeklyMonth)) as $value) {
        $result = $twTool->getWeeklyInfo($model->id, $value['start'], $value['end']);
        $weekinfo[] = [
            'date' => date('m/d', strtotime($value['start'])).'～'.date('m/d', strtotime($value['end'])),
            'class' => !empty($result) ? 'btn btn-info weekinfo' : ($currentTime > $value['end'] ? 
                        (/*Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER) ? 'btn btn-danger weekinfo' :*/ 'btn btn-danger weekinfo disabled') : 
                        ($currentTime >= $value['start'] && $currentTime <= $value['end'] ? 
                        'btn btn-info weekinfo disabled' : 'btn btn-default weekinfo disabled')),
            'icon' => $currentTime < $value['start'] ?  'not-to' : 
                        (empty($result) && $currentTime > $value['end'] ? 'leak-write' : 
                            ($currentTime >= $value['start'] && $currentTime <= $value['end'] ? 
                                'this-week' : 'already-write')),
            'start' => $value['start'],
            'end' => $value['end']
        ];
        if(!empty($result)){
            $results = [
                'course_id' => $result->course_id,
                'create_time' => $result->create_time,
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

<h4><?= Yii::t('rcoa/teamwork', 'Development Weekly').'：'; ?></h4>
<span class="team-leader">
    <?php echo Yii::t('rcoa/teamwork', 'This Week Weekly Developer').'：'; 
        echo empty($model->weekly_editors_people)? '无' : 
            $model->weeklyEditorsPeople->u->nickname.' ('.$model->weeklyEditorsPeople->position.')' 
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
            /**
             * 提交 按钮显示必须满足以下条件：
             * 1、状态非为【已完成】
             * 2、周报必须不能为空
             * 3、(必须是【队长】 and 课程 【创建者】 是自己)
             * or 【周报编辑人】 or 【项目管理员】 or 【课程负责人】
             */
            
            if($model->getIsNormal() && !empty($weeklyInfoResult)
                && (($twTool->getIsLeader() && $model->create_by == \Yii::$app->user->id) 
                || $model->weekly_editors_people == \Yii::$app->user->id 
                || $model->course_principal == \Yii::$app->user->id 
                || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
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
           
            if($model->getIsNormal() && empty($weeklyInfoResult)
                && (($twTool->getIsLeader() && $model->create_by == \Yii::$app->user->id) 
                || $model->weekly_editors_people == \Yii::$app->user->id 
                || $model->course_principal == \Yii::$app->user->id
                || Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER)))
                echo Html::a(Yii::t('rcoa/teamwork', 'Create Weekly'), ['summary/create', 'course_id' => $model->id], [
                 'class' => 'btn btn-primary weekinfo']);
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
       $('<a>').html('<i class="state-icon '+this['icon']+'"></i>'+this['date']).addClass(this['class']).attr({
            "start": this['start'], 
            "end" : this['end']
        }).appendTo($("#weekinfo"));
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
                $('<a>').html('<i class="state-icon '+this['icon']+'"></i>'+this['date']).addClass(this['class']).attr({
                     "start": this['start'], 
                     "end" : this['end']
                 }).appendTo($("#weekinfo"));
            });
            /** 单击选中 */
            $('.weekinfo').click(function(){
                clickSelect($(this));
            }); 
	});
    }
    /** 每周列表AJAX */          
    function clickWeekinfo(e){
        var start = $(e).attr("start"),
            end = $(e).attr("end"),
            isAuthorization = $isAuthorization; 
        $.post("/teamwork/summary/view?course_id=$model->id&start="+start+"&end="+end, function(data)
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