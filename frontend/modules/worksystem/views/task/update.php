<?php

use common\models\worksystem\WorksystemTask;
use frontend\modules\worksystem\assets\WorksystemAssets;
use yii\helpers\ArrayHelper;
use yii\web\View;

/* @var $this View */
/* @var $model WorksystemTask */

$this->title = Yii::t('rcoa/worksystem', 'Update Worksystem Task');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/worksystem', 'Worksystem Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/worksystem', 'Update');
?>

<?= $this->render('/layouts/_title', [
    'params' => ['index', 
        'create_by' => Yii::$app->user->id, 
        'producer' => Yii::$app->user->id, 
        'assign_people' => Yii::$app->user->id,
        'status' => WorksystemTask::STATUS_DEFAULT,
        'mark' => false,
    ],
    'title' => Yii::t('rcoa', 'Update'),
]) ?>

<div class="container worksystem worksystem-task-update has-title">

    <?= $this->render('_form', [
        'model' => $model,
        'itemTypes' => $itemTypes,
        'items' => $items,
        'itemChilds' => $itemChilds,
        'courses' => $courses,
        'taskTypes' => ArrayHelper::map($taskTypes, 'id', 'name'),
        'teams' => $teams,
        'annexs' => $annexs,
    ]) ?>
    
    <!-- 显示模态框 -->
    <?= $this->render('/layouts/_model') ?>

</div>

<?= $this->render('/layouts/_form_navbar', [
    'model' => $model,
    'params' => ['view', 'id' => $model->id],
]) ?>

<?php
$js = 
<<<JS
        
    $('#add-attribute').load("/worksystem/add-attributes/update?task_id=$model->id");
    $('#contentinfo').load("/worksystem/contentinfo/update?task_id=$model->id");
        
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    WorksystemAssets::register($this);
?>