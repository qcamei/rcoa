<?php

use common\models\teamwork\CourseManage;
use frontend\modules\teamwork\TwAsset;
use kartik\widgets\Select2;
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
                    'label' => Yii::t('rcoa', 'Detail').'：'.$model->course->name,
                    'template' => '<li class="course-name active" style="width:50%">{link}</li>',
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container course-manage-view has-title">

   <?= $this->render('_form_detai', [
        'model' => $model,
        'producer' => $producer,
    ]) ?>
    
    <h4>课程进度总结：</h4>
    
    <?php  $form = ActiveForm::begin([
        'id' => 'form-summary-search',
    ]) ?>

    <?= Html::beginTag('div', ['class' => 'col-lg-3 col-md-3 col-sm-4', 'style'=> 'padding:0;margin-bottom:10px;']).
            Select2::widget([
                'name' => 'create_time',
                'value' => $create_time_key,
                'data' => $create_time,
                'hideSearch' => true,
                'options' => [
                    'placeholder' => '请选择...',
                ],
                'pluginEvents' => [
                    'change' => 'function(){ select2Log();}'
                ]
             ]).Html::endTag('div'); ?> 
    
    <?php ActiveForm::end(); ?>
    <?php
        echo Html::beginTag('div', ['class' => 'col-lg-3 col-md-3 col-sm-4', 'style'=> 'margin-bottom:10px;']).
             Html::a('编辑', [
                'summary/update', 'course_id' => $model->id, 'create_time' => $createTime,], 
                ['class' => 'btn btn-primary']).' '.
             Html::a('新增', ['summary/create', 'course_id' => $model->id], ['class' => 'btn btn-primary']).Html::endTag('div');
        /* @var $model CourseManage */
        echo Html::beginTag('div', ['class' => 'col-lg-12 col-md-12 col-sm-4', 'style' => 'padding:0']).
             Html::beginTag('div',['class' => 'summar']).'<p>时间：'.$createdAt.'</p>'.
             $content. 
             Html::endTag('div').Html::endTag('div');
    ?>
    
</div>

<div class="controlbar">
    <div class="container">
        <?= Html::a(Yii::t('rcoa', 'Back'), ['index'], ['class' => 'btn btn-default',/*'onclick'=>'history.go(-1)'*/]) ?>
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
             */
            if($model->getIsNormal() && $twTool->getIsLeader())    
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
        ?>
    </div>
</div>

<?php
//$reflashUrl = Yii::$app->urlManager->createAbsoluteUrl(['/teamwork/default/view']);
//id = "$model->id";
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

    function select2Log(){
        $("#form-summary-search").submit();
    } 
</script>


<?php
    TwAsset::register($this);
?>
