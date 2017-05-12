<?php

use common\models\demand\DemandTask;
use common\widgets\cslider\CSliderAssets;
use frontend\modules\demand\assets\ChartAsset;
use frontend\modules\demand\assets\DemandAssets;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $model DemandTask */

$this->title = Yii::t('rcoa/demand', 'Demand View').'：'.$model->course->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/** 判断是否提示审核 */
if($model->getIsStatusDefault() && $model->create_by == Yii::$app->user->id)
    $isCheck = 1;
else 
   $isCheck = 0; 

/** 判断是否提示创建课程开发数据 */
if($model->getIsStatusDeveloping() && $model->undertake_person == Yii::$app->user->id)
   $isCreateDevelop = 1;
else
   $isCreateDevelop = 0;

?>

<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb breadcrumb-title'],
            'homeLink' => [
                'label' => Yii::t('rcoa/demand', 'Demand Tasks'),
                'url' => ['index'],
                'template' => '<li class="course-name">{link}</li>',
            ],
            'links' => [
                [
                    'label' => Yii::t('rcoa/demand', 'Demand View').'：'.$model->course->name,
                    'template' => '<li class="course-name active" style="width:50%">{link}</li>',
                ],
            ]
        ]);?>
    </div>
</div>

<div class="container demand-task-view has-title demand-task">
    
    <?= $this->render('_form_detai', [
        'model' => $model,
        'workitmType' => $workitmType,
        'workitems' => $workitem,
    ]) ?>
        
    <span><?= Yii::t('rcoa/demand', 'Demand Task Annexes').'：'; ?></span>
    <?php
        foreach ($annex as $value) {
            echo Html::a($value->name, ['annex/view', 'id' => $value->id], ['style' => 'margin-right:10px;']);
        }
    ?>
    
    <?= $this->render('/check/index',[
        'model' => $model->demandChecks,
    ]) ?>
    
    <h4><?= Html::encode(Yii::t('rcoa/demand', 'Demand Acceptances')) ?></h4>
    <div id="demand-acceptance-view"></div>    
    
</div>

<?= $this->render('_form_view',[
    'model' => $model,
    'dtTool' => $dtTool,
    'rbacManager' => $rbacManager,
]) ?>

<div class="demand-task">
    <?= $this->render('_form_model')?>    
</div>

<?php
$js = 
<<<JS
        
    //加载课程验收记录
    $("#demand-acceptance-view").load("/demand/acceptance/view?demand_task_id=$model->id");    
      
    /** 此事件在模态框被隐藏（并且同时在 CSS 过渡效果完成）之后被触发。 */
    $('.myModal').on('hidden.bs.modal', function(){
        $(".myModal").html("");
    }); 
    
    /** 提交任务操作 弹出模态框 */
    $('#task-submit-check').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });    
       
 
    /** 取消操作 弹出模态框 */
    $('#cancel').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
     
    /** 审核不通过操作 弹出模态框 */
    $('#check-create').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
    
    /** 查看审核记录 */   
    $('.view-check').click(function(){
       $(".myModal").html("");
       $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
        
    /** 承接操作 弹出模态框 */
    $('#undertake').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
       
   /** 创建开发操作 弹出模态框 */
    if($isCreateDevelop && $develop){
        $('.myModal').modal("show");
        $('#myModalBody').html('<i class="state-icon already_write"></i>是否现在就开始创建开发课程数据？');
        $("#button").click(function(){
            location.href = $('#create-develop').attr("href");
        });
    }
        
    /** 待确定操作 弹出模态框 */
    $('#wait-confirm').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
        
    /** 申诉操作 弹出模态框 */
    $('#appeal-create').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
        
    /** 回复操作 弹出模态框 */
    $('#reply-create').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });    
        
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    DemandAssets::register($this);
    ChartAsset::register($this);
    CSliderAssets::register($this);
?>