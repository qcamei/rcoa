<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\College */

$this->title = Yii::t('rcoa/basedata', 'Update')."：".$model->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container college-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
