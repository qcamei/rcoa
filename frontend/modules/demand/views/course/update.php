<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\Course */

$this->title = Yii::t('rcoa/basedata', '{Update} {Course}',['Update'=>  Yii::t('rcoa/basedata', 'Update'),'Course'=>  Yii::t('rcoa/basedata', 'Course')]);
if($model->parent_id != null)
{
    $this->params['breadcrumbs'][] = ['label' => $colleges[$model->parent->parent_id], 'url' => ['/demand/college/view','id'=>$model->parent->parent_id]];
    $this->params['breadcrumbs'][] = ['label' => $projects[$model->parent_id], 'url' => ['/demand/project/view','id'=>$model->parent_id]];
}
$this->params['breadcrumbs'][] = $model->name;
?>
<div class="container course-update">

    <?= $this->render('_form', [
        'model' => $model,
        'colleges'=>$colleges,
        'projects'=>$projects,
    ]) ?>

</div>
