<?php

use common\models\worksystem\WorksystemTask;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\helpers\ArrayHelper;
use yii\web\View;


/* @var $this View */
/* @var $model WorksystemTask */

$this->title = Yii::t('rcoa/worksystem', 'Create Worksystem Task');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/layouts/_title', [
    'params' => ['index'],
    'title' => Yii::t('rcoa', 'Create'),
]) ?>

<div class="container worksystem worksystem-task-create has-title">

    <?= $this->render('_form', [
        'model' => $model,
        'itemTypes' => $itemTypes,
        'items' => $items,
        'itemChilds' => $itemChilds,
        'courses' => $courses,
        'taskTypes' => ArrayHelper::map($taskTypes, 'id', 'name'),
        'teams' => $teams,
    ]) ?>

    <!-- 显示模态框 -->
    <?= $this->render('_form_model', [
        'taskTypes' => $taskTypes,
    ]) ?>
    
</div>

<?= $this->render('/layouts/_form_navbar', [
    'model' => $model,
    'params' => ['index'],
]) ?>

<?php
$js = 
<<<JS
    
    $('.myModal').modal({show:'show', backdrop:'static', keyboard:false});
    $('.clickselected').click(function(){
        var dataValue = $(this).attr('data-value');
        $('#worksystemtask-task_type_id').find('option[value='+dataValue+']').attr('selected', true);
        $('#add-attribute').load("/worksystem/add-attributes/create?task_type_id="+dataValue);
    });
        
    $('#contentinfo').load("/worksystem/contentinfo/index");
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    WorksystemAssets::register($this);
?>
