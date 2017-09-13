<?php

use common\models\demand\DemandTask;
use frontend\modules\demand\assets\DemandAssets;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\Breadcrumbs;


/* @var $this View */
/* @var $model DemandTask */

$this->title = Yii::t('rcoa/demand', 'Create Demand Task');
$this->params['breadcrumbs'][] = ['label' => Yii::t('rcoa/demand', 'Demand Tasks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/layouts/_title', [
    'params' => ['index'],
    'title' => Yii::t('rcoa', 'Create'),
]) ?>

<div class="container demand demand-task-create has-title">

    <?= $this->render('_form', [
        'model' => $model,
        'itemTypes' => $itemTypes,
        'items' => $items,
        'itemChilds' => $itemChilds,
        'courses' => $courses,
        'teachers' => $teachers,
        'teams' => $teams,
        'workitmType' => $workitmType,
        'workitem' => $workitem,
    ]) ?>

</div>

<?= $this->render('/layouts/_form_navbar', [
    'model' => $model,
    'params' => ['index'],
]) ?>


<?php
$js = 
<<<JS
    
    
JS;
    //$this->registerJs($js,  View::POS_READY);
?>

<?php
    DemandAssets::register($this);
?>