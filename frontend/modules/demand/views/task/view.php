<?php

use common\models\demand\DemandTask;
use frontend\modules\demand\assets\DemandAssets;
use wskeee\rbac\RbacName;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $model DemandTask */

$this->title = Yii::t('rcoa/demand', 'Demand View').'：'.$model->course->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

/** 判断添加课程产品【标识】 */
if($model->getIsStatusDefault() || $model->getIsStatusAdjusimenting())
    $mark = 1;
else 
    $mark = 0;
/** 判断是否提示创建课程开发数据 */
if($model->getIsStatusDeveloping() && $model->undertake_person == Yii::$app->user->id)
   $isCreateDevelop = 1;
else
   $isCreateDevelop = 0;

?>

<div class="title">
    <div class="container">
        <?= Breadcrumbs::widget([
            'options' => ['class' => 'breadcrumb'],
            'homeLink' => [
                'label' => Yii::t('rcoa/demand', 'Demand Tasks'),
                'url' => ['index', 'status' => DemandTask::STATUS_DEFAULT],
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
    ]) ?>
        
    <span><?= Yii::t('rcoa/demand', 'Demand Task Annexes').'：'; ?></span>
    <?php
        foreach ($annex as $value) {
            echo Html::a($value->name, ['annex/view', 'id' => $value->id], ['style' => 'margin-right:10px;']);
        }
    ?>
    
    <h4 id="anchor"><?= Yii::t('rcoa/demand', 'Demand Task Products'); ?></h4>
    <div class="demand-task-product">
        <div class="col-lg-12 col-md-12" style="padding:0px;">
            <?php if(Yii::$app->user->can(RbacName::PERMSSION_DEMAND_TASK_CREATE_PRODUCT) && $mark && $model->create_by == Yii::$app->user->id):?>
            <div class="add">
                <?= Html::a('添加', ['product/list', 'task_id' => $model->id], 
                                    ['id' => 'add' ,'class' => 'btn btn-success btn-sm',
                                      'data-toggle' => 'tooltip', 'data-placement'=> 'top', 'title' => '点击这里添加课程产品！'
                                    ]); ?>
            </div>
            <?php endif;?>
            <div id="demand-task-product-index"></div>
        </div>
    </div>
    
    <?= $this->render('/check/index',[
        'model' => $model->demandChecks,
    ]) ?>
    
    <?= $this->render('/acceptance/index',[
        'model' => $model->demandAcceptances,
    ]) ?>

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
    /** 滚动到添加课程产品处 */
    if($sign){
        $('html,body').animate({scrollTop:($('#anchor').offset().top) - 140},1000);
        $('#add').tooltip('show'); 
    }   
    /** 此事件在模态框被隐藏（并且同时在 CSS 过渡效果完成）之后被触发。 */
    $('.myModal').on('hidden.bs.modal', function(){
        $(".myModal").html("");
    }); 
    
    //加载已选课程产品列表
    $("#demand-task-product-index").load("/demand/product/index?task_id=$model->id");
    /** 单击添加按钮显示产品列表 模态框 */    
    $('#add').click(function(){
        $(".myModal").html("");
        $(this).tooltip('hide');  
        $(".myModal").modal('show').load($(this).attr("href"));
        return false;
    });
        
    /** 提交任务操作 弹出模态框 */
    $('#task-submit-check').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });    
       
    /** 完成操作 弹出模态框 */
    $('#complete').click(function(){
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
    
    /** 提交任务操作 弹出模态框 */
    $('#submit-task').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
        
    /** 验收不通过操作 弹出模态框 */
    $('#acceptance-create').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    }); 
        
    /** 查看验收记录 */   
    $('.view-acceptance').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
   
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    DemandAssets::register($this);
?>