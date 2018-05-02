<?php

use wskeee\framework\models\Course;
use yii\web\View;

/* @var $this View */
/* @var $model Course */

$this->title = Yii::t('app', '{Update} {Courses}',['Update'=>  Yii::t('app', 'Update'),'Courses'=>  Yii::t('app', 'Courses')]);
if($model->parent_id != null)
{
    $this->params['breadcrumbs'][] = ['label' => $colleges[$model->parent->parent_id], 'url' => ['/need/college/view','id'=>$model->parent->parent_id]];
    $this->params['breadcrumbs'][] = ['label' => $projects[$model->parent_id], 'url' => ['/need/project/view','id'=>$model->parent_id]];
}
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="main course-update">

    <?= $this->render('_form', [
        'model' => $model,
        'colleges'=>$colleges,
        'projects'=>$projects,
    ]) ?>

</div>
