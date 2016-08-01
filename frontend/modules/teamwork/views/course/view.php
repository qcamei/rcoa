<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TwAsset;
use kartik\widgets\Select2;
use wskeee\rbac\RbacName;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;


/* @var $this View */
/* @var $model CourseManage */

$this->title = Yii::t('rcoa/teamwork', 'Course View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Course Manages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb'],
            'homeLink' => [
                'label' => Yii::t('rcoa/teamwork', 'Courses'),
                'url' => ['index'],
                'template' => '<li class="course-name">{link}</li>',
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa/teamwork', 'Course View').'：'.$model->course->name,
                    'template' => '<li class="course-name active" style="width:50%">{link}</li>',
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container course-manage-view has-title item-manage">

   <?= $this->render('_form_detai', [
        'model' => $model,
        'producer' => $producer,
    ]) ?>
    
    <span>课程附件：</span>
    <?php
        foreach ($annex as $value) {
            echo Html::a($value->name, ['annex/view', 'id' => $value->id], ['style' => 'margin-right:10px;']);
        }
    ?>
    
    <h4> 开发周报：</h4>
    <span style="color: blue;">本周开发者：<?= empty($model->weekly_editors_people)? '无' : 
            $model->weeklyEditorsPeople->u->nickname.' ('.$model->weeklyEditorsPeople->position.')' ?>
    </span>
    
    <div class="row">
    <?php
        echo Html::beginTag('div', ['class' => 'col-lg-2 col-md-10 col-sm-10 col-xs-12 weekinfo', 'style' => 'padding:0px;']);
            echo  Select2::widget([
                'name' => 'create_time',
                'value' => $weeklyMonthValue,
                'data' => $weeklyMonth,
                'hideSearch' => true,
                'options' => [
                    'placeholder' => '请选择...',
                ],
                'pluginEvents' => [
                    'change' => 'function(){ select2Log($(this).val());}'
                ]
             ]);
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'col-lg-7 col-md-10 col-sm-10 col-xs-12', 'style' => 'padding:0px;']);
            foreach ($weekinfo as $value) {
                $result = $twTool->getWeeklyInfo($model->id, $value['start'], $value['end']);
                echo Html::a(date('m-d',  strtotime($value['start'])).'～'.date('m-d',  strtotime($value['end'])), [
                    'view', 'id' => $model->id, 'start' => $value['start'], 'end' => $value['end']
                ], ['class' => !empty($result) ?  'btn btn-info weekinfo' : 'btn btn-info weekinfo disabled',]);
            }
        echo Html::endTag('div');
        echo Html::beginTag('div', ['class' => 'col-lg-2 col-md-2 col-sm-2 col-xs-12', 'style' => 'padding:0px;']).
             Html::a('编辑', [
                'summary/update', 'course_id' => $model->id, 'create_time' => $createTime,], 
                ['class' => 'btn btn-primary weekinfo']).' '.
             Html::a('新增', ['summary/create', 'course_id' => $model->id], ['class' => 'btn btn-primary weekinfo']).Html::endTag('div');
        /* @var $model CourseManage */
        echo Html::beginTag('div', ['class' => 'col-lg-12 col-md-12 col-sm-4', 'style' => 'padding:0']).
             Html::beginTag('div',['class' => 'summar']).'<p class ="time">时间：'.$createdAt.'</p>'.
             $content. 
             Html::endTag('div').Html::endTag('div');
    ?>
    </div>
    
</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), '#', ['class' => 'btn btn-default','onclick'=>'history.go(-1)']) ?>
        <?php
            /**
             * 编辑 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             * 3、创建者是自己
             */
            if($model->getIsNormal() && $twTool->getIsLeader() && $model->create_by == Yii::$app->user->id)
                echo Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).' ';
            /**
             * 配置 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             * 3、创建者是自己
             */
            if($model->getIsNormal() && $twTool->getIsLeader() && $model->create_by == Yii::$app->user->id)    
                echo Html::a('配置', ['/teamwork/courselink/index', 'course_id' => $model->id], ['class' => 'btn btn-primary']).' ';
           
            echo Html::a('进度', ['/teamwork/courselink/progress', 'course_id' => $model->id], ['class' => 'btn btn-primary']).' ';
            /**
             * 完成 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             * 3、创建者是自己
             */
            if($model->getIsNormal() && $twTool->getIsLeader() && $model->create_by == Yii::$app->user->id)
                echo Html::a('完成', ['carry-out', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
            
            /**
             * 恢复 按钮显示必须满足以下条件：
             * 1、必须是状态为【已经完成】
             * 2、必须是【项目管理员】
            */
            if($model->getIsCarryOut() && Yii::$app->user->can(RbacName::ROLE_PROJECT_MANAGER))
                echo Html::a('恢复', ['normal', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
        ?>
    </div>
</div>

<?php
$reflashUrl = Yii::$app->urlManager->createAbsoluteUrl(['/teamwork/default/view']);
$id = "$model->id";
$js = 
<<<JS
    var reflashUrl = "",
        
    $('#create_time').change(function(){
        select2Log($(this).val());
    });
    function select2Log(value)
    {  
        location.href = reflashUrl+'?id='+id+'&date='+value;
    }
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<script type="text/javascript">
    var reflashUrl = "<?= Yii::$app->urlManager->createAbsoluteUrl(['/teamwork/course/view']); ?>";
        id = "<?= $model->id; ?>";
    function select2Log(value){
       location.href = reflashUrl+'?id='+id+'&month='+value;
    } 
</script>

<?php
    TwAsset::register($this);
?>
