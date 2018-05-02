<?php

use common\models\need\NeedTask;
use common\models\need\NeedTaskUser;
use frontend\modules\need\assets\ModuleAssets;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\web\View;

/* @var $this View */
/* @var $model NeedTask */


ModuleAssets::register($this);

$this->title = $model->task_name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Need Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="container need-task-view has-title">
    <!--基本信息-->
    <?= $this->render('_details', [
        'model' => $model,
    ]) ?>
    <!--开发内容-->
    <?= $this->render('/content/view', [
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model->contents,
        ]),
    ]) ?>
    <!--开发人员-->
    <div id="developer">
        <?= $this->render('/user/index', [
            'model' => $model,
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $model->taskUsers,
            ]),
        ]) ?>
    </div>
    <!--开发成本-->
    <?= $this->render('_table', [
        'model' => $model,
    ]) ?>
    <!--需求附件-->
    <?= $this->render('/attachments/index', [
        'dataProvider' => new ArrayDataProvider([
            'allModels' => $model->attachments,
        ]),
    ]) ?>
    <!--操作记录-->
    <div id="needtasklog"></div>
</div>

<?= $this->render('_btngroup', [
    'model' => $model,
    'isHasReceive' => $isHasReceive,
    'params' => ['index']
]) ?>

<?= $this->render('/layouts/model') ?>

<?php
$js = 
<<<JS
   
    $("#needtasklog").load("../log/index?need_task_id=$model->id");    
        
    //显示模态框
    window.showModal = function(elem){
        $(".myModal").html("");
        $('.myModal').modal("show").load(elem.attr("href"));
        return false;
    }    
    //修改对象
    window.updataObject = function(elem){
        var performancePercent = elem.val();
        $.post('../user/update?id=' + elem.attr("id"), {'performance_percent': performancePercent}, function(rel){
            if(rel['code'] == '200'){
                elem.parent().next().children().text(number_format(costNumber, 2, '.', ''));
            }
        });
        return false;
    }
    //删除对象
    window.delObject = function(elem){
        $.post(elem.attr("href"), function(rel){
            if(rel['code'] == '200'){
                elem.parent('td').parent('tr').remove();
            }
        });
        return false;
    }
JS;
    $this->registerJs($js,  View::POS_READY);
?>