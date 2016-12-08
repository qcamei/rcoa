<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model wskeee\framework\models\College */

$this->title = Yii::t('rcoa/basedata', '{Create}',['Create'=>  Yii::t('rcoa/basedata', 'Create')]);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container college-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
