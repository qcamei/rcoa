<?php

use wskeee\framework\models\Project;
use yii\web\View;


/* @var $this View */
/* @var $model Project */

$this->title = Yii::t('app', '{Create} {Item Child ID}',[
    'Create'=>  Yii::t('app', 'Create'), 'Item Child ID'=>  Yii::t('app', 'Item Child ID')]);
if ($model->parent_id != null) {
    $this->params['breadcrumbs'][] = ['label' => $colleges[$model->parent_id], 'url' => ['/need/college/view', 'id' => $model->parent_id]];
}
$this->params['breadcrumbs'][] = Yii::t('rcoa/basedata', 'Create');

?>

<div class="main project-create">

    <?= $this->render('_form', [
        'model' => $model,
        'colleges' => $colleges,
    ]) ?>

</div>
