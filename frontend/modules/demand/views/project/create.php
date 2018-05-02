<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\Project */

$this->title = Yii::t('rcoa/basedata', '{Create} {Project}',['Create'=>  Yii::t('rcoa/basedata', 'Create'),'Project'=>  Yii::t('rcoa/basedata', 'Project')]);
if($model->parent_id != null)
    $this->params['breadcrumbs'][] = ['label' => $colleges[$model->parent_id], 'url' => ['/demand/college/view','id'=>$model->parent_id]];
$this->params['breadcrumbs'][] = Yii::t('rcoa/basedata', 'Create');
?>
<div class="container project-create">

    <?= $this->render('_form', [
        'model' => $model,
        'colleges' => $colleges,
    ]) ?>

</div>
