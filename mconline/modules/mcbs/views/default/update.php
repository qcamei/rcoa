<?php

use common\models\mconline\McbsCourse;
use mconline\modules\mcbs\assets\McbsAssets;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $model McbsCourse */

$this->title = Yii::t(null, '{Update}{Mcbs}{Courses}: ', [
            'Update' => Yii::t('app', 'Update'),
            'Mcbs' => Yii::t('app', 'Mcbs'),
            'Courses' => Yii::t('app', 'Courses'),
        ]) . $model->course->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t(null, '{Mcbs}{Courses}', [
            'Mcbs' => Yii::t('app', 'Mcbs'),
            'Courses' => Yii::t('app', 'Courses'),
        ]), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->course->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
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

<?php
$js = <<<JS
        
    
JS;
$this->registerJs($js, View::POS_READY);
?>

<?php
McbsAssets::register($this);
?>