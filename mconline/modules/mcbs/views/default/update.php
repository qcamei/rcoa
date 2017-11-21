<?php

use common\models\mconline\McbsCourse;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model McbsCourse */


//$this->title = Yii::t('app', 'Update {modelClass}: ', [
//    'modelClass' => 'Mcbs Course',
//]) . $model->id;
//$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Mcbs Courses'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<?= 
    $this->render('/layouts/title', [
        'params' => ['index'],
        'title' => Yii::t('app', 'Update').'：'.$model->course_id,
    ]) 
?>

<div class="mcbs-course-update mcbs">
    
    <h1> <?php //Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'itemTypes' => $itemTypes,
        'items' => $items,
        'itemChilds' => $itemChilds,
        'courses' => $courses,
    ]) ?>

</div>

<?=
    $this->render('/layouts/footer', [
        'model' => $model,
        'params' => ['index'],
    ])
?>

<?php
$js = 
<<<JS
        
    
JS;
    $this->registerJs($js,  View::POS_READY);
?>

<?php
    McbsAssets::register($this);
?>