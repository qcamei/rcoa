<?php

use common\models\demand\DemandTask;
use frontend\modules\demand\assets\DemandAssets;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $model DemandTask */

$this->title = Yii::t('rcoa/demand', 'Demand View').'：'.$model->course->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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
        'progress' => $progress,
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
    
</div>

<?= $this->render('_form_view',[
    'model' => $model,
    'dtTool' => $dtTool,
]) ?>

<div class="demand-task">
    
    <?= $this->render('_form_model')?>
    
</div>

<?php
$js = 
<<<JS
    /** 此事件在模态框被隐藏（并且同时在 CSS 过渡效果完成）之后被触发。 */
    $('.myModal').on('hidden.bs.modal', function(){
        window.location.reload();
    }); 
        
    /** 完成操作 弹出模态框 */
    $('#complete').click(function()
    {
        var urlf = $(this).attr("href");
        $('.myModal').modal({remote:urlf});
        return false;
    });
    
    /** 取消操作 弹出模态框 */
    $('#cancel').click(function()
    {
        var urlf = $(this).attr("href");
        $('.myModal').modal({remote:urlf});
        return false;
    });
     
    /** 审核不通过操作 弹出模态框 */
    $('#check-create').click(function()
    {
        var urlf = $(this).attr("href");
        $('.myModal').modal({remote:urlf});
        return false;
    });
    
    /** 查看审核记录 */   
    $('.view-check').click(function(){
        var urlf = $(this).attr("href");
        $(".myModal").modal({remote:urlf});
        return false;
    });
        
    /** 承接操作 弹出模态框 */
    $('#undertake').click(function()
    {
        var urlf = $(this).attr("href");
        $('.myModal').modal({remote:urlf});
        return false;
    });
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    DemandAssets::register($this);
?>