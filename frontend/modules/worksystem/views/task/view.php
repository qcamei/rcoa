<?php

use common\models\worksystem\WorksystemTask;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model WorksystemTask */

$this->title = Yii::t('rcoa/worksystem', 'Worksystem Tasks');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/layouts/_title', [
    'params' => ['index', 
        'create_by' => Yii::$app->user->id, 
        'producer' => Yii::$app->user->id, 
        'assign_people' => Yii::$app->user->id,
        'status' => WorksystemTask::STATUS_DEFAULT,
        'mark' => false,
    ],
    'title' => Yii::t('rcoa', 'View').'：'.$model->name,
]) ?>

<div class="container worksystem worksystem-task-view has-title">

    <?= $this->render('_form_detai', [
        'model' => $model,
        'attributes' => $attributes,
        'producer' => $producer,
    ]) ?>
    
    <span><?= Yii::t('rcoa/worksystem', 'Worksystem Annexes').'：'; ?></span>
    <?php
        foreach ($annexs as $item) 
            echo Html::a($item['name'], ['annex/view', 'id' => $item['id']], ['style' => 'margin-right:10px;']);
    ?>
    
    <?= $this->render('/contentinfo/index', [
        'allModels' => $model->worksystemContentinfos,
    ]) ?>
    
    <?= $this->render('/operation/index', [
        'allModels' => $model->worksystemOperations,
        '_wsOp' => $_wsOp,
    ]) ?>
    
    <?= $this->render('/layouts/_model') ?>

</div>

<?= $this->render('_form_view', [
    'model' => $model,
    'is_assigns' => $is_assigns,
    'is_producer' => $is_producer
]) ?>


<?php
$js = 
<<<JS
      
    /** 此事件在模态框被隐藏（并且同时在 CSS 过渡效果完成）之后被触发。 */
    $('.myModal').on('hidden.bs.modal', function(){
        $(".myModal").html("");
    }); 
            
    /** 审核提交操作 弹出模态框 */
    $('#check-submit').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    }); 
    
    /** 审核创建操作 弹出模态框 */
    $('#check-create').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    }); 
        
    /** 指派创建操作 弹出模态框 */
    $('#assign-create').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    }); 
        
    /** 支撑创建操作 弹出模态框 */
    $('#brace-create').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    }); 
        
    /** 支撑取消操作 弹出模态框 */
    $('#brace-cancel').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    }); 
        
    /** 外包创建操作 弹出模态框 */
    $('#epiboly-create').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    }); 
        
    /** 外包取消操作 弹出模态框 */
    $('#epiboly-cancel').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    }); 
        
    /** 开始制作操作 弹出模态框 */
    $('#start-make').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    }); 
        
    /** 开始制作操作 弹出模态框 */
    $('#acceptance-submit').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    }); 
        
    /** 承接制作操作 弹出模态框 */
    $('#undertake-create').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    });
        
    /** 取消承接操作 弹出模态框 */
    $('#undertake-cancel').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    }); 
        
    /** 创建验收操作 弹出模态框 */
    $('#acceptance-create').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    }); 
        
    /** 完成验收操作 弹出模态框 */
    $('#acceptance-complete').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    }); 
        
    /** 取消任务操作 弹出模态框 */
    $('#cancel').click(function(){
        $(".myModal").html("");
        $('.myModal').modal("show").load($(this).attr("href"));
        return false;
    }); 
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>


<?php
    WorksystemAssets::register($this);
?>