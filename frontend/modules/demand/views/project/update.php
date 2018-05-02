<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\Project */

$this->title = Yii::t('rcoa/basedata', 'Update'). '：' . $model->name;
$this->params['breadcrumbs'][] = Yii::t('rcoa/basedata', 'Update').'：'.$model->name;
?>
<div class="container project-update">

    <?= $this->render('_form', [
        'model' => $model,
        'colleges'=>$colleges,
    ]) ?>

</div>
