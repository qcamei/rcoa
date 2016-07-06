<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TwAsset;
use kartik\widgets\Select2;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;


/* @var $this View */
/* @var $model CourseManage */

$this->title = Yii::t('rcoa/teamwork', 'Course View');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/teamwork', 'Course Manages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$model->courseSummary->course_id = $model->id;
?>

<div class="title">
    <div class="container">
        <?= $this->title.'：'.$model->course->name ?>
    </div>
</div>

<div class="container course-manage-view has-title">

   <?= $this->render('_form_detai', [
        'model' => $model,
        'statusName' => $statusName,
        'producer' => $producer,
    ]) ?>
    
    <h4>课程进度总结：</h4>
    
    <?php  $form = ActiveForm::begin([
        'id' => 'form-summary-search',
    ]) ?>
    
    <?= Html::beginTag('div', ['class' => 'col-lg-3 col-md-3 col-sm-4', 'style'=> 'padding:0;margin-bottom:10px;']).
             Select2::widget([
                'name' => 'create_time',
                'value' => empty($result) ? $model->courseSummary->create_time : $result->create_time,
                'data' => $create_time,
                'hideSearch' => true,
                'pluginEvents' => [
                    'change' => 'function(){ select2Log();}'
                ]
             ]).Html::endTag('div'); ?> 
    
    <?php ActiveForm::end() ?>
    
    <?php
        echo Html::beginTag('div', ['class' => 'col-lg-3 col-md-3 col-sm-4', 'style'=> 'margin-bottom:10px;']).
             Html::a('编辑', [
                'summary/update', 'course_id' => $model->id, 
                'create_time' => empty($result) ? $model->courseSummary->create_time : $result->create_time,], 
                ['class' => 'btn btn-primary']).' '.
             Html::a('新增', ['summary/create', 'course_id' => $model->id], ['class' => 'btn btn-primary']).Html::endTag('div');
        /* @var $model CourseManage */
        echo Html::beginTag('div', ['class' => 'col-lg-12 col-md-12 col-sm-4', 'style' => 'padding:0']).
             Html::beginTag('div',['style' => 'width:100%;height:350px;border:1px #ccc solid;color:#ccc;padding:10px']).'<p>时间：'.
             (!empty($result)? date('Y-m-d H:i', $result->created_at) : date('Y-m-d H:i', $model->courseSummary->created_at)).'</p>'.
             (!empty($result)? $result->content : $model->courseSummary->content).
             Html::endTag('div').Html::endTag('div');
    ?>
    
</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), '', ['class' => 'btn btn-default','onclick'=>'history.go(-1)']) ?>
        <?php
            /**
             * 编辑 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             * 3、创建者是自己
             */
            if($model->project->getIsNormal() && $model->project->getIsLeader() && $model->create_by == Yii::$app->user->id)
                echo Html::a('编辑', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']).' ';
            /**
             * 配置 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             */
            if($model->project->getIsNormal() && $model->project->getIsLeader())    
                echo Html::a('配置', ['/teamwork/course/link-list', 'course_id' => $model->id], ['class' => 'btn btn-primary']).' ';
           
            echo Html::a('进度', ['/teamwork/course/progress-list', 'course_id' => $model->id], ['class' => 'btn btn-primary']).' ';
            /**
             * 完成 按钮显示必须满足以下条件：
             * 1、必须是状态为【正常】
             * 2、必须是【队长】
             * 3、创建者是自己
             */
            if($model->project->getIsNormal() && $model->project->getIsLeader() && $model->create_by == Yii::$app->user->id)
                echo Html::a('完成', ['carry-out', 'id' => $model->id], ['class' => 'btn btn-danger']).' ';
        ?>
    </div>
</div>

<?php
$js = 
<<<JS
  
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<script type="text/javascript">
    function select2Log(){
        $("#form-summary-search").submit();
    } 
</script>


<?php
    TwAsset::register($this);
?>
